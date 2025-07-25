-- =========================================================
-- BAU Website - Additional Schema for University System
-- Tables for applications, documents, notifications, sessions
-- =========================================================

USE bau_website;

/* ---------------------------------------------------------
   9. Student Applications System
   --------------------------------------------------------- */

-- Application forms with multi-step support
CREATE TABLE application_forms (
  id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  applicant_user_id BIGINT UNSIGNED NOT NULL,
  program_id        INT UNSIGNED NOT NULL,
  application_year  SMALLINT UNSIGNED NOT NULL,
  status            ENUM('draft','submitted','under_review','approved','rejected','waitlisted') DEFAULT 'draft',
  submitted_at      TIMESTAMP NULL,
  reviewed_at       TIMESTAMP NULL,
  reviewed_by       BIGINT UNSIGNED NULL,
  rejection_reason  TEXT NULL,
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_app_applicant FOREIGN KEY (applicant_user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_app_program FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE,
  CONSTRAINT fk_app_reviewer FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
  UNIQUE KEY uq_app_user_program_year (applicant_user_id, program_id, application_year)
) ENGINE=InnoDB;

-- Personal information section
CREATE TABLE application_personal_info (
  application_id    BIGINT UNSIGNED PRIMARY KEY,
  date_of_birth     DATE,
  gender           ENUM('male','female','other','prefer_not_to_say'),
  nationality      VARCHAR(100),
  passport_number  VARCHAR(50),
  phone_number     VARCHAR(20),
  emergency_contact_name VARCHAR(100),
  emergency_contact_phone VARCHAR(20),
  address_line1    VARCHAR(255),
  address_line2    VARCHAR(255),
  city             VARCHAR(100),
  state_province   VARCHAR(100),
  postal_code      VARCHAR(20),
  country          VARCHAR(100),
  CONSTRAINT fk_personal_app FOREIGN KEY (application_id) REFERENCES application_forms(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Academic history section
CREATE TABLE application_academic_history (
  id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  application_id   BIGINT UNSIGNED NOT NULL,
  institution_name VARCHAR(255) NOT NULL,
  degree_type      VARCHAR(100),
  field_of_study   VARCHAR(255),
  start_date       DATE,
  end_date         DATE,
  gpa              DECIMAL(3,2),
  gpa_scale        DECIMAL(3,2) DEFAULT 4.0,
  is_current       BOOLEAN DEFAULT FALSE,
  CONSTRAINT fk_academic_app FOREIGN KEY (application_id) REFERENCES application_forms(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Work experience section
CREATE TABLE application_work_experience (
  id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  application_id   BIGINT UNSIGNED NOT NULL,
  company_name     VARCHAR(255) NOT NULL,
  position_title   VARCHAR(255),
  start_date       DATE,
  end_date         DATE,
  is_current       BOOLEAN DEFAULT FALSE,
  description      TEXT,
  CONSTRAINT fk_work_app FOREIGN KEY (application_id) REFERENCES application_forms(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Additional information section
CREATE TABLE application_additional_info (
  application_id           BIGINT UNSIGNED PRIMARY KEY,
  personal_statement       MEDIUMTEXT,
  why_this_program        MEDIUMTEXT,
  career_goals            MEDIUMTEXT,
  extracurricular_activities TEXT,
  awards_honors           TEXT,
  languages_spoken        TEXT,
  special_needs           TEXT,
  CONSTRAINT fk_additional_app FOREIGN KEY (application_id) REFERENCES application_forms(id) ON DELETE CASCADE
) ENGINE=InnoDB;

/* ---------------------------------------------------------
   10. Document Management System
   --------------------------------------------------------- */

-- Document types for applications
CREATE TABLE document_types (
  id          SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code        VARCHAR(20) NOT NULL UNIQUE,
  is_required BOOLEAN DEFAULT TRUE,
  max_size_mb TINYINT UNSIGNED DEFAULT 5,
  allowed_extensions VARCHAR(100) DEFAULT 'pdf,doc,docx,jpg,jpeg,png'
) ENGINE=InnoDB;

CREATE TABLE document_type_translations (
  document_type_id SMALLINT UNSIGNED,
  language_code    CHAR(2),
  name            VARCHAR(255) NOT NULL,
  description     TEXT,
  PRIMARY KEY (document_type_id, language_code),
  CONSTRAINT fk_doctype_tr_type FOREIGN KEY (document_type_id) REFERENCES document_types(id) ON DELETE CASCADE,
  CONSTRAINT fk_doctype_tr_lang FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Uploaded documents
CREATE TABLE application_documents (
  id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  application_id   BIGINT UNSIGNED NOT NULL,
  document_type_id SMALLINT UNSIGNED NOT NULL,
  original_filename VARCHAR(255) NOT NULL,
  stored_filename  VARCHAR(255) NOT NULL,
  file_path        VARCHAR(500) NOT NULL,
  file_size_bytes  INT UNSIGNED NOT NULL,
  mime_type        VARCHAR(100),
  uploaded_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_doc_app FOREIGN KEY (application_id) REFERENCES application_forms(id) ON DELETE CASCADE,
  CONSTRAINT fk_doc_type FOREIGN KEY (document_type_id) REFERENCES document_types(id) ON DELETE CASCADE
) ENGINE=InnoDB;

/* ---------------------------------------------------------
   11. Session Management (without cookies)
   --------------------------------------------------------- */

-- Session tokens for authentication
CREATE TABLE user_sessions (
  id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id          BIGINT UNSIGNED NOT NULL,
  session_token    VARCHAR(128) NOT NULL UNIQUE,
  ip_address       VARCHAR(45),
  user_agent       VARCHAR(500),
  expires_at       TIMESTAMP NOT NULL,
  created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_activity    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_session_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_session_token (session_token),
  INDEX idx_session_expires (expires_at)
) ENGINE=InnoDB;

/* ---------------------------------------------------------
   12. Notification System
   --------------------------------------------------------- */

-- Notification types
CREATE TABLE notification_types (
  id    SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code  VARCHAR(50) NOT NULL UNIQUE,
  icon  VARCHAR(50) DEFAULT 'info'
) ENGINE=InnoDB;

CREATE TABLE notification_type_translations (
  notification_type_id SMALLINT UNSIGNED,
  language_code       CHAR(2),
  name               VARCHAR(255) NOT NULL,
  template           TEXT,
  PRIMARY KEY (notification_type_id, language_code),
  CONSTRAINT fk_notif_type_tr_type FOREIGN KEY (notification_type_id) REFERENCES notification_types(id) ON DELETE CASCADE,
  CONSTRAINT fk_notif_type_tr_lang FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB;

-- User notifications
CREATE TABLE user_notifications (
  id                   BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id             BIGINT UNSIGNED NOT NULL,
  notification_type_id SMALLINT UNSIGNED NOT NULL,
  title               VARCHAR(255) NOT NULL,
  message             TEXT NOT NULL,
  action_url          VARCHAR(500),
  is_read             BOOLEAN DEFAULT FALSE,
  created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  read_at             TIMESTAMP NULL,
  CONSTRAINT fk_notif_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_notif_type FOREIGN KEY (notification_type_id) REFERENCES notification_types(id) ON DELETE CASCADE,
  INDEX idx_user_notifications (user_id, is_read, created_at)
) ENGINE=InnoDB;

-- Email notification queue
CREATE TABLE email_queue (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  to_email    VARCHAR(255) NOT NULL,
  subject     VARCHAR(255) NOT NULL,
  body_html   MEDIUMTEXT,
  body_text   MEDIUMTEXT,
  status      ENUM('pending','sent','failed') DEFAULT 'pending',
  attempts    TINYINT UNSIGNED DEFAULT 0,
  max_attempts TINYINT UNSIGNED DEFAULT 3,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  sent_at     TIMESTAMP NULL,
  error_message TEXT NULL,
  INDEX idx_email_status (status, created_at)
) ENGINE=InnoDB;

/* ---------------------------------------------------------
   13. User Preferences and Settings
   --------------------------------------------------------- */

-- User notification preferences
CREATE TABLE user_notification_preferences (
  user_id              BIGINT UNSIGNED,
  notification_type_id SMALLINT UNSIGNED,
  email_enabled        BOOLEAN DEFAULT TRUE,
  in_app_enabled       BOOLEAN DEFAULT TRUE,
  PRIMARY KEY (user_id, notification_type_id),
  CONSTRAINT fk_pref_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_pref_notif_type FOREIGN KEY (notification_type_id) REFERENCES notification_types(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- User dashboard preferences
CREATE TABLE user_dashboard_preferences (
  user_id           BIGINT UNSIGNED PRIMARY KEY,
  dashboard_layout  JSON,
  theme            ENUM('light','dark','auto') DEFAULT 'light',
  items_per_page   TINYINT UNSIGNED DEFAULT 10,
  CONSTRAINT fk_dash_pref_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

/* ---------------------------------------------------------
   14. Analytics and Reporting
   --------------------------------------------------------- */

-- Content analytics
CREATE TABLE content_analytics (
  id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  content_type ENUM('news','event','page','program') NOT NULL,
  content_id   BIGINT UNSIGNED NOT NULL,
  user_id      BIGINT UNSIGNED NULL,
  action_type  ENUM('view','click','share','download') NOT NULL,
  ip_address   VARCHAR(45),
  user_agent   VARCHAR(500),
  referrer     VARCHAR(500),
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_analytics_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_content_analytics (content_type, content_id, created_at),
  INDEX idx_analytics_date (created_at)
) ENGINE=InnoDB;

-- System usage analytics
CREATE TABLE system_analytics (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  metric_name VARCHAR(100) NOT NULL,
  metric_value DECIMAL(15,4) NOT NULL,
  dimensions  JSON,
  recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_metric_date (metric_name, recorded_at)
) ENGINE=InnoDB;

/* ---------------------------------------------------------
   15. Content Scheduling
   --------------------------------------------------------- */

-- Scheduled content publication
CREATE TABLE scheduled_content (
  id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  content_type ENUM('news','event') NOT NULL,
  content_id   BIGINT UNSIGNED NOT NULL,
  scheduled_for TIMESTAMP NOT NULL,
  action_type  ENUM('publish','unpublish') NOT NULL,
  status       ENUM('pending','executed','failed') DEFAULT 'pending',
  created_by   BIGINT UNSIGNED NOT NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  executed_at  TIMESTAMP NULL,
  CONSTRAINT fk_sched_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_scheduled_execution (scheduled_for, status)
) ENGINE=InnoDB;

/* ---------------------------------------------------------
   16. Insert Initial Data
   --------------------------------------------------------- */

-- Document types
INSERT INTO document_types (code, is_required, max_size_mb, allowed_extensions) VALUES
('transcript', TRUE, 10, 'pdf,jpg,jpeg,png'),
('diploma', TRUE, 10, 'pdf,jpg,jpeg,png'),
('recommendation_letter', TRUE, 5, 'pdf,doc,docx'),
('personal_statement', TRUE, 5, 'pdf,doc,docx'),
('cv_resume', TRUE, 5, 'pdf,doc,docx'),
('passport_copy', TRUE, 5, 'pdf,jpg,jpeg,png'),
('language_certificate', FALSE, 5, 'pdf,jpg,jpeg,png'),
('portfolio', FALSE, 20, 'pdf,jpg,jpeg,png,zip');

-- Document type translations
INSERT INTO document_type_translations (document_type_id, language_code, name, description) VALUES
(1, 'en', 'Official Transcript', 'Official academic transcript from your previous institution'),
(1, 'fr', 'Relevé de notes officiel', 'Relevé de notes académique officiel de votre institution précédente'),
(2, 'en', 'Diploma/Degree Certificate', 'Copy of your diploma or degree certificate'),
(2, 'fr', 'Diplôme/Certificat de diplôme', 'Copie de votre diplôme ou certificat de diplôme'),
(3, 'en', 'Letter of Recommendation', 'Letter of recommendation from academic or professional reference'),
(3, 'fr', 'Lettre de recommandation', 'Lettre de recommandation d\'une référence académique ou professionnelle'),
(4, 'en', 'Personal Statement', 'Personal statement or statement of purpose'),
(4, 'fr', 'Déclaration personnelle', 'Déclaration personnelle ou déclaration d\'intention'),
(5, 'en', 'CV/Resume', 'Current curriculum vitae or resume'),
(5, 'fr', 'CV/Curriculum Vitae', 'Curriculum vitae ou CV actuel'),
(6, 'en', 'Passport Copy', 'Copy of passport identification page'),
(6, 'fr', 'Copie du passeport', 'Copie de la page d\'identification du passeport'),
(7, 'en', 'Language Certificate', 'Language proficiency certificate (TOEFL, IELTS, etc.)'),
(7, 'fr', 'Certificat de langue', 'Certificat de compétence linguistique (TOEFL, IELTS, etc.)'),
(8, 'en', 'Portfolio', 'Academic or professional portfolio'),
(8, 'fr', 'Portfolio', 'Portfolio académique ou professionnel');

-- Notification types
INSERT INTO notification_types (code, icon) VALUES
('application_submitted', 'check-circle'),
('application_status_change', 'info-circle'),
('document_uploaded', 'upload'),
('document_required', 'alert-triangle'),
('news_published', 'newspaper'),
('event_reminder', 'calendar'),
('system_maintenance', 'settings'),
('welcome', 'user-plus');

-- Notification type translations
INSERT INTO notification_type_translations (notification_type_id, language_code, name, template) VALUES
(1, 'en', 'Application Submitted', 'Your application has been successfully submitted for review.'),
(1, 'fr', 'Candidature soumise', 'Votre candidature a été soumise avec succès pour examen.'),
(2, 'en', 'Application Status Update', 'Your application status has been updated to: {status}'),
(2, 'fr', 'Mise à jour du statut de candidature', 'Le statut de votre candidature a été mis à jour à: {status}'),
(3, 'en', 'Document Uploaded', 'Document "{document_name}" has been uploaded successfully.'),
(3, 'fr', 'Document téléchargé', 'Le document "{document_name}" a été téléchargé avec succès.'),
(4, 'en', 'Document Required', 'Please upload the required document: {document_name}'),
(4, 'fr', 'Document requis', 'Veuillez télécharger le document requis: {document_name}'),
(5, 'en', 'News Published', 'New article published: {title}'),
(5, 'fr', 'Actualité publiée', 'Nouvel article publié: {title}'),
(6, 'en', 'Event Reminder', 'Reminder: {event_title} starts in {time}'),
(6, 'fr', 'Rappel d\'événement', 'Rappel: {event_title} commence dans {time}'),
(7, 'en', 'System Maintenance', 'System maintenance scheduled for {date}'),
(7, 'fr', 'Maintenance système', 'Maintenance système prévue pour {date}'),
(8, 'en', 'Welcome', 'Welcome to BAU! Your account has been created successfully.'),
(8, 'fr', 'Bienvenue', 'Bienvenue à BAU! Votre compte a été créé avec succès.');

-- =========================================================
-- End of additional schema
-- =========================================================
