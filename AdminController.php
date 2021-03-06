<?php
/**
 * @package   ImpressPages
 */

namespace Plugin\Track;

use Ip\Form;
use Plugin\Track\Model\Course;
use Plugin\Track\Model\ModuleVideo;
use Plugin\Track\Model\Module;
use Plugin\Track\Model\TrackResource;
use Plugin\Track\Model\Video;
use Plugin\Track\Repository\CourseRepository;
use Plugin\Track\Repository\VideoRepository;


class AdminController
{

    private $fileRoot = 'file/secure/videos';

    /**
     * @ipSubmenu Grooa Courses
     */
    public function index()
    {
        $config = [
            'title' => '',
            'table' => 'grooa_course',
            'idField' => 'id',
            'fields' => [
                [
                    'field' => 'id',
                    'label' => 'ID',
                    'allowCreate' => false,
                    'allowUpdate' => false
                ],
                [
                    'field' => 'label',
                    'label' => 'Label',
                    'note' => 'No spaces, lowercase only, and only characters from 0-9 and a-z'
                ],
                [
                    'field' => 'name',
                    'label' => 'Name'
                ],
                [
                    'field' => 'cover',
                    'label' => 'Cover image',
                    'type' => 'RepositoryFile',
                    'preview' => false,
                    'default' => null
                ],
                [
                    'field' => 'description',
                    'label' => 'Short description',
                    'type' => 'Textarea',
                    'default' => null,
                    'note' => '1 to 2 sentences only'
                ],
                [
                    'field' => 'introduction',
                    'label' => 'Introduction',
                    'type' => 'Textarea',
                    'default' => null,
                    'preview' => false,
                    'attributes' => [
                        'rows' => '10'
                    ],
                    'note' => 'Longer descriptive text. Supports Markdown!'
                ],
                [
                    'field' => 'createdOn',
                    'label' => 'Created On',
                    'allowCreate' => false
                ]
            ],
            'pageSize' => 15
        ];

        return ipGridController($config);
    }

    /**
     * @ipSubmenu Modules
     */
    public function masterClass()
    {
        // Docs: https://www.impresspages.org/docs/grid
        $config = [
            'title' => 'Tracks',
            'table' => Module::TABLE,
            'idField' => 'trackId',
            'actions' => [
                [
                    'type' => 'Html',
                    'html' => '<a class="button" style="margin: auto 1em;" href="' . ipConfig()->baseUrl() . 'online-courses">To Master Class</a>'
                ],
                [
                    'type' => 'Html',
                    'html' => '<a class="button" style="margin: auto 1em;" href="' . ipConfig()->baseUrl() . '?aa=GrooaPayment">To Payment Manager</a>'
                ]
            ],
            'fields' => [
                [
                    'field' => 'title',
                    'label' => 'Title',
                    'validators' => ['Required']
                ],
                [
                    'field' => 'grooaCourseId',
                    'label' => 'Grooa Course',
                    'validators' => ['Required'],
                    'type' => 'Select',
                    'values' => Module::getGrooaCourseWithIdAndName()
                ],
                [
                    'field' => 'state',
                    'label' => 'State',
                    'validators' => ['Required'],
                    'type' => 'Select',
                    'values' => ['draft', 'published', 'withdrawn']
                ],
                [
                    'field' => 'type',
                    'label' => 'Type',
                    'validators' => ['Required'],
                    'type' => 'Select',
                    'values' => ['introduction', 'webinar', 'module'],
                    'default' => 'module',
                    'note' => 'Denotes this element can be categorized as. Will display on the preview cards'
                ],
                [
                    'field' => 'num',
                    'label' => 'Module number',
                    'type' => 'Integer',
                    'note' => 'Write in what number this module has. Ex. Module 10. If you don\'t have any, keep this empty'
                ],
                [
                    'field' => 'price',
                    'label' => 'Price',
                    'type' => 'Text',
                    'value' => '0.0',
                    'default' => '0.0'
                ],
                [
                    'field' => 'isFree',
                    'label' => 'Is Free',
                    'type' => 'Checkbox',
                    'default' => false
                ],
                [
                    'field' => 'order',
                    'label' => 'Order',
                    'type' => 'Integer',
                    'note' => 'Lowest is first, highest is last',
                    'default' => 0,
                    'values' => 0
                ],
                [
                    'field' => 'shortDescription',
                    'label' => 'Short Description',
                    'hint' => 'Used on listing',
                    'type' => 'Textarea',
                    'preview' => false
                ],
                [
                    'field' => 'longDescription',
                    'label' => 'Long Description',
                    'type' => 'Textarea',
                    'preview' => false,
                    'validators' => ['Required']
                ],
                [
                    'field' => 'thumbnail',
                    'label' => 'Thumbnail',
                    'hint' => 'Used in smaller tiles',
                    'type' => 'RepositoryFile',
                    'preview' => false
                ],
                [
                    'field' => 'largeThumbnail',
                    'label' => 'Large Thumbnail',
                    'type' => 'RepositoryFile',
                    'preview' => false
                ]
            ],
            'pageSize' => 15
        ];

        return ipGridController($config);
    }

