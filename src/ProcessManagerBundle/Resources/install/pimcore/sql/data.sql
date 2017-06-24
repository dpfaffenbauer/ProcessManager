CREATE TABLE IF NOT EXISTS `process_manager_processes` (
  `id`       INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`     VARCHAR(255) NOT NULL,
  `message`  TEXT         NOT NULL,
  `progress` INT          NOT NULL,
  `total`    INT          NOT NULL
);

CREATE TABLE IF NOT EXISTS `process_manager_executables` (
  `id`          INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(255) NOT NULL,
  `description` TEXT         NOT NULL,
  `type`        VARCHAR(255) NOT NULL,
  `cron`        VARCHAR(255) NULL,
  `settings`    TEXT         NOT NULL,
  `active`      INT          NOT NULL DEFAULT 1
);