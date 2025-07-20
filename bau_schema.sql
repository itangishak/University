
-- =========================================================
-- Burundi Adventist University (BAU) – Website database
-- MySQL 8.x / MariaDB 10.5+ compatible
-- Character set: utf8mb4  |  Collation: utf8mb4_unicode_ci
-- Generated: 2025‑07‑15
-- =========================================================

/* Create dedicated database */
CREATE DATABASE IF NOT EXISTS bau_website
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE bau_website;

/* ---------------------------------------------------------
   1.  Reference & authentication tables
   --------------------------------------------------------- */
CREATE TABLE languages (
  language_code CHAR(2) PRIMARY KEY,          -- 'fr', 'en', …
  name           VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

INSERT IGNORE INTO languages (language_code, name) VALUES
  ('fr', 'Français'),
  ('en', 'English');

/* User roles kept simple; can be moved to a dedicated table if preferred */
CREATE TABLE users (
  id                 BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role               ENUM('student','staff','admin','alumni','partner') NOT NULL,
  username           VARCHAR(60)  NOT NULL UNIQUE,
  email              VARCHAR(100) NOT NULL UNIQUE,
  password_hash      VARCHAR(255) NOT NULL,
  first_name         VARCHAR(100),
  last_name          VARCHAR(100),
  preferred_language CHAR(2) DEFAULT 'fr',
  created_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at         TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_users_language FOREIGN KEY (preferred_language)
            REFERENCES languages(language_code)
            ON UPDATE CASCADE
) ENGINE=InnoDB;

/* ---------------------------------------------------------
   2.  Academic structure (Faculties → Departments → Programs → Courses)
   --------------------------------------------------------- */
CREATE TABLE faculties (
  id         SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code       VARCHAR(15) NOT NULL UNIQUE,  -- e.g. 'FALSH'
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE faculty_translations (
  faculty_id     SMALLINT UNSIGNED,
  language_code  CHAR(2),
  name           VARCHAR(255) NOT NULL,
  description    TEXT,
  PRIMARY KEY (faculty_id, language_code),
  CONSTRAINT fk_faculty_tr_fac FOREIGN KEY (faculty_id) REFERENCES faculties(id) ON DELETE CASCADE,
  CONSTRAINT fk_faculty_tr_lang FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE departments (
  id          SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  faculty_id  SMALLINT UNSIGNED NOT NULL,
  code        VARCHAR(15) NOT NULL UNIQUE,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_dept_faculty FOREIGN KEY (faculty_id) REFERENCES faculties(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE department_translations (
  department_id SMALLINT UNSIGNED,
  language_code CHAR(2),
  name          VARCHAR(255) NOT NULL,
  description   TEXT,
  PRIMARY KEY (department_id, language_code),
  CONSTRAINT fk_dept_tr_dept FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE,
  CONSTRAINT fk_dept_tr_lang FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE programs (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  department_id   SMALLINT UNSIGNED NOT NULL,
  level           ENUM('Bachelor','Licence','Master','PhD','Certificate') NOT NULL,
  program_code    VARCHAR(20) NOT NULL UNIQUE,
  total_credits   SMALLINT UNSIGNED,
  duration_years  TINYINT UNSIGNED,
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_prog_dept FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE program_translations (
  program_id    INT UNSIGNED,
  language_code CHAR(2),
  name          VARCHAR(255) NOT NULL,
  description   MEDIUMTEXT,
  admission_req MEDIUMTEXT,
  PRIMARY KEY (program_id, language_code),
  CONSTRAINT fk_prog_tr_prog FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE,
  CONSTRAINT fk_prog_tr_lang FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE courses (
  id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  program_id         INT UNSIGNED NOT NULL,
  course_code        VARCHAR(20) NOT NULL UNIQUE,
  credits            TINYINT UNSIGNED NOT NULL DEFAULT 3,
  semester           ENUM('S1','S2','S3','S4','S5','S6','S7','S8','S9','S10') NOT NULL,
  language_of_instr  ENUM('fr','en','both') DEFAULT 'both',
  modality           ENUM('on‑campus','online','hybrid') DEFAULT 'on‑campus',
  created_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_course_prog FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE course_translations (
  course_id     INT UNSIGNED,
  language_code CHAR(2),
  title         VARCHAR(255) NOT NULL,
  description   MEDIUMTEXT,
  PRIMARY KEY (course_id, language_code),
  CONSTRAINT fk_course_tr_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
  CONSTRAINT fk_course_tr_lang FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB;

/* ---------------------------------------------------------
   3.  Content management: News, Events, Static pages, Media
   --------------------------------------------------------- */
CREATE TABLE news_articles (
  id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  author_user_id  BIGINT UNSIGNED NOT NULL,
  slug            VARCHAR(150) NOT NULL UNIQUE,
  status          ENUM('draft','published','archived') DEFAULT 'draft',
  hero_image_url  VARCHAR(255),
  published_at    DATETIME NULL,
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_news_author FOREIGN KEY (author_user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE news_translations (
  article_id     BIGINT UNSIGNED,
  language_code  CHAR(2),
  title          VARCHAR(255) NOT NULL,
  summary        TEXT,
  body           MEDIUMTEXT,
  PRIMARY KEY (article_id, language_code),
  FULLTEXT KEY ft_news_body (title, summary, body),
  CONSTRAINT fk_news_tr_news FOREIGN KEY (article_id) REFERENCES news_articles(id) ON DELETE CASCADE,
  CONSTRAINT fk_news_tr_lang FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB
  CHARACTER SET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE events (
  id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  start_datetime  DATETIME NOT NULL,
  end_datetime    DATETIME NOT NULL,
  location        VARCHAR(255),
  is_online       BOOLEAN DEFAULT FALSE,
  registration_url VARCHAR(255),
  calendar_uid    VARCHAR(100) UNIQUE,
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE event_translations (
  event_id       BIGINT UNSIGNED,
  language_code  CHAR(2),
  title          VARCHAR(255) NOT NULL,
  description    MEDIUMTEXT,
  PRIMARY KEY (event_id, language_code),
  FULLTEXT KEY ft_events_desc (title, description),
  CONSTRAINT fk_event_tr_event FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
  CONSTRAINT fk_event_tr_lang  FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE static_pages (
  id         SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug       VARCHAR(60) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE page_translations (
  page_id       SMALLINT UNSIGNED,
  language_code CHAR(2),
  title         VARCHAR(255) NOT NULL,
  content       MEDIUMTEXT,
  PRIMARY KEY (page_id, language_code),
  CONSTRAINT fk_page_tr_page FOREIGN KEY (page_id) REFERENCES static_pages(id) ON DELETE CASCADE,
  CONSTRAINT fk_page_tr_lang FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE media_gallery (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  media_type  ENUM('photo','video') NOT NULL,
  src_url     VARCHAR(255) NOT NULL,
  thumb_url   VARCHAR(255),
  caption_fr  VARCHAR(255),
  caption_en  VARCHAR(255),
  uploaded_by BIGINT UNSIGNED,
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_media_user FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

/* ---------------------------------------------------------
   4.  Student information & portal
   --------------------------------------------------------- */
CREATE TABLE student_profiles (
  user_id        BIGINT UNSIGNED PRIMARY KEY,
  student_no     VARCHAR(30) NOT NULL UNIQUE,
  program_id     INT UNSIGNED NOT NULL,
  entry_year     SMALLINT UNSIGNED NOT NULL,
  status         ENUM('active','inactive','graduated','suspended') DEFAULT 'active',
  advisor_id     BIGINT UNSIGNED NULL,
  FOREIGN KEY (user_id)    REFERENCES users(id)      ON DELETE CASCADE,
  FOREIGN KEY (program_id) REFERENCES programs(id)   ON DELETE CASCADE,
  FOREIGN KEY (advisor_id) REFERENCES users(id)      ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE course_enrollments (
  id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id   BIGINT UNSIGNED NOT NULL,
  course_id    INT UNSIGNED NOT NULL,
  acad_year    VARCHAR(9) NOT NULL,         -- e.g. '2025-2026'
  semester     ENUM('Semester 1','Semester 2','Summer') NOT NULL,
  grade_letter ENUM('A','B','C','D','E','F','I','P','NP') DEFAULT NULL,
  grade_point  DECIMAL(3,2) DEFAULT NULL,
  UNIQUE KEY uq_enroll (student_id, course_id, acad_year, semester),
  CONSTRAINT fk_enrol_student FOREIGN KEY (student_id) REFERENCES student_profiles(user_id) ON DELETE CASCADE,
  CONSTRAINT fk_enrol_course  FOREIGN KEY (course_id)  REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE invoices (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id    BIGINT UNSIGNED NOT NULL,
  invoice_no    VARCHAR(40) NOT NULL UNIQUE,
  amount_due    DECIMAL(10,2) NOT NULL,
  currency      CHAR(3) NOT NULL DEFAULT 'BIF',
  due_date      DATE NOT NULL,
  status        ENUM('pending','paid','partial','overdue') DEFAULT 'pending',
  pdf_url       VARCHAR(255),
  issued_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  paid_at       TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT fk_inv_student FOREIGN KEY (student_id) REFERENCES student_profiles(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

/* Internal (student–staff) messages */
CREATE TABLE messages (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sender_id     BIGINT UNSIGNED NOT NULL,
  receiver_id   BIGINT UNSIGNED NOT NULL,
  subject       VARCHAR(255) NOT NULL,
  body          MEDIUMTEXT NOT NULL,
  sent_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  read_at       TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT fk_msg_sender   FOREIGN KEY (sender_id)   REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_msg_receiver FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

/* ---------------------------------------------------------
   5.  Alumni & Career services
   --------------------------------------------------------- */
CREATE TABLE alumni_profiles (
  user_id          BIGINT UNSIGNED PRIMARY KEY,
  graduation_year  SMALLINT UNSIGNED,
  current_position VARCHAR(255),
  company          VARCHAR(255),
  bio              TEXT,
  CONSTRAINT fk_alumni_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE job_postings (
  id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  posted_by       BIGINT UNSIGNED,
  company_name    VARCHAR(255) NOT NULL,
  location        VARCHAR(255),
  apply_url       VARCHAR(255),
  valid_until     DATE,
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (posted_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE job_translations (
  job_id        BIGINT UNSIGNED,
  language_code CHAR(2),
  title         VARCHAR(255) NOT NULL,
  description   MEDIUMTEXT,
  PRIMARY KEY (job_id, language_code),
  FULLTEXT KEY ft_job_desc (title, description),
  FOREIGN KEY (job_id)        REFERENCES job_postings(id)     ON DELETE CASCADE,
  FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB;

/* ---------------------------------------------------------
   6.  Help‑desk / IT ticketing
   --------------------------------------------------------- */
CREATE TABLE tickets (
  id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  opened_by       BIGINT UNSIGNED NOT NULL,
  assigned_to     BIGINT UNSIGNED NULL,
  category        VARCHAR(100) NOT NULL,
  priority        ENUM('low','medium','high','urgent') DEFAULT 'medium',
  status          ENUM('open','in_progress','resolved','closed') DEFAULT 'open',
  sla_hours       SMALLINT UNSIGNED DEFAULT 72,
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (opened_by)  REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE ticket_messages (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ticket_id   BIGINT UNSIGNED NOT NULL,
  author_id   BIGINT UNSIGNED NOT NULL,
  body        MEDIUMTEXT NOT NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
  FOREIGN KEY (author_id) REFERENCES users(id)  ON DELETE CASCADE
) ENGINE=InnoDB;

/* ---------------------------------------------------------
   7.  Miscellaneous (FAQs, Newsletter, Search helper)
   --------------------------------------------------------- */
CREATE TABLE faq_categories (
  id           SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sort_order   SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE faq_category_translations (
  category_id   SMALLINT UNSIGNED,
  language_code CHAR(2),
  name          VARCHAR(255) NOT NULL,
  PRIMARY KEY (category_id, language_code),
  FOREIGN KEY (category_id)  REFERENCES faq_categories(id) ON DELETE CASCADE,
  FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE faqs (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id   SMALLINT UNSIGNED NOT NULL,
  sort_order    SMALLINT UNSIGNED DEFAULT 0,
  FOREIGN KEY (category_id) REFERENCES faq_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE faq_translations (
  faq_id        BIGINT UNSIGNED,
  language_code CHAR(2),
  question      VARCHAR(255) NOT NULL,
  answer        MEDIUMTEXT   NOT NULL,
  PRIMARY KEY (faq_id, language_code),
  FULLTEXT KEY ft_faq (question, answer),
  FOREIGN KEY (faq_id)        REFERENCES faqs(id) ON DELETE CASCADE,
  FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE newsletter_subscribers (
  id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email          VARCHAR(150) NOT NULL UNIQUE,
  subscribed_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  confirmed      BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB;

/* ---------------------------------------------------------
   8.  Search helper – simple view for full‑text search across entities
   --------------------------------------------------------- */
/* Example VIEW combining multilingual titles for global search */
CREATE OR REPLACE VIEW global_search_view AS
SELECT 'news'   AS source, n.id AS record_id, t.language_code, t.title, t.summary AS excerpt
FROM news_articles n
JOIN news_translations t ON t.article_id = n.id
UNION ALL
SELECT 'event', e.id, t.language_code, t.title, t.description
FROM events e
JOIN event_translations t ON t.event_id = e.id
UNION ALL
SELECT 'program', p.id, t.language_code, t.name, t.description
FROM programs p
JOIN program_translations t ON t.program_id = p.id;

/* Full-text index on the view will be created using MySQL’s
   functional index on a generated column in InnoDB ≥8.0. */

-- =========================================================
-- End of schema
-- =========================================================
