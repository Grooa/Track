<?php

namespace Plugin\Track;

use Plugin\Mailgun\Model as Mailgun;
use Plugin\GrooaPayment\Response\RestError;
use Plugin\Track\Model\Course;

class PublicController
{

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

        $trackId = ipRequest()->getQuery('trackId');

        if (empty($trackId) || $trackId < 0) {
            return new RestError('Missing query-param `trackId`', 400);
        }

        $courses = Course::getWithIdAndTitle($trackId);

        return new \Ip\Response\Json($courses);
    }
}