    /**
     * @ipSubmenu Videos
     */
    public function courses()
    {
        // Docs: https://www.impresspages.org/docs/grid
        $config = [
            'title' => 'Course Resources',
            'table' => VideoRepository::TABLE_NAME,
            'idField' => 'courseId',
            'fields' => [
                [
                    'field' => 'courseId',
                    'label' => 'ID',
                    'allowCreate' => false,
                    'allowUpdate' => false
                ],
                [
                    'field' => 'title',
                    'label' => 'Title'
                ],
                [
                    'field' => 'price',
                    'label' => 'Price',
                    'type' => 'Text',
                    'value' => '0.0'
                ],
                [
                    'field' => 'trackId',
                    'label' => 'Track',
                    'type' => 'Select',
                    'values' => Module::getWithIdAndTitle()
                ],
                [
                    'field' => 'shortDescription',
                    'label' => 'Short Description',
                    'note' => 'Used on the preview card. Should be short',
                    'type' => 'RichText'
                ],
                [
                    'field' => 'longDescription',
                    'label' => 'Long Description',
                    'hint' => 'Used on the Video page. Should be around 255-400 characters',
                    'type' => 'RichText'
                ],
                [
                    'field' => 'video',
                    'label' => 'Video URL',
                    'hint' => 'Use "Copy path" on Amazon S3 console and paste it here. If you want to support multiple resolutions, suffix the filename with _720px, _1080px, etc.',
                    'required' => true,
                    'type' => 'Text'
                ],
                [
                    'field' => 'thumbnail',
                    'label' => 'Thumbnail',
                    'type' => 'RepositoryFile',
                    'preview' => true
                ],
                [
                    'field' => 'largeThumbnail',
                    'label' => 'Large Thumbnail',
                    'type' => 'RepositoryFile',
                    'preview' => true
                ]
            ],
            'pageSize' => 15
        ];

        return ipGridController($config);
    }

    /**
     * @ipSubmenu Video Resources
     */
    public function courseResources()
    {
        $config = [
            'title' => 'Video Resources',
            'table' => TrackResource::TABLE,
            'idField' => 'id',
            'fields' => [
                [
                    'field' => 'id',
                    'label' => 'ID'
                ],
                [
                    'field' => 'label',
                    'label' => 'Label',
                    'attributes' => [
                        'required' => 'required'
                    ],
                ],
                [
                    'field' => 'description',
                    'label' => 'Description',
                    'type' => 'RichText'
                ],
                [
                    'field' => 'filename',
                    'label' => 'Filename',
                    'hint' => 'Use "Copy path" on Amazon S3 console and paste it here. If you want to support multiple resolutions, suffix the filename with _720px, _1080px, etc.',
                    'required' => true,
                    'type' => 'Text',
                    'attributes' => [
                        'required' => 'required'
                    ],
                ],
                [
                    'field' => 'trackId',
                    'label' => 'Track',
                    'type' => 'Select',
                    'attributes' => [
                        'required' => 'required'
                    ],
                    'values' => Module::getWithIdAndTitle()
                ],
                [
                    'field' => 'courseId',
                    'label' => 'Video Id',
                    'type' => 'Text',
                    'value' => '0',
                    'attributes' => [
                        'required' => 'required'
                    ],
                    'default' => 0
                ]
            ],
            'pageSize' => 15
        ];

        return ipGridController($config);
    }

