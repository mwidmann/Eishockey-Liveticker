-- Adds a couple of new fields to the match
--
-- overtime_length defines the potentially variable overtime length between
--					regular season and playoffs
-- head1			the name of the first head referee
-- head2			the name of the second head referee
-- linesman1		the name of the first linesman
-- linesman2		the name of the second linesman
-- spectators		the number of people attending the game
--					make it an INT if your stadium has more than 65535
--					spectators
ALTER TABLE liveticker_match 
ADD COLUMN overtime_length tinyint unsigned NOT NULL DEFAULT '5', 
ADD COLUMN head1 varchar(50), 
ADD COLUMN head2 varchar(50), 
ADD COLUMN linesman1 varchar(50),
ADD COLUMN linesman2 varchar(50),
ADD COLUMN spectators smallint unsigned;
