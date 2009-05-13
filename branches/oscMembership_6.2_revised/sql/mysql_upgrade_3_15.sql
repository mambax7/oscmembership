
ALTER TABLE <dbname>.`<dbprefix>_oscmembership_family` ADD COLUMN `picloc` TEXT  AFTER `editedby`;

ALTER TABLE <dbname>.`<dbprefix>_oscmembership_person` ADD COLUMN `picloc` TEXT  AFTER `editedby`;
