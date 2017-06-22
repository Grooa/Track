<?php

namespace Plugin\Track\Setup;

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
        $this->initTrackTable($this->trackTable);
//        $this->initTrackOrderTable($this->trackOrderTable);
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
        CREATE TABLE IF NOT EXISTS $table (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `payment` VARCHAR(128),
          `ordered_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
          
          FOREIGN KEY (`userId`)
            REFERENCES $userTable (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
            
            
          FOREIGN KEY (`track_id`)
            REFERENCES $this->trackTable (`id`),
            
            
          UNIQUE (`track_id`),
          
          PRIMARY KEY (`id`)
        
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
        ";

        ipDb()->execute($sql);
    }
}