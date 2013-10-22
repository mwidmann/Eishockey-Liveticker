-- 
-- Creates the table structure for the liveticker.
--
--
-- Table structure for table `liveticker_entry`
--

CREATE TABLE liveticker_entry (
  entry_id int(10) NOT NULL auto_increment,
  match_id int(10) NOT NULL default '0',
  entry_type int(4) NOT NULL default '0',
  team int(4) NOT NULL default '2',
  playtime varchar(5) NOT NULL default '00:00',
  player1 varchar(50) default NULL,
  player2 varchar(50) default NULL,
  player3 varchar(50) default NULL,
  powerplay int(4) NOT NULL default '0',
  penalty_time int(4) default NULL,
  penalty_reason varchar(50) default NULL,
  penalty_extension int(4) default '0',
  info text,
  entry_time datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  PRIMARY KEY  (entry_id)
) ENGINE=MyISAM;

--
-- Table structure for table `liveticker_match`
--
CREATE TABLE liveticker_match (
  match_id int(10) NOT NULL auto_increment,
  home varchar(255) NOT NULL default '',
  home_name varchar(50) NOT NULL default '',
  away varchar(255) NOT NULL default '',
  away_name varchar(50) NOT NULL default '',
  matchdate datetime NOT NULL default '0000-00-00 00:00:00',
  info text,
  running tinyint NOT NULL DEFAULT '1',
  overtime_length tinyint unsigned NOT NULL DEFAULT '5', 
  head1 varchar(50), 
  head2 varchar(50), 
  linesman1 varchar(50),
  linesman2 varchar(50),
  spectators smallint unsigned, 
  PRIMARY KEY  (match_id)
) ENGINE=MyISAM;