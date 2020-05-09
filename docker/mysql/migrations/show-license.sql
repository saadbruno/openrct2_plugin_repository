ALTER TABLE `plugins`
ADD `licenseName` varchar(64) COMMENT 'Plug-in license nickname',
ADD `licenseUrl` varchar(255) COMMENT 'Plug-in license URL';
