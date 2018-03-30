<?php

namespace Plugin\Track;

use Ip\Response;
use Plugin\Mailgun\Model as Mailgun;
use Plugin\GrooaPayment\Response\RestError;
use Plugin\Track\Model\AwsS3;
use Plugin\Track\Model\Module;
use Plugin\Track\Model\Resource;
use Plugin\Track\Model\Video;
use Plugin\Track\Service\AwsService;
use Plugin\Track\Service\CourseService;
use Plugin\Track\Service\ModuleService;
use Plugin\Track\Service\ResourceService;
use Plugin\Track\Service\VideoService;

class PublicController
{
    private $courseService;
    private $moduleService;
    private $videoService;
    private $resourceService;
    private $awsService;

    public function __construct()
    {
        $this->courseService = new CourseService();
        $this->moduleService = new ModuleService();
        $this->videoService = new VideoService();
        $this->resourceService = new ResourceService();
        $this->awsService = new AwsService();
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
            'modules' => array_map(function (Module $m) {
                return $m->serializeToArray();
            }, $course->getModules())
        ]);
    }

    /**
     * Finds the module by the id,
     * and attempts to join relevant data.
     * @param int $id
     * @return Response
     */
    public function findModuleById(int $id): Response
    {
        if (!ipRequest()->isGet()) {
            return new RestError("Method Not Allowed", 405);
        }

        $authenticated = ipUser()->isLoggedIn();
        $hasFullAccess = TrackProtector::canAccess(ipUser(), $id);

        $module = $this->moduleService->findById($id);

        if (empty($module) || !$module->isPublished()) {
            return new RestError("Cannot find module with id: $id", 404);
        }

        $module->setVideos($this->setVideosByAccessRights(
            $module->getVideos(),
            $authenticated,
            $hasFullAccess
        ));

        return new Response\Json($module->serialize());
    }

    /**
     * Modifies the videos to display what the client has access to
     * 
     * @param array $videos List of Videos
     * @param bool $authenticated Whether the client is authenticated
     * @param bool $hasFullAccess Whether the client has actuall access to this module
     * @return array
     */
    private function setVideosByAccessRights(array $videos, $authenticated, $hasFullAccess): array
    {
        if (empty($videos)) {
            return [];
        }

        if (!$authenticated || !$hasFullAccess) {
            // Clear data which shouldn't be shared to non-authenticated users
            $videos = array_map(function (Video $v) {
                $v->setLongDescription(null);
                $v->setUrl(null);
                $v->setResources([]);
                return $v;
            }, $videos);
        } else {
            $videos = array_map(function (Video $v) {
                $presignedUrl = $this->awsService->createPresignedUrl($v->getUrl());

                $v->setUrl($presignedUrl);

                $resources = $v->getResources();

                if (!empty($resources)) {
                    $v->setResources(array_map(function(Resource $r) {
                        $r->setUrl($this->awsService->createPresignedUrl($r->getFilename()));

                        return $r;
                    }, $resources));
                }

                return $v;
            }, $videos);
        }

        return $videos;
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

        $resources = $this->resourceService->findByVideoId($courseId);

        // Create valid presigned urls, and convert to JSON friendly format
        if (!empty($resources)) {
            $resources = array_map(function (Resource $r) {
                $r->setUrl(AwsS3::getPresignedUri($r['filename']));
                return $r->serialize();
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
