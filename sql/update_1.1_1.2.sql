-- Adds the tinyint to liveticker_match to set a game as currently running or
-- not. The entry is 1 by default meaning that the game is active. If it's set
-- to 0 through the admin interface the displayed ticker will not refresh 
-- automatically.
ALTER TABLE liveticker_match ADD COLUMN running tinyint NOT NULL DEFAULT '1';

-- Another requested feature was the ability to store the current local time
-- along with the liveticker entry. Therefore we will need a timestamp field
ALTER TABLE liveticker_entry ADD COLUMN entry_time datetime NOT NULL DEFAULT '1970-01-01 00:00:00';