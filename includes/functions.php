<?php
// Helper Functions

/**
 * Get current language
 * @return string Current language code
 */
function getCurrentLanguage() {
    return isset($_SESSION['language']) ? $_SESSION['language'] : DEFAULT_LANGUAGE;
}

/**
 * Translate text based on current language
 * @param string $key Translation key
 * @return string Translated text
 */
function translate($key) {
    global $translations;
    $lang = getCurrentLanguage();
    return isset($translations[$key]) ? $translations[$key] : $key;
}

/**
 * Sanitize input
 * @param string $input Input to sanitize
 * @return string Sanitized input
 */
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

/**
 * Database connection helper
 * @return PDO Database connection
 */
function getDbConnection() {
    try {
        require_once __DIR__ . '/../config/database.php';
        return new PDO(
            "mysql:host=$db_host;dbname=$db_name",
            $db_user,
            $db_pass
        );
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

/**
 * Get university contact information
 * @param string $type Type of contact info (phone1, phone2, email, address)
 * @return string Contact information
 */
function getUniversityContact($type) {
    switch (strtolower($type)) {
        case 'phone1':
            return UNIVERSITY_PHONE_1;
        case 'phone2':
            return UNIVERSITY_PHONE_2;
        case 'email':
            return UNIVERSITY_EMAIL;
        case 'address':
            return UNIVERSITY_ADDRESS;
        case 'emergency':
            return EMERGENCY_CONTACT;
        default:
            return '';
    }
}

/**
 * Get social media links
 * @param string $platform Social media platform (facebook, twitter, instagram, youtube, linkedin)
 * @return string Social media URL
 */
function getSocialMediaLink($platform) {
    switch (strtolower($platform)) {
        case 'facebook':
            return SOCIAL_FACEBOOK;
        case 'twitter':
            return SOCIAL_TWITTER;
        case 'instagram':
            return SOCIAL_INSTAGRAM;
        case 'youtube':
            return SOCIAL_YOUTUBE;
        case 'linkedin':
            return SOCIAL_LINKEDIN;
        default:
            return '#';
    }
}

/**
 * Get university information
 * @param string $type Type of info (full_name, short_name, motto, established_year)
 * @return string University information
 */
function getUniversityInfo($type) {
    switch (strtolower($type)) {
        case 'full_name':
            return UNIVERSITY_FULL_NAME;
        case 'short_name':
            return UNIVERSITY_SHORT_NAME;
        case 'motto':
            return UNIVERSITY_MOTTO;
        case 'established_year':
            return UNIVERSITY_ESTABLISHED_YEAR;
        case 'hours_weekday':
            return UNIVERSITY_HOURS_WEEKDAY;
        case 'hours_weekend':
            return UNIVERSITY_HOURS_WEEKEND;
        default:
            return '';
    }
}

/**
 * Get formatted phone number for tel: links
 * @param string $phone Phone number
 * @return string Formatted phone number for tel: links
 */
function formatPhoneForTel($phone) {
    return str_replace([' ', '-', '(', ')'], '', $phone);
}

/**
 * Get all social media links as array
 * @return array Array of social media links
 */
function getAllSocialMediaLinks() {
    return [
        'facebook' => SOCIAL_FACEBOOK,
        'twitter' => SOCIAL_TWITTER,
        'instagram' => SOCIAL_INSTAGRAM,
        'youtube' => SOCIAL_YOUTUBE,
        'linkedin' => SOCIAL_LINKEDIN
    ];
}

/**
 * Get footer links
 * @param string $type Type of link (privacy, terms)
 * @return string Footer link URL
 */
function getFooterLink($type) {
    switch (strtolower($type)) {
        case 'privacy':
            return PRIVACY_POLICY_URL;
        case 'terms':
            return TERMS_SERVICE_URL;
        default:
            return '#';
    }
}

/**
 * Get university statistics from database
 * @return array Array of statistics (students, faculty, programs, years_established)
 */
function getUniversityStats() {
    try {
        require_once __DIR__ . '/../config/database.php';
        $database = new Database();
        $pdo = $database->getConnection();
        
        if (!$pdo) {
            throw new Exception("Database connection failed");
        }
        
        // Count students (users with role 'student')
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'student' AND status = 'active'");
        $stmt->execute();
        $students = $stmt->fetchColumn();
        
        // Count faculty (users with roles other than 'student')
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role IN ('administrator', 'communication_officer', 'admission_officer') AND status = 'active'");
        $stmt->execute();
        $faculty = $stmt->fetchColumn();
        
        // Count programs
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM programs");
        $stmt->execute();
        $programs = $stmt->fetchColumn();
        
        // Calculate years since establishment
        $currentYear = date('Y');
        $establishedYear = UNIVERSITY_ESTABLISHED_YEAR;
        $yearsEstablished = $currentYear - $establishedYear;
        
        return [
            'students' => (int)$students,
            'faculty' => (int)$faculty,
            'programs' => (int)$programs,
            'years_established' => max(1, $yearsEstablished) // At least 1 year
        ];
        
    } catch (PDOException $e) {
        // Return default values if database connection fails
        error_log("Database error in getUniversityStats: " . $e->getMessage());
        return [
            'students' => 500,  // Default fallback values
            'faculty' => 50,
            'programs' => 15,
            'years_established' => 1
        ];
    }
}

/**
 * Get a specific university statistic
 * @param string $type Type of statistic (students, faculty, programs, years_established)
 * @return int Statistic value
 */
function getUniversityStat($type) {
    static $stats = null;
    
    if ($stats === null) {
        $stats = getUniversityStats();
    }
    
    return isset($stats[$type]) ? $stats[$type] : 0;
}
?>
