<?php

namespace Plugin\Track;

use Plugin\Mailgun\Model as Mailgun;
use Plugin\GrooaPayment\Response\RestError;
use Plugin\Track\Model\AwsS3;
use Plugin\Track\Model\Module;
use Plugin\Track\Model\ModuleVideo;
use Plugin\Track\Model\TrackResource;
use Plugin\Track\Service\CourseService;
use Plugin\Track\Service\ModuleService;
use Plugin\Track\Service\VideoService;

class PublicController
{
    private $courseService;
    private $moduleService;
    private $videoService;

    public function __construct()
    {
        $this->courseService = new CourseService();
        $this->moduleService = new ModuleService();
        $this->videoService = new VideoService();
    }

    public function findCourseByLabel($label)
    {
        if (!isset($this->courseService)) {
            $this->courseService = new CourseService();
        }

        if (!ipRequest()->isGet()) {
           return new RestError("Method not allowed", 405);
        }

        $course = $this->courseService->findByLabel($label);

        if (empty($course)) {
            return new RestError("Cannot find course with label: $label", 404);
        }

        $course->setModules(
            $this->moduleService->findAllPublishedByCourseId($course->getId())
        );

        return new \Ip\Response\Json([
            'id' => $course->getId(),
            'label' => $course->getLabel(),
            'name' => $course->getName(),
            'description' => $course->getDescription(),
            'introduction' => $course->getIntroduction(),
            'createdOn' => $course->getCreatedOn(),
            'cover' => !empty($course->getCover())
                ? ipFileUrl('file/repository/' . $course->getCover())
                : null,
            'modules' => array_map(function(Module $m) {
                return $m->serializeToArray();
            }, $course->getModules())
        ]);
    }

    /**
     *
     * @param int $moduleId
     */
    public function findModuleById(int $moduleId) {

    }

    public function contactSales()
    {
        ipRequest()->mustBePost();

        $data = ipRequest()->getPost();

        //                                            User  &  Track
        $form = SiteController::createContactSalesForm($data, $data);

        $errors = $form->validate($data);
        $res = ['status' => 'ok'];

        if ($errors) {
            $res['status'] = 'error';
            $res['errors'] = $errors;
        } else {
            $res['replaceHtml'] = '<section>
                <h2>Request posted</h2>
                <p class="centered">Thank you for contacting, we will try to respond as quickly as possible</p>
            </section>';
        }

        $mg = new Mailgun(
            'postmaster@grooa.com',
            '[Grooa] Request for Online Course');

        $timestamp = new \DateTime();
        $timestamp = $timestamp->format("Y-m-d H:i:s");

        $mg->setHtml("
        <header>
            <h1>Someone wants to invest in the Online Course ${data['title']}</h1>
        </header>
        <section>
            <strong>Company: </strong> <em>${data['companyName']}</em> <br>
            <strong>Contact email</strong> <em>${data['email']}</em> <br>
            <strong>Message</strong>
            <p>${data['message']}</p>    
        </section>
        <footer style=\"display: flex; justify-content: space-between\">
            <div>
                <strong>Timestamp</strong>
                <em>$timestamp</em>
            </div>
        </footer>
        ");

        $mg->setPlain("
        Someone wants to invest in the Online Course ${data['title']}
        
        Company: ${data['companyName']}
        Contact email: ${data['email']}
        Message:
        
        ${data['message']}
        
        Timestamp: $timestamp
        ");

        $mg->addTag('contact')->addTag('online-course');

        $mg->setReplyTo($data['email']);

        try {
            $mg->send();
        } catch (\Exception $e) {
            ipLog()->error('Cannot Send Course Request Email', $e);
            $res['replaceHtml'] = '
            <section>
                <h2>Could not send course request</h2>
                <p>Something went wrong when sending your course request. 
                Please notify us on <em>info@grooa.com</em> if this problem persists</p>
            </section>
            ';
        }

        return new \Ip\Response\Json($res);
    }


    /**
     * @controller
     * @rest
     * Will fetch all the courses-video resources in JSON format
     */
    public function retrieveCourseResources($trackId, $courseId)
    {
        if (!ipRequest()->isGet()) {
            return new RestError("Method Not Allowed", 405);
        }

        if (!ipUser()->isLoggedIn()) {
            return new RestError("Request requires authenticated user", 403);
        }

        if (!TrackProtector::canAccess(ipUser(), $trackId)) {
            return new RestError("You must purchase this module ($trackId) first", 403);
        }

        $resources = TrackResource::getAll($trackId, $courseId);

        // Create valid presigned urls
        if (!empty($resources)) {
            $resources = array_map(function ($r) {
                $r['url'] = AwsS3::getPresignedUri($r['filename']);
                return $r;
            }, $resources);
        }

        return new \Ip\Response\Json($resources);
    }

    /**
     * Retrieves a list of courses for the selected track.
     *
     * format: [
     *      [<courseId:int>, <title:string>]
     * ]
     */
    public function listCourses()
    {
        if (empty(ipAdminId()) || ipAdminId() < 0) {
            return new RestError('Not Authorized', 403);
        }

        $moduleId = ipRequest()->getQuery('trackId');

        if (empty($moduleId) || $moduleId < 0) {
            return new RestError('Missing query-param `trackId`', 400);
        }

        $courses = $this->videoService->findByModuleIdWithIdAndTitle($moduleId);

        return new \Ip\Response\Json($courses);
    }
}
