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

    private $trackTable;
    private $trackOrderTable;
    private $courseTable;

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

        $this->trackTable = ipTable(Track::TABLE);
        $this->trackOrderTable = ipTable(TrackOrder::TABLE);
        $this->courseTable = ipTable(Course::TABLE);

        $this->initTrackTable($this->trackTable);
        $this->initTrackOrderTable($this->trackOrderTable);
        $this->initCourseTable($this->courseTable);
        $this->initCourseResourcesTable(ipTable(TrackResource::TABLE));
    }

    public function remove()
    {
//        ipDb()->execute("DROP TABLE $this->trackTable;");
//        ipDb()->execute("DROP TABLE $this->trackOrderTable;");
//        ipDb()->execute("DROP TABLE $this->courseTable;");
    }

    private function initCourseResourcesTable($table)
    {
        $courseTable = ipTable(Course::TABLE);
        $trackTable = ipTable(Track::TABLE);

        $sql = "
        CREATE TABLE IF NOT EXISTS $table (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `label` VARCHAR(255),
          `description` VARCHAR(255),
          `filename` VARCHAR(255) NOT NULL,
          `courseId` INT(11) NOT NULL,
          `trackId` INT(11) NOT NULL,
          
          FOREIGN KEY (`courseId`)
            REFERENCES $courseTable (`courseId`),  
            
          FOREIGN KEY (`trackId`)
            REFERENCES $trackTable (`trackId`),
            
          PRIMARY KEY (`id`)
          
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ";
        ipDb()->execute($sql);
    }

    private function initCourseTable($table)
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS $table (
          `courseId` int(11) NOT NULL AUTO_INCREMENT,
          `title` VARCHAR (255) NOT NULL,
          `shortDescription` VARCHAR (255) NULL,
          `longDescription` TEXT NULL,
          `createdOn a` DATETIME DEFAULT CURRENT_TIMESTAMP,
          `thumbnail` VARCHAR(255) NULL,
          `largeThumbnail` VARCHAR (255) NULL,
          `price` FLOAT NULL,
          `video` VARCHAR (255) NULL,
          `trackId` INT(11) NOT NULL,
          
          FOREIGN KEY (`trackId`)
            REFERENCES $this->trackTable (`trackId`),
          
          PRIMARY KEY (`courseId`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
        ";
        ipDb()->execute($sql);
    }

    private function initTrackTable($table)
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS $table (
          `trackId` int(11) NOT NULL AUTO_INCREMENT,
          `title` VARCHAR (255) NULL,
          `shortDescription` VARCHAR (255) NULL,
          `longDescription` TEXT NULL,
          `createdOn` DATETIME DEFAULT CURRENT_TIMESTAMP,
          `thumbnail` VARCHAR(255) NULL,
          `largeThumbnail` VARCHAR(255) NULL,
          `price` FLOAT NULL,
          
          PRIMARY KEY (`trackId`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
        ";
        ipDb()->execute($sql);
    }

    private function initTrackOrderTable($table)
    {
        $userTable = ipTable('user');

        $sql = "
         CREATE TABLE IF NOT EXISTS $this->trackOrderTable (
          `orderId` INT(11) NOT NULL AUTO_INCREMENT,
          `type` VARCHAR(128),
          `createdOn` DATETIME DEFAULT CURRENT_TIMESTAMP,
    	  `userId` INT(11) NOT NULL,
		  `trackId` INT(11) NOT NULL,
		  `payerId` VARCHAR (255),
		  `paymentId` VARCHAR(255),
		  `saleId` VARCHAR (255),
		  `state` VARCHAR(128),
		  `completed` DATETIME,
		  `paymentExecuted` DATETIME,
          
          FOREIGN KEY (`userId`)
            REFERENCES $userTable (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
            
            
          FOREIGN KEY (`trackId`)
            REFERENCES $this->trackTable (`trackId`),
          
          PRIMARY KEY (`orderId`)
        
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
        ";

        ipDb()->execute($sql);
    }
}