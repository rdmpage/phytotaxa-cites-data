CREATE TABLE `cites` (
  `id` int(11) NOT NULL,
  `guid` varchar(255) NOT NULL,
  `guid-date` varchar(32) DEFAULT NULL,
  `author` text,
  `title` text,
  `container-title` text,
  `type` varchar(32) DEFAULT NULL,
  `volume` varchar(255) DEFAULT NULL,
  `issue` varchar(255) DEFAULT NULL,
  `page` varchar(255) DEFAULT NULL,
  `issued` varchar(16) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `publisher-place` varchar(255) DEFAULT NULL,
  `editor` varchar(255) DEFAULT NULL,
  `URL` varchar(255) DEFAULT NULL,
  `DOI` varchar(255) DEFAULT NULL,
  `PMID` varchar(255) DEFAULT NULL,
  `PMCID` varchar(255) DEFAULT NULL,
  `isbn` varchar(255) DEFAULT NULL,
  `note` text,
  `unstructured` text,
  PRIMARY KEY (`id`),
  KEY `guid` (`guid`),
  KEY `guid-date` (`guid-date`),
  KEY `author` (`author`(255)),
  KEY `title` (`title`(255)),
  KEY `container-title` (`container-title`(255)),
  KEY `type` (`type`),
  KEY `volume` (`volume`),
  KEY `issue` (`issue`),
  KEY `page` (`page`),
  KEY `issued` (`issued`),
  KEY `DOI` (`DOI`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;