CREATE TABLE IF NOT EXISTS ip_grooa_course (
  `id` INT (11) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `createdOn` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `description` TEXT DEFAULT NULL,
  `cover` VARCHAR(255) DEFAULT NULL,

  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ip_track (
  `trackId` int(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR (255) NULL,
  `shortDescription` VARCHAR (255) NULL,
  `longDescription` TEXT NULL,
  `createdOn` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `thumbnail` VARCHAR(255) NULL,
  `largeThumbnail` VARCHAR(255) NULL,
  `price` FLOAT NULL,
  `isFree` BOOL DEFAULT FALSE,
  `state` ENUM('draft', 'published', 'withdrawn') DEFAULT 'draft',
  `order` INT(3) DEFAULT 0,

  `type` ENUM('introduction', 'webinar', 'module') NOT NULL DEFAULT 'module',
  `num` INT(5) DEFAULT NULL,

  `grooaCourseId` INT(11) NOT NULL,

  FOREIGN KEY (`grooaCourseId`)
    REFERENCES `ip_grooa_course` (`id`),

  PRIMARY KEY (`trackId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ip_course (
  `courseId` int(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR (255) NOT NULL,
  `shortDescription` VARCHAR (255) NULL,
  `longDescription` TEXT NULL,
  `createdOn` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `thumbnail` VARCHAR(255) NULL,
  `largeThumbnail` VARCHAR (255) NULL,
  `price` FLOAT NULL,
  `video` VARCHAR (255) NULL,
  `trackId` INT(11) NOT NULL,

  FOREIGN KEY (`trackId`)
    REFERENCES ip_track (`trackId`),

  PRIMARY KEY (`courseId`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ip_course_resource (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(255),
  `description` VARCHAR(255),
  `filename` VARCHAR(255) NOT NULL,
  `courseId` INT(11) NOT NULL,
  `trackId` INT(11) NOT NULL,

  FOREIGN KEY (`courseId`)
  REFERENCES ip_course (`courseId`),

  FOREIGN KEY (`trackId`)
  REFERENCES ip_track (`trackId`),

  PRIMARY KEY (`id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8;