<?php

namespace Plugin\Track;

use Ip\Exception;
use Ip\Response\PageNotFound;
use Ip\Response\Redirect;
use Plugin\GrooaUser\Model\GrooaUser;
use Plugin\Track\Model\AwsS3;
use Plugin\Track\Model\Module;

use Plugin\GrooaPayment\Model\TrackOrder;
use Plugin\Track\Service\CourseService;

class SiteController
{
    private $courseService;

    /**
     *
     * @param string $courseLabel
     * @return \Ip\Response\Layout
     * @throws \Ip\Exception
     */
    public function viewCoursePage(String $courseLabel)
    {
        if (!isset($this->courseService)) {
            $this->courseService = new CourseService();
        }

        $course = $this->courseService->findByLabel($courseLabel);

        if(is_null($course)) {
            return new PageNotFound("We cannot find any course with label: $courseLabel");
        }

        $layout = new \Ip\Response\Layout(
            ipView('view/viewCoursePage.php', [ 'course' => $course ])->render()
        );

        $layout->setLayout('onlineCourse.php');

        return $layout;
    }

    public function listTracks()
    {
        $tracks = Module::findAll();

        $layout = new \Ip\Response\Layout(
            ipView('view/list.php', ['tracks' => $tracks])->render());
        $layout->setLayout('track.php');
        //$layout->setLayoutVariable('coverImage', $track['large_thumbnail']);

        return $layout;
    }

    /**
     * @controller
     * Present the specific Track,
     * and allows users and businesses to purchase it.
     *
     * @param $trackId
     * @return \Ip\Response\Layout | \Ip\Response\Redirect
     * @throws Exception
     */
    public function retrieveTrack($trackId)
    {
        $track = Module::get($trackId);

        if (!$track) {
            throw new Exception('No such Track ' . $trackId);
        }

        $hasPurchased = false;
        $order = null;
        if (ipUser()->loggedIn()) {
            $uid = ipUser()->userId();

            $hasPurchased = TrackOrder::hasPurchased($trackId, $uid);
            $order = TrackOrder::getByTrackAndUser($trackId, $uid);
        }

        // Redirect to the video page if the user has purchased the track, and it has videos
        if ($hasPurchased && !empty($track['courses']) && !empty($track['courses'])) {
            return new \Ip\Response\Redirect(
                ipConfig()->baseUrl() . "online-courses/" . $track['trackId'] . "/v/" . $track['courses'][0]['courseId'] . "/"
            );
        }

        $layout = new \Ip\Response\Layout(
            ipView('view/retrieve.php', [
                'track' => $track,
                'purchasedOn' => !empty($order) && $hasPurchased ?
                    $order['paymentExecuted'] :
                    null,
                'hasPurchased' => $hasPurchased
            ])->render());
        $layout->setLayout('track.php');

        $layout->setTitle("${track['title']} | Grooa");
        $layout->setDescription($track['shortDescription']);

        // Set background image
        $layout->setLayoutVariable('coverImage', ipFileUrl('file/repository/' . $track['largeThumbnail']));

        return $layout;
    }

    /**
     * @controller
     * Page where the specific course video is presented.
     * Cannot be access if not purchased.
     * See Job.php for the filter which handles this
     *
     * @param $trackId
     * @param $courseId
     * @return \Ip\Response\Layout|\Ip\Response\PageNotFound
     */
    public function retrieveCourse($trackId, $courseId)
    {
        $track = Module::get($trackId, $courseId);

        if (!$track || !$track['course']) {
            return new \Ip\Response\PageNotFound("Cannot find Track of course");
        }

        $uri = null;
        if (!empty($track['course']['video'])) {
            $uri = AwsS3::getPresignedUri($track['course']['video']);
        }

        $track['course']['video'] = $uri; // Replace the saved url, with the actual AWS S3 url

        ipAddJsVariable('track', $track);

        $layout = new \Ip\Response\Layout(ipView('view/retrieveCourse.php',
            [
                'track' => $track,
                'course' => $track['course']
            ])->render()
        );
        $layout->setLayout('track.php');

        $layout->setTitle("${track['title']} | Grooa");
        $layout->setDescription(!empty($track['course']['shortDescription']) ?
            $track['course']['shortDescription'] : $track['shortDescription']);

        //$layout->setLayoutVariable('coverImage', ipFileUrl('file/repository/' . $track['course']['largeThumbnail']));
        //$layout->setLayoutVariable('coverTitle', $track['title']);

        return $layout;
    }

