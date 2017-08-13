<?php

namespace Plugin\Track;

use Ip\Exception;
use Ip\Response\PageNotFound;
use Ip\Response\Redirect;
use Plugin\GrooaPayment\Response\RestError;
use Plugin\GrooaUser\Model\GrooaUser;
use Plugin\Track\Model\AwsS3;
use Plugin\Track\Model\Track;

use Plugin\GrooaPayment\Model\TrackOrder;

class SiteController
{
    public function listTracks()
    {
        $tracks = Track::findAll();

        $layout = new \Ip\Response\Layout(
            ipView('view/list.php', ['tracks' => $tracks])->render());
        $layout->setLayout('track.php');
        //$layout->setLayoutVariable('coverImage', $track['large_thumbnail']);

        return $layout;
    }

    /**
     * @param $trackId
     * @return \Ip\Response\Layout
     * @throws Exception
     */
    public function retrieveTrack($trackId)
    {
        $track = Track::get($trackId);

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

        $layout = new \Ip\Response\Layout(
            ipView('view/retrieve.php', [
                'track' => $track,
                'purchasedOn' => !empty($order) && $hasPurchased ?
                    $order['paymentExecuted'] :
                    null,
                'hasPurchased' => $hasPurchased
            ])->render());
        $layout->setLayout('track.php');

        // Set background image
        $layout->setLayoutVariable('coverImage', ipFileUrl('file/repository/' . $track['largeThumbnail']));

        return $layout;
    }

    /**
     * @param $trackId
     * @param $courseId
     * @return \Ip\Response\Layout|\Ip\Response\PageNotFound
     */
    public function retrieveCourse($trackId, $courseId)
    {
        $track = Track::get($trackId, $courseId);

        if (!$track || !$track['course']) {
            return new \Ip\Response\PageNotFound("Cannot find Track of course");
        }

        $uri = AwsS3::getPresignedUri(
            'courses/videos/Archer.S08E04.Ladyfingers.720p.WEB.x264-[MULVAcoded].mp4');

        $track['course']['video'] = $uri; // Replace the saved url, with the actual AWS S3 url

        ipAddJsVariable('track', $track);

        $layout = new \Ip\Response\Layout(ipView('view/retrieveCourse.php',
            [
                'track' => $track,
                'course' => $track['course']
            ])->render()
        );
        $layout->setLayout('track.php');

        $layout->setLayoutVariable('coverImage', $track['course']['largeThumbnail']);
        $layout->setLayoutVariable('coverTitle', $track['title']);

        return $layout;
    }

    public function retrieveCourseResources($trackId, $courseId) {
        if (!ipRequest()->isGet()) {
            return new RestError("Method Not Allowed", 405);
        }

//        if (!ipRequest()->isAjax()) {
//            return new RestError("Request must be Ajax", 401);
//        }

        if (!ipUser()->isLoggedIn()) {
            return new RestError("Request requires authenticated user", 403);
        }

        if (!TrackProtector::canAccess(ipUser(), $trackId)) {
            return new RestError("You must purchase this track ($trackId) first", 403);
        }

    }

    public function contactSales () {
        if (!ipUser()->isLoggedIn()) {
            return new Redirect(ipConfig()->baseUrl() . 'home');
        }

        $user = GrooaUser::getByUserId(ipUser()->userId());

        if (!empty($user['businessUser']) && $user['businessUser'] != true) {
            return new Redirect(ipConfig()->baseUrl() . 'home');
        }

        $user['email'] = ipUser()->data()['email'];

        $trackId = ipRequest()->getQuery('course');
        $track = Track::get($trackId);

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

    public static function createContactSalesForm($user, $track) {
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
