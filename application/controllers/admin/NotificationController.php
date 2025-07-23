<?php

// Подключаем необходимые файлы
require_once 'engine/BaseController.php';
require_once 'application/controllers/admin/BaseAdminController.php';

class NotificationController extends BaseAdminController {
    
    public function __construct() {
        parent::__construct();
        require_once 'engine/main/encryption.php';
    }
    
    // Главная страница уведомлений
    public function index() {
        $this->requireAccessLevel(5);
        
        try {
            require_once 'engine/main/db.php';
            
            $securityAlerts = $this->getSecurityAlerts();
            $notificationSettings = $this->getNotificationSettings();
            $telegramNotifications = $this->getTelegramNotifications();
            $emailNotifications = $this->getEmailNotifications();
            
            return $this->render('admin/notifications/index', [
                'title' => 'Уведомления и алерты',
                'securityAlerts' => $securityAlerts,
                'notificationSettings' => $notificationSettings,
                'telegramNotifications' => $telegramNotifications,
                'emailNotifications' => $emailNotifications
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке уведомлений: ' . $e->getMessage()
            ]);
        }
    }
    
    // Настройки уведомлений
    public function settings() {
        $this->requireAccessLevel(5);
        
        try {
            require_once 'engine/main/db.php';
            
            $settings = $this->getNotificationSettings();
            $testResults = $this->getTestResults();
            
            return $this->render('admin/notifications/settings', [
                'title' => 'Настройки уведомлений',
                'settings' => $settings,
                'testResults' => $testResults
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке настроек: ' . $e->getMessage()
            ]);
        }
    }
    
