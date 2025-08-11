-- Create client_risk_assessments table
CREATE TABLE IF NOT EXISTS `client_risk_assessments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(255) NOT NULL,
  `client_type` enum('individual','corporate','partnership') NOT NULL,
  `is_pep` tinyint(1) DEFAULT 0,
  `has_opaque_ownership` tinyint(1) DEFAULT 0,
  `has_inconsistent_docs` tinyint(1) DEFAULT 0,
  `service_type` enum('high_risk','complex','standard') NOT NULL,
  `payment_method` enum('cash','efts_swift','pos') NOT NULL,
  `delivery_method` enum('remote','face_to_face') NOT NULL,
  `total_points` int(11) NOT NULL,
  `overall_risk_rating` varchar(50) NOT NULL,
  `client_acceptance` varchar(100) NOT NULL,
  `ongoing_monitoring` varchar(100) NOT NULL,
  `applied_risks` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `client_risk_assessments_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 