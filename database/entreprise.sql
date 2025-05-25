CREATE TABLE `entreprise-detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `acronym` varchar(255) DEFAULT NULL,
  `courte-description` varchar(255) DEFAULT NULL,
  `description` varchar(5000) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;