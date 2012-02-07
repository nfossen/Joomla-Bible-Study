DROP TABLE IF EXISTS `#__jbsbackup_update`;

CREATE TABLE IF NOT EXISTS `#__jbsbackup_update` (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  version VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__jbsbackup_update` (id,version) VALUES
(1,'7.0.7');

CREATE TABLE IF NOT EXISTS `#__jbsbackup_timeset` (
	`timeset` varchar(14) NOT NULL DEFAULT '',
	`backup` varchar(14) DEFAULT NULL,
	PRIMARY KEY (`timeset`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__jbsbackup_timeset`
--

INSERT INTO `#__jbsbackup_timeset` (`timeset`, `backup`) VALUES
	( '1281646339', '1281646339');