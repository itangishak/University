<?php
require_once __DIR__ . '/Database.php';

/**
 * Notification System Class
 * Handles in-app notifications and email queue management
 */
class NotificationSystem {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new notification for a user
     */
    public function createNotification($userId, $typeCode, $title, $message, $actionUrl = null, $variables = []) {
        // Get notification type
        $notificationType = $this->db->fetch(
            "SELECT id FROM notification_types WHERE code = ?",
            [$typeCode]
        );
        
        if (!$notificationType) {
            throw new Exception("Invalid notification type: $typeCode");
        }
        
        // Replace variables in title and message
        $title = $this->replaceVariables($title, $variables);
        $message = $this->replaceVariables($message, $variables);
        
        // Insert notification
        $sql = "INSERT INTO user_notifications (user_id, notification_type_id, title, message, action_url) 
                VALUES (?, ?, ?, ?, ?)";
        
        $this->db->execute($sql, [
            $userId,
            $notificationType['id'],
            $title,
            $message,
            $actionUrl
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Get notifications for a user
     */
    public function getUserNotifications($userId, $limit = 20, $offset = 0, $unreadOnly = false) {
        $whereClause = "WHERE n.user_id = ?";
        $params = [$userId];
        
        if ($unreadOnly) {
            $whereClause .= " AND n.is_read = FALSE";
        }
        
        $sql = "SELECT n.*, nt.code as type_code, nt.icon 
                FROM user_notifications n
                JOIN notification_types nt ON n.notification_type_id = nt.id
                $whereClause
                ORDER BY n.created_at DESC
                LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId, $userId) {
        $sql = "UPDATE user_notifications 
                SET is_read = TRUE, read_at = NOW() 
                WHERE id = ? AND user_id = ?";
        
        return $this->db->execute($sql, [$notificationId, $userId]);
    }
    
    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId) {
        $sql = "UPDATE user_notifications 
                SET is_read = TRUE, read_at = NOW() 
                WHERE user_id = ? AND is_read = FALSE";
        
        return $this->db->execute($sql, [$userId]);
    }
    
    /**
     * Get unread notification count
     */
    public function getUnreadCount($userId) {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as count FROM user_notifications WHERE user_id = ? AND is_read = FALSE",
            [$userId]
        );
        
        return $result['count'];
    }
    
    /**
     * Delete notification
     */
    public function deleteNotification($notificationId, $userId) {
        $sql = "DELETE FROM user_notifications WHERE id = ? AND user_id = ?";
        return $this->db->execute($sql, [$notificationId, $userId]);
    }
    
    /**
     * Queue email notification
     */
    public function queueEmail($toEmail, $subject, $bodyHtml, $bodyText = null) {
        if (!$bodyText) {
            $bodyText = strip_tags($bodyHtml);
        }
        
        $sql = "INSERT INTO email_queue (to_email, subject, body_html, body_text) 
                VALUES (?, ?, ?, ?)";
        
        $this->db->execute($sql, [$toEmail, $subject, $bodyHtml, $bodyText]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Process email queue
     */
    public function processEmailQueue($limit = 10) {
        $emails = $this->db->fetchAll(
            "SELECT * FROM email_queue 
             WHERE status = 'pending' AND attempts < max_attempts 
             ORDER BY created_at ASC 
             LIMIT ?",
            [$limit]
        );
        
        foreach ($emails as $email) {
            $this->sendEmail($email);
        }
    }
    
    /**
     * Send individual email
     */
    private function sendEmail($emailData) {
        try {
            // Update attempts
            $this->db->execute(
                "UPDATE email_queue SET attempts = attempts + 1 WHERE id = ?",
                [$emailData['id']]
            );
            
            // Here you would integrate with your email service (PHPMailer, SendGrid, etc.)
            // For now, we'll just mark as sent
            $success = $this->mockSendEmail($emailData);
            
            if ($success) {
                $this->db->execute(
                    "UPDATE email_queue SET status = 'sent', sent_at = NOW() WHERE id = ?",
                    [$emailData['id']]
                );
            } else {
                throw new Exception("Failed to send email");
            }
            
        } catch (Exception $e) {
            $this->db->execute(
                "UPDATE email_queue SET status = 'failed', error_message = ? WHERE id = ?",
                [$e->getMessage(), $emailData['id']]
            );
        }
    }
    
    /**
     * Mock email sending (replace with actual email service)
     */
    private function mockSendEmail($emailData) {
        // This is a placeholder - integrate with your actual email service
        error_log("Email sent to: " . $emailData['to_email'] . " - Subject: " . $emailData['subject']);
        return true;
    }
    
    /**
     * Replace variables in text
     */
    private function replaceVariables($text, $variables) {
        foreach ($variables as $key => $value) {
            $text = str_replace('{' . $key . '}', $value, $text);
        }
        return $text;
    }
    
    /**
     * Send notification with email option
     */
    public function notify($userId, $typeCode, $title, $message, $actionUrl = null, $variables = [], $sendEmail = false) {
        // Create in-app notification
        $notificationId = $this->createNotification($userId, $typeCode, $title, $message, $actionUrl, $variables);
        
        // Send email if requested and user preferences allow it
        if ($sendEmail) {
            $user = $this->db->fetch("SELECT email FROM users WHERE id = ?", [$userId]);
            if ($user) {
                $this->queueEmail(
                    $user['email'],
                    $title,
                    $this->formatEmailBody($message, $actionUrl),
                    strip_tags($message)
                );
            }
        }
        
        return $notificationId;
    }
    
    /**
     * Format email body with basic HTML
     */
    private function formatEmailBody($message, $actionUrl = null) {
        $html = "<html><body>";
        $html .= "<p>" . nl2br(htmlspecialchars($message)) . "</p>";
        
        if ($actionUrl) {
            $html .= "<p><a href='" . htmlspecialchars($actionUrl) . "' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>View Details</a></p>";
        }
        
        $html .= "<hr><p><small>This is an automated message from Burundi Adventist University.</small></p>";
        $html .= "</body></html>";
        
        return $html;
    }
    
    /**
     * Clean old notifications
     */
    public function cleanOldNotifications($daysOld = 30) {
        $sql = "DELETE FROM user_notifications WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
        return $this->db->execute($sql, [$daysOld]);
    }
}