    /**
     * WidgetSkeleton.js ask to provide widget management popup HTML. This controller does this.
     * @return \Ip\Response\Json
     * @throws \Ip\Exception\View
     */
    public function widgetPopupHtml()
    {
        $widgetId = ipRequest()->getQuery('widgetId');
        $widgetRecord = \Ip\Internal\Content\Model::getWidgetRecord($widgetId);
        $widgetData = $widgetRecord['data'];

        switch ($widgetRecord['name']) {
            case 'CoursePreview':
                $form = $this->coursePreviewManagementForm($widgetData);
                break;
            default:
                $form = $this->managementForm($widgetData);
        }

        //Render form and popup HTML
        $viewData = array(
            'form' => $form
        );
        $popupHtml = ipView('view/editPopup.php', $viewData)->render();
        $data = array(
            'popup' => $popupHtml
        );
        //Return rendered widget management popup HTML in JSON format
        return new \Ip\Response\Json($data);
    }

    /**
     * Check widget's posted data and return data to be stored or errors to be displayed
     */
    public function checkForm()
    {
        $data = ipRequest()->getPost();
        $form = $this->managementForm();
        $data = $form->filterValues($data); //filter post data to remove any non form specific items
        $errors = $form->validate($data); //http://www.impresspages.org/docs/form-validation-in-php-3
        if ($errors) {
            //error
            $data = array(
                'status' => 'error',
                'errors' => $errors
            );
        } else {
            //success
            unset($data['aa']);
            unset($data['securityToken']);
            unset($data['antispam']);
            $data = array(
                'status' => 'ok',
                'data' => $data

            );
        }
        return new \Ip\Response\Json($data);
    }

    public function checkCoursePreviewForm()
    {
        $data = ipRequest()->getPost();
        $form = $this->coursePreviewManagementForm();
        $data = $form->filterValues($data); //filter post data to remove any non form specific items
        $errors = $form->validate($data); //http://www.impresspages.org/docs/form-validation-in-php-3
        if ($errors) {
            //error
            $data = array(
                'status' => 'error',
                'errors' => $errors
            );
        } else {
            //success
            unset($data['aa']);
            unset($data['securityToken']);
            unset($data['antispam']);
            $data = array(
                'status' => 'ok',
                'data' => $data

            );
        }

        return new \Ip\Response\Json($data);
    }

    protected function coursePreviewManagementForm(array $widgetData = array()): Form
    {
        $courseRepository = new CourseRepository();

        $form = new Form();
        $form->setEnvironment(Form::ENVIRONMENT_ADMIN);

        $form->addField(new \Ip\Form\Field\Hidden([
            'name' => 'aa',
            'value' => 'Track.checkCoursePreviewForm'
        ]));

        $form->addField(new Form\Field\Select([
            'name' => 'courseId',
            'label' => 'Course',
            'values' => array_map(function ($course) {
                return $this->transformToIdAndLabel($course);
            }, $courseRepository->findAll()),
            'value' => !empty($widgetData['courseId']) ? $widgetData['courseId'] : null
        ]));

        return $form;
    }

    protected function managementForm($widgetData = array())
    {
        $form = new Form();

        $form->setEnvironment(Form::ENVIRONMENT_ADMIN);

        //setting hidden input field so that this form would be submitted to 'errorCheck' method of this controller. (http://www.impresspages.org/docs/controller)
        $field = new \Ip\Form\Field\Hidden(
            array(
                'name' => 'aa',
                'value' => 'Track.checkForm'
            )
        );

        //ADD YOUR OWN FIELDS

        // Register fields to form
        $form->addField($field); // Keep at top
        $form->addField(new \Ip\Form\Field\Select([
            'name' => 'trackId',
            'label' => 'Course',
            'values' => Module::getWithIdAndTitle(),
            'value' => !empty($widgetData['trackId']) ? $widgetData['trackId'] : null
        ]));

        return $form;
    }

    private function transformToIdAndLabel(Course $course): array
    {
        return [$course->getId(), $course->getName()];
    }
}
