ALTER TABLE `users` ADD `name` VARCHAR(255) NULL DEFAULT NULL AFTER `id`;
ALTER TABLE `books` ADD `description` VARCHAR(255) NULL DEFAULT NULL AFTER `title`;
ALTER TABLE `books` ADD `author` VARCHAR(255) NULL DEFAULT NULL AFTER `title`;
