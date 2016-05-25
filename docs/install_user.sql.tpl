ALTER TABLE `oxuser` ADD COLUMN `[{$column}]` bigint unsigned NOT NULL default '0' COMMENT 'Facebook id (used for openid login)';
