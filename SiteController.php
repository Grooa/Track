<?php

namespace Plugin\Track;

use Ip\Exception;
use Ip\Page;
use Ip\Response;
use Ip\Response\Layout;
use Ip\Response\PageNotFound;
use Ip\Response\Redirect;
use Plugin\GrooaUser\Model\GrooaUser;
use Plugin\Track\Model\AwsS3;
use Plugin\Track\Model\Module;

use Plugin\GrooaPayment\Model\TrackOrder;
use Plugin\Track\Model\Video;
use Plugin\Track\Service\CourseService;
use Plugin\Track\Service\ModuleService;

class SiteController
{
    private $courseService;
    private $moduleService;

    function __construct()
    {
        $this->courseService = new CourseService();
        $this->moduleService = new ModuleService();
    }

    /**
     *
     * @param string $courseLabel
     * @return Layout
     * @throws \Ip\Exception
     */
    public function viewCoursePage(String $courseLabel)
    {
        if (!isset($this->courseService)) {
            $this->courseService = new CourseService();
        }

        $course = $this->courseService->findByLabel($courseLabel);

        if (is_null($course)) {
            return new PageNotFound("We cannot find any course with label: $courseLabel");
        }

        $layout = new Layout(
            ipView('view/viewCoursePage.php', ['course' => $course])->render()
        );

        $layout->setLayout('onlineCourse.php');
        $layout->setTitle($course->getName() . ' | Grooa');
        $layout->setDescription('Checkout our online courses on The CLEAR Mindset and The Mindful leadership gym');
        $layout->setLayoutVariable('ogImage', $course->getCover() ?? null);

        return $layout;
    }

    public function listTracks()
    {
        $tracks = Module::findAll();

        $layout = new Layout(
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
     * @return Layout | \Ip\Response\Redirect
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

        $track['courseRootUri'] = 'c/' . $track['courseRootUri'];

        $layout = new Layout(
            ipView('view/viewModule.php', [
                'track' => $track,
                'hasPurchased' => $hasPurchased
            ])->render());
        $layout->setLayout('onlineCourse.php');

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
     * @return Layout
     * @throws \Ip\Exception
     */
    public function retrieveCourse(int $trackId, int $courseId): Layout
    {
        $module = $this->moduleService->findById($trackId);

        if (empty($module)) {
            return new PageNotFound("Cannot find any module with id: $trackId");
        }

        $videos = array_filter($module->getVideos(), function (Video $v) use ($courseId) {
            return $v->getId() === $courseId;
        });

        if (empty($videos)) {
            return new PageNotFound(
                "Cannot find any video with id: $courseId, corresponding to module: " . $module->getTitle()
            );
        }

        /**
         * @var \Plugin\Track\Model\Video
         */
        $currentVideo = $videos[0]; // After filter, should the first video be the correct item

        $course = $this->courseService->findById($module->getCourseId());

        $layout = new Layout(ipView('view/videoPage.php', [
            'breadcrumbs' => [
                [
                    'uri' => ('c/' . $course->getLabel()),
                    'label' => $course->getName()
                ],
                [
                    'uri' => 'online-courses/' . $module->getId() . '/v/' . $currentVideo->getId() . '/',
                    'label' => $module->getTitle(),
                    'active' => true
                ]
            ]
        ])->render());
        $layout->setLayout('onlineCourse.php');

        $layout->setTitle($module->getTitle() . " | Grooa");
        $layout->setDescription(
            !empty($currentVideo->getShortDescription())
                ? $currentVideo->getShortDescription()
                : $module->getShortDescription()
        );

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

        $layout = new Layout(ipView('view/contactSales.php',
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
