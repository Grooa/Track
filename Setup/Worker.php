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
        ipDb()->execute("DROP TABLE $this->trackTable;");
        ipDb()->execute("DROP TABLE $this->courseTable;");
        $this->initTrackTable($this->trackTable);
//        $this->initTrackOrderTable($this->trackOrderTable);
        $this->initCourseTable($this->courseTable);
    }

    public function remove()
    {
//        ipDb()->execute("DROP TABLE $this->trackTable;");
//        ipDb()->execute("DROP TABLE $this->trackOrderTable;");
//        ipDb()->execute("DROP TABLE $this->courseTable;");
    }

    private function initCourseTable($table)
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS $table (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `title` VARCHAR (255) NULL,
          `short_description` VARCHAR (255) NULL,
          `long_description` TEXT NULL,
          `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
          `price` FLOAT NULL,
          
          FOREIGN KEY (`track_id`)
            REFERENCES $this->trackTable (`id`),
          
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
        ";
    }

    private function initTrackTable($table)
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS $table (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `title` VARCHAR (255) NULL,
          `short_description` VARCHAR (255) NULL,
          `long_description` TEXT NULL,
          `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
          `price` FLOAT NULL,
          
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
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