-- orct2p database schema

DROP TABLE IF EXISTS `plugins`;

CREATE TABLE `plugins` (
  `id` varchar(64) NOT NULL COMMENT 'Repo ID from GitHub',
  `name` varchar(255) DEFAULT NULL COMMENT 'Repo Name from GitHub',
  `url` varchar(255) DEFAULT NULL COMMENT 'Repo URL',
  `description` varchar(1024) NULL COMMENT 'Repo description',
  `updatedAt` int(12) DEFAULT NULL COMMENT 'Last time repo was updated, in Unix timestamp',
  `usesCustomOpenGraphImage` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Defines if repo has an OG image',
  `thumbnail` varchar(255) DEFAULT NULL COMMENT 'from "openGraphImageUrl"',
  `stargazers` int(12) NOT NULL DEFAULT '0' COMMENT 'How many stars the repo has',
  `owner` varchar(64) NOT NULL DEFAULT '0' COMMENT 'GitHub owner user''s ID.\r\nFallbacks to a default user',
  `readme` text COMMENT 'Plug-in description, pulled from repo''s readme',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Main plug-in index';

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `plugin_id` varchar(255) NOT NULL COMMENT 'id form the plugins table',
  `tag` varchar(64) NOT NULL COMMENT 'tag from github',
  KEY `plugin_id` (`plugin_id`),
  UNIQUE KEY `plugin_tag_pair` (`plugin_id`,`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` varchar(64)  NOT NULL COMMENT 'plugin owner github id',
  `username` varchar(32)  DEFAULT NULL COMMENT 'username from github, from the "login" field',
  `avatarUrl` varchar(255)  DEFAULT NULL COMMENT 'avatar from github',
  `url` varchar(255)  DEFAULT NULL COMMENT 'github profile url',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Users (owners) for plugins';

-- adds fallback user
INSERT INTO `users` (`id`, `username`, `avatarUrl`, `url`) VALUES
('0', 'Unknown', NULL, NULL);
