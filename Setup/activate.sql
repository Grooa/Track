CREATE TABLE IF NOT EXISTS ip_track (
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