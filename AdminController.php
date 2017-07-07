<?php
/**
 * @package   ImpressPages
 */

namespace Plugin\Track;
use Plugin\Track\Models\TrackModel;
use Plugin\Track\Models\CourseModel;


class AdminController
{

    private $fileRoot = 'file/secure/videos';

    /**
     * @ipSubmenu Tracks
     */
    public function index()
    {
        // Docs: https://www.impresspages.org/docs/grid
        $config = [
            'table' => 'track',
            'idField' => 'trackId',
            'fields' => [
                [
                    'field' => 'title',
                    'label' => 'Title',
                    'validators' => ['Required']
                ],
                [
                    'field' => 'price',
                    'label' => 'Price',
                    'type' => 'Currency'
                ],
                [
                    'field' => 'shortDescription',
                    'label' => 'Short Description',
                    'hint' => 'Used on listing',
                    'type' => 'RichText'
                ],
                [
                    'field' => 'longDescription',
                    'label' => 'Long Description',
                    'type' => 'RichText',
                    'validators' => ['Required']
                ],
                [
                    'field' => 'thumbnail',
                    'label' => 'Thumbnail',
                    'hint' => 'Used in smaller tiles',
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
     * @ipSubmenu Courses
     */
    public function courses() {
        // Docs: https://www.impresspages.org/docs/grid
        $config = [
            'table' => 'course',
            'idField' => 'courseId',
            'fields' => [
                [
                    'field' => 'title',
                    'label' => 'Title'
                ],
                [
                    'field' => 'price',
                    'label' => 'Price',
                    'type' => 'Currency'
                ],
                [
                    'field' => 'shortDescription',
                    'label' => 'Short Description',
                    'type' => 'RichText'
                ],
                [
                    'field' => 'longDescription',
                    'label' => 'Long Description',
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
                ],
                [
                    'field' => 'trackId',
                    'label' => 'Track',
                    'type' => 'Select',
                    'values' => TrackModel::findWithIdAndTitle()
                ]
            ],
            'pageSize' => 15,
            'beforeDelete' => function($id) {
                CourseModel::removeVideos($id, $this->fileRoot);
            }
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

        //create form prepopulated with current widget data
        $form = $this->managementForm($widgetData);

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

    protected function managementForm($widgetData = array())
    {
        $form = new \Ip\Form();

        $form->setEnvironment(\Ip\Form::ENVIRONMENT_ADMIN);

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

        return $form;
    }
}