    /**
     * @controller
     * Renders the contact sale form, which allows business users to request
     * pricing for a specific course.
     *
     * Business accounts are not allowed to purchase through PayPal,
     * as courses purchased by businesses, is assumed to be shared across multiple employees.
     *
     * Requires query param `course`, which is the trackId of the requested course
     */
    public function contactSales()
    {
        if (!ipUser()->isLoggedIn()) {
            return new Redirect(ipConfig()->baseUrl() . 'home');
        }

        $user = GrooaUser::getByUserId(ipUser()->userId());

        if (!empty($user['businessUser']) && $user['businessUser'] != true) {
            return new Redirect(ipConfig()->baseUrl() . 'home');
        }

        $user['email'] = ipUser()->data()['email'];

        $trackId = ipRequest()->getQuery('course');
        $track = Module::get($trackId);

        if (empty($trackId) || empty($track)) {
            return new PageNotFound("Cannot find the selected course");
        }

        $layout = new \Ip\Response\Layout(ipView('view/contactSales.php',
            [
                'track' => $track,
                'user' => $user,
                'form' => self::createContactSalesForm($user, $track)
            ])->render()
        );
        $layout->setLayout('contact.php');

        return $layout;
    }

    public static function createContactSalesForm($user, $track)
    {
        $form = new \Ip\Form();
        $form->setMethod('post');
        $form->setAction(ipConfig()->baseUrl());
        $form->setEnvironment(\Ip\Form::ENVIRONMENT_PUBLIC);
        $form->addClass('mailgun contact');

        $form->addField(new \Ip\Form\Field\Hidden([
            'name' => 'pa',
            'value' => 'Track.contactSales'
        ]));

        $form->addField(new \Ip\Form\Field\Hidden([
            'name' => 'companyName',
            'value' => $user['companyName']
        ]));

        $form->addField(new \Ip\Form\Field\Hidden([
            'name' => 'title',
            'value' => $track['title']
        ]));

        $form->addField(new \Ip\Form\Field\Text([
            'name' => 'name',
            'label' => 'Company Name',
            'value' => $user['companyName'],
            'attributes' => ['disabled' => 'disabled']
        ]));

        $form->addField(new \Ip\Form\Field\Email([
            'name' => 'email',
            'label' => 'Email',
            'value' => $user['email'],
            'validators' => ['Required']
        ]));

        // Just for
        $form->addField(new \Ip\Form\Field\Text([
            'name' => 'courseTitle',
            'label' => 'Name of Course',
            'value' => $track['title'],
            'attributes' => ['disabled' => 'disabled']
        ]));

        // Used by Track.contactSales to know which course to purchase
        $form->addField(new \Ip\Form\Field\Hidden([
            'name' => 'trackId',
            'value' => $track['trackId']
        ]));


        $form->addField(new \Ip\Form\Field\Textarea([
            'name' => 'message',
            'label' => 'Message',
            'hint' => 'Hi, I am interested in x course! We are around 5 people who will take this course and is interested in a price estimate',
            'attributes' => [
                'placeholder' => 'Hi, I am interested in x course! We are around 5 people who will take this course and is interested in a price estimate'
            ],
            'validators' => ['Required']
        ]));

        $form->addField(new \Ip\Form\Field\Submit([
            'value' => 'Send Request'
        ]));

        return $form;
    }
}
