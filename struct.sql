CREATE TABLE IF NOT EXISTS `minute_replicate` (
  `sequenceNumber` int(10) unsigned NOT NULL,
  `timestamp` datetime DEFAULT NULL,
  PRIMARY KEY `pk_seq` (sequenceNumber),
  KEY `idx_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `hour_replicate` (
  `sequenceNumber` int(10) unsigned NOT NULL,
  `timestamp` datetime DEFAULT NULL,
  PRIMARY KEY `pk_seq` (sequenceNumber),
  KEY `idx_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `day_replicate` (
  `sequenceNumber` int(10) unsigned NOT NULL,
  `timestamp` datetime DEFAULT NULL,
  PRIMARY KEY `pk_seq` (sequenceNumber),
  KEY `idx_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
