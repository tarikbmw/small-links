SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `link`;
CREATE TABLE `link` (
  `linkID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(10) unsigned NOT NULL,
  `visited` int(10) unsigned DEFAULT 0,
  `urlID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`linkID`),
  UNIQUE KEY `linkID_UNIQUE` (`linkID`),
  KEY `fk_link_user_idx` (`userID`),
  KEY `fk_link_url_idx` (`urlID`),
  CONSTRAINT `fk_link_url` FOREIGN KEY (`urlID`) REFERENCES `url` (`urlID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_link_user` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `url`;
CREATE TABLE `url` (
  `urlID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `origin` varchar(45) NOT NULL,
  PRIMARY KEY (`urlID`),
  UNIQUE KEY `urlID_UNIQUE` (`urlID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mail` varchar(45) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `userID_UNIQUE` (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS=1;