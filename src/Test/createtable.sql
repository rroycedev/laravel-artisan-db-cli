CREATE TABLE `notification_subscriptions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `device_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `app_auth_token` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `notification_subscriptions_user_id_unique` (`user_id`),
  UNIQUE KEY `notification_subscriptions_device_token_unique` (`device_token`),
  KEY `notification_subscriptions_app_auth_token_index` (`app_auth_token`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=utf8_unicode_ci

