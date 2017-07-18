<?php

namespace Plugin\Track;

use Ip\Exception;
use \Ip\Internal\Plugins\Service as PluginService;
use \Plugin\GrooaPayment\Model\TrackOrder;
use \Plugin\Track\Model\Course;
use \Plugin\Track\Model\Track;
use \Plugin\Track\Model\TrackResource;

class Worker
{

    public function activate()
    {
        $plugins = PluginService::getActivePluginNames();

        if (!in_array('User', $plugins)) {
            throw new Exception("The Track plugin requires ImpressPages's User plugin. 
            Install and activate this first");
        }

        if (!in_array('PageAccessControl', $plugins)) {
            throw new Exception("The Track plugin requires Grooas PageAccessControl plugin. 
            Install and activate this first");
        }

        if (!in_array('Composer', $plugins)) {
            throw new Exception("The Track plugin requires the Composer plugin. 
            Install and activate this first");
        }

    }

    public function remove()
    {
//        ipDb()->execute("DROP TABLE $this->trackTable;");
//        ipDb()->execute("DROP TABLE $this->trackOrderTable;");
//        ipDb()->execute("DROP TABLE $this->courseTable;");
    }

}