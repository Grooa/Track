<?php

namespace Plugin\Track\Setup;

use Ip\Exception;
use \Ip\Internal\Plugins\Service as PluginService;

class Worker
{

    private $trackTable;
    private $trackOrderTable;
    private $courseTable;

    public function __construct()
    {
        $this->trackTable = ipTable('track');
        $this->trackOrderTable = ipTable('track_order');
        $this->courseTable = ipTable('course');
    }

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

        $this->initTrackTable($this->trackTable);
        $this->initTrackOrderTable($this->trackOrderTable);
        $this->initCourseTable($this->courseTable);
    }

    public function remove()
    {
        ipDb()->execute("DROP TABLE $this->trackTable;");
//        ipDb()->execute("DROP TABLE $this->trackOrderTable;");
        ipDb()->execute("DROP TABLE $this->courseTable;");
    }


    private function initCourseTable($table)
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS $table (
          `course_id` int(11) NOT NULL AUTO_INCREMENT,
          `title` VARCHAR (255) NOT NULL,
          `short_description` VARCHAR (255) NULL,
          `long_description` TEXT NULL,
          `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
          `thumbnail` VARCHAR(255) NULL,
          `large_thumbnail` VARCHAR (255) NULL,
          `price` FLOAT NULL,
          `video` VARCHAR (255) NULL,
          `track_id` INT(11) NOT NULL,
          
          FOREIGN KEY (`track_id`)
            REFERENCES $this->trackTable (`track_id`),
          
          PRIMARY KEY (`course_id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
        ";
    }

    private function initTrackTable($table)
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS $table (
          `track_id` int(11) NOT NULL AUTO_INCREMENT,
          `title` VARCHAR (255) NULL,
          `short_description` VARCHAR (255) NULL,
          `long_description` TEXT NULL,
          `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
          `thumbnail` VARCHAR(255) NULL,
          `large_thumbnail` VARCHAR(255) NULL,
          `price` FLOAT NULL,
          
          PRIMARY KEY (`track_id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
        ";
        ipDb()->execute($sql);
    }

    private function initTrackOrderTable($table)
    {
        $userTable = ipTable('user');

        $sql = "
         CREATE TABLE IF NOT EXISTS $this->trackOrderTable (
          `order_id` INT(11) NOT NULL AUTO_INCREMENT,
          `type` VARCHAR(128),
          `created_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
    	  `userId` INT(11) NOT NULL,
		  `track_id` INT(11) NOT NULL,
		  `payerId` VARCHAR (255),
		  `paymentId` VARCHAR(255),
		  `saleId` VARCHAR (255),
		  `state` VARCHAR(128),
		  `completed` DATETIME
          
          FOREIGN KEY (`userId`)
            REFERENCES $userTable (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
            
            
          FOREIGN KEY (`track_id`)
            REFERENCES $this->trackTable (`track_id`),
            
          UNIQUE (`track_id`),
          
          PRIMARY KEY (`order_id`)
        
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
        ";

        ipDb()->execute($sql);
    }
}