    // Обновление настроек
    public function updateSettings() {
        $this->requireAccessLevel(5);
        
        header('Content-Type: application/json');
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            require_once 'engine/main/db.php';
            
            $userId = $_SESSION['admin_user_id'];
            $settings = $data['settings'] ?? [];
            
            Database::execute(
                "INSERT INTO notification_settings (user_id, email_enabled, telegram_enabled, telegram_chat_id, alert_types, daily_reports, weekly_reports) 
                 VALUES (?, ?, ?, ?, ?, ?, ?) 
                 ON DUPLICATE KEY UPDATE 
                 email_enabled = VALUES(email_enabled),
                 telegram_enabled = VALUES(telegram_enabled),
                 telegram_chat_id = VALUES(telegram_chat_id),
                 alert_types = VALUES(alert_types),
                 daily_reports = VALUES(daily_reports),
                 weekly_reports = VALUES(weekly_reports)",
                [
                    $userId,
                    $settings['email_enabled'] ?? true,
                    $settings['telegram_enabled'] ?? false,
                    $settings['telegram_chat_id'] ?? '',
                    json_encode($settings['alert_types'] ?? []),
                    $settings['daily_reports'] ?? false,
                    $settings['weekly_reports'] ?? false
                ]
            );
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    // Тестирование уведомлений
    public function testNotification() {
        $this->requireAccessLevel(5);
        
        header('Content-Type: application/json');
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $type = $data['type'] ?? 'email';
            
            require_once 'engine/main/db.php';
            
            $result = false;
            $message = '';
            
            switch ($type) {
                case 'email':
                    $result = $this->sendTestEmail();
                    $message = $result ? 'Тестовое email уведомление отправлено' : 'Ошибка отправки email';
                    break;
                case 'telegram':
                    $result = $this->sendTestTelegram();
                    $message = $result ? 'Тестовое Telegram уведомление отправлено' : 'Ошибка отправки Telegram';
                    break;
                default:
                    throw new Exception('Неизвестный тип уведомления');
            }
            
            echo json_encode([
                'success' => $result,
                'message' => $message
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    // Отправка уведомления
    public function sendNotification() {
        $this->requireAccessLevel(5);
        
        header('Content-Type: application/json');
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            require_once 'engine/main/db.php';
            
            $type = $data['type'] ?? 'security_alert';
            $recipients = $data['recipients'] ?? [];
            $subject = $data['subject'] ?? '';
            $message = $data['message'] ?? '';
            
            $result = false;
            
            switch ($type) {
                case 'email':
                    $result = $this->sendEmailNotification($recipients, $subject, $message);
                    break;
                case 'telegram':
                    $result = $this->sendTelegramNotification($recipients, $message);
                    break;
                case 'both':
                    $emailResult = $this->sendEmailNotification($recipients, $subject, $message);
                    $telegramResult = $this->sendTelegramNotification($recipients, $message);
                    $result = $emailResult && $telegramResult;
                    break;
                default:
                    throw new Exception('Неизвестный тип уведомления');
            }
            
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Уведомление отправлено' : 'Ошибка отправки уведомления'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    // API для получения уведомлений
    public function api() {
        $this->requireAccessLevel(5);
        
        header('Content-Type: application/json');
        
        try {
            require_once 'engine/main/db.php';
            
            $type = $_GET['type'] ?? 'alerts';
            
            switch ($type) {
                case 'alerts':
                    $data = $this->getSecurityAlertsAPI();
                    break;
                case 'settings':
                    $data = $this->getNotificationSettingsAPI();
                    break;
                case 'history':
                    $data = $this->getNotificationHistory();
                    break;
                default:
                    $data = $this->getAllNotifications();
            }
            
            echo json_encode($data);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    // Отметить уведомление как прочитанное
    public function markAsRead() {
        $this->requireAccessLevel(5);
        
        header('Content-Type: application/json');
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $alertId = $data['alert_id'] ?? null;
            
            if (!$alertId) {
                throw new Exception('Alert ID required');
            }
            
            require_once 'engine/main/db.php';
            
            Database::execute(
                "UPDATE security_alerts SET is_read = 1 WHERE id = ?",
                [$alertId]
            );
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    // Отметить все уведомления как прочитанные
    public function markAllAsRead() {
        $this->requireAccessLevel(5);
        
        header('Content-Type: application/json');
        
        try {
            require_once 'engine/main/db.php';
            
            Database::execute("UPDATE security_alerts SET is_read = 1");
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    // Решить инцидент
    public function resolveIncident() {
        $this->requireAccessLevel(5);
        
        header('Content-Type: application/json');
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $alertId = $data['alert_id'] ?? null;
            $resolution = $data['resolution'] ?? '';
            
            if (!$alertId) {
                throw new Exception('Alert ID required');
            }
            
            require_once 'engine/main/db.php';
            
            Database::execute(
                "UPDATE security_alerts SET is_resolved = 1, resolved_by = ?, resolved_at = NOW() WHERE id = ?",
                [$_SESSION['admin_user_id'], $alertId]
            );
            
            // Логируем разрешение инцидента
            $this->logIncidentResolution($alertId, $resolution);
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    // Приватные методы
    
    private function getSecurityAlerts() {
        return Database::fetchAll(
            "SELECT sa.*, u.user_fio, u.user_login 
             FROM security_alerts sa 
             LEFT JOIN users u ON sa.user_id = u.user_id 
             ORDER BY sa.created_at DESC LIMIT 50"
        );
    }
    
    private function getNotificationSettings() {
        $userId = $_SESSION['admin_user_id'];
        $settings = Database::fetchOne(
            "SELECT * FROM notification_settings WHERE user_id = ?",
            [$userId]
        );
        
        if (!$settings) {
            // Создаем настройки по умолчанию
            $settings = [
                'email_enabled' => true,
                'telegram_enabled' => false,
                'telegram_chat_id' => '',
                'alert_types' => json_encode(['login_anomaly', 'geographic_anomaly', 'behavior_anomaly']),
                'daily_reports' => false,
                'weekly_reports' => false
            ];
        }
        
        return $settings;
    }
    
    private function getTelegramNotifications() {
        return Database::fetchAll(
            "SELECT * FROM telegram_notifications ORDER BY sent_at DESC LIMIT 20"
        );
    }
    
    private function getEmailNotifications() {
        return Database::fetchAll(
            "SELECT * FROM email_notifications ORDER BY sent_at DESC LIMIT 20"
        );
    }
    
    private function getTestResults() {
        return [
            'email' => $this->testEmailConnection(),
            'telegram' => $this->testTelegramConnection()
        ];
    }
    
    private function sendTestEmail() {
        $to = 'admin@example.com'; // Замените на реальный email
        $subject = 'Тестовое уведомление безопасности';
        $message = 'Это тестовое уведомление от системы безопасности. Время: ' . date('Y-m-d H:i:s');
        
        $headers = [
            'From: security@example.com',
            'Reply-To: security@example.com',
            'Content-Type: text/html; charset=UTF-8'
        ];
        
        $result = mail($to, $subject, $message, implode("\r\n", $headers));
        
        // Логируем попытку отправки
        Database::execute(
            "INSERT INTO email_notifications (recipient_email, subject, message_body, notification_type, is_sent) 
             VALUES (?, ?, ?, 'security_alert', ?)",
            [$to, $subject, $message, $result ? 1 : 0]
        );
        
        return $result;
    }
    
    private function sendTestTelegram() {
        $chatId = $this->getTelegramChatId();
        if (!$chatId) {
            return false;
        }
        
        $message = "🔔 Тестовое уведомление безопасности\n\nВремя: " . date('Y-m-d H:i:s') . "\nСистема: " . $_SERVER['HTTP_HOST'];
        
        $result = $this->sendTelegramMessage($chatId, $message);
        
        // Логируем попытку отправки
        Database::execute(
            "INSERT INTO telegram_notifications (chat_id, message_text, message_type, is_sent) 
             VALUES (?, ?, 'alert', ?)",
            [$chatId, $message, $result ? 1 : 0]
        );
        
        return $result;
    }
    
    private function sendEmailNotification($recipients, $subject, $message) {
        if (empty($recipients)) {
            return false;
        }
        
        $headers = [
            'From: security@example.com',
            'Reply-To: security@example.com',
            'Content-Type: text/html; charset=UTF-8'
        ];
        
        $success = true;
        
        foreach ($recipients as $recipient) {
            $result = mail($recipient, $subject, $message, implode("\r\n", $headers));
            
            // Логируем отправку
            Database::execute(
                "INSERT INTO email_notifications (recipient_email, subject, message_body, notification_type, is_sent) 
                 VALUES (?, ?, ?, 'security_alert', ?)",
                [$recipient, $subject, $message, $result ? 1 : 0]
            );
            
            if (!$result) {
                $success = false;
            }
        }
        
        return $success;
    }
    
    private function sendTelegramNotification($recipients, $message) {
        if (empty($recipients)) {
            return false;
        }
        
        $success = true;
        
        foreach ($recipients as $chatId) {
            $result = $this->sendTelegramMessage($chatId, $message);
            
            // Логируем отправку
            Database::execute(
                "INSERT INTO telegram_notifications (chat_id, message_text, message_type, is_sent) 
                 VALUES (?, ?, 'alert', ?)",
                [$chatId, $message, $result ? 1 : 0]
            );
            
            if (!$result) {
                $success = false;
            }
        }
        
        return $success;
    }
    
    private function sendTelegramMessage($chatId, $message) {
        $botToken = $this->getTelegramBotToken();
        if (!$botToken) {
            return false;
        }
        
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
        
        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];
        
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        return $result !== false;
    }
    
    private function getTelegramChatId() {
        $settings = $this->getNotificationSettings();
        return $settings['telegram_chat_id'] ?? '';
    }
    
    private function getTelegramBotToken() {
        // В реальной системе токен должен храниться в конфигурации
        return 'YOUR_BOT_TOKEN'; // Замените на реальный токен
    }
    
    private function testEmailConnection() {
        // Простая проверка возможности отправки email
        return function_exists('mail');
    }
    
    private function testTelegramConnection() {
        $botToken = $this->getTelegramBotToken();
        if ($botToken === 'YOUR_BOT_TOKEN') {
            return false;
        }
        
        $url = "https://api.telegram.org/bot{$botToken}/getMe";
        $result = file_get_contents($url);
        
        return $result !== false;
    }
    
    private function getSecurityAlertsAPI() {
        $alerts = $this->getSecurityAlerts();
        $unreadCount = Database::fetchOne(
            "SELECT COUNT(*) FROM security_alerts WHERE is_read = 0"
        )['COUNT(*)'] ?? 0;
        
        return [
            'alerts' => $alerts,
            'unread_count' => $unreadCount,
            'total_count' => count($alerts)
        ];
    }
    
    private function getNotificationSettingsAPI() {
        return $this->getNotificationSettings();
    }
    
    private function getNotificationHistory() {
        $emailHistory = Database::fetchAll(
            "SELECT * FROM email_notifications ORDER BY sent_at DESC LIMIT 20"
        );
        
        $telegramHistory = Database::fetchAll(
            "SELECT * FROM telegram_notifications ORDER BY sent_at DESC LIMIT 20"
        );
        
        return [
            'email' => $emailHistory,
            'telegram' => $telegramHistory
        ];
    }
    
    private function getAllNotifications() {
        return [
            'alerts' => $this->getSecurityAlertsAPI(),
            'settings' => $this->getNotificationSettingsAPI(),
            'history' => $this->getNotificationHistory()
        ];
    }
    
    private function logIncidentResolution($alertId, $resolution) {
        Database::execute(
            "INSERT INTO admin_audit_log (admin_user_id, action_type, target_type, target_id, new_values) 
             VALUES (?, 'resolve_incident', 'security_alert', ?, ?)",
            [$_SESSION['admin_user_id'], $alertId, json_encode(['resolution' => $resolution])]
        );
    }
    
    // Метод для автоматической отправки уведомлений о критических событиях
    public function sendCriticalAlert($alertType, $title, $message, $userId = null, $ipAddress = null) {
        try {
            require_once 'engine/main/db.php';
            
            // Создаем запись в базе
            Database::execute(
                "INSERT INTO security_alerts (alert_type, severity, title, message, user_id, ip_address) 
                 VALUES (?, 'critical', ?, ?, ?, ?)",
                [$alertType, $title, $message, $userId, $ipAddress]
            );
            
            // Получаем настройки уведомлений
            $settings = $this->getNotificationSettings();
            
            // Отправляем уведомления
            if ($settings['email_enabled']) {
                $this->sendEmailNotification(
                    ['admin@example.com'], // Замените на реальные email
                    "🚨 КРИТИЧЕСКОЕ УВЕДОМЛЕНИЕ: {$title}",
                    $message
                );
            }
            
            if ($settings['telegram_enabled'] && $settings['telegram_chat_id']) {
                $this->sendTelegramNotification(
                    [$settings['telegram_chat_id']],
                    "🚨 КРИТИЧЕСКОЕ УВЕДОМЛЕНИЕ\n\n{$title}\n\n{$message}"
                );
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Ошибка отправки критического уведомления: " . $e->getMessage());
            return false;
        }
    }
} 