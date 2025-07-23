<?php
require_once 'engine/security/DataEncryption.php';
require_once 'engine/main/db.php';

class SecureAnalyticsModel {
    private $encryption;
    
    public function __construct() {
        $this->encryption = DataEncryption::getInstance();
    }
    
    /**
     * Логирует активность пользователя с шифрованием чувствительных данных
     */
    public function logUserActivity($userId, $action, $description, $ip, $userAgent) {
        // Шифруем IP адрес
        $encryptedIP = $this->encryption->encryptIP($ip);
        
        // Также сохраняем анонимизированный IP для аналитики
        $anonymizedIP = $this->encryption->anonymizeIP($ip);
        
        Database::execute(
            "INSERT INTO user_activity (user_id, action_type, activity_description, ip_address, ip_address_encrypted, user_agent, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, NOW())",
            [$userId, $action, $description, $anonymizedIP, $encryptedIP, $userAgent]
        );
    }
    
    /**
     * Сохраняет информацию о геолокации с шифрованием
     */
    public function saveGeolocation($ip, $geoData) {
        $encryptedIP = $this->encryption->encryptIP($ip);
        $anonymizedIP = $this->encryption->anonymizeIP($ip);
        
        // Проверяем существует ли запись
        $existing = Database::fetchOne(
            "SELECT id FROM ip_geolocation WHERE ip_address_encrypted = ?",
            [$encryptedIP]
        );
        
        if ($existing) {
            // Обновляем существующую запись
            Database::execute(
                "UPDATE ip_geolocation SET 
                 country = ?, region = ?, city = ?, 
                 latitude = ?, longitude = ?, timezone = ?,
                 isp = ?, organization = ?, updated_at = NOW()
                 WHERE ip_address_encrypted = ?",
                [
                    $geoData['country'] ?? null,
                    $geoData['region'] ?? null,
                    $geoData['city'] ?? null,
                    $geoData['latitude'] ?? null,
                    $geoData['longitude'] ?? null,
                    $geoData['timezone'] ?? null,
                    $geoData['isp'] ?? null,
                    $geoData['organization'] ?? null,
                    $encryptedIP
                ]
            );
        } else {
            // Создаем новую запись
            Database::execute(
                "INSERT INTO ip_geolocation 
                 (ip_address, ip_address_encrypted, country, region, city, latitude, longitude, timezone, isp, organization)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $anonymizedIP,
                    $encryptedIP,
                    $geoData['country'] ?? null,
                    $geoData['region'] ?? null,
                    $geoData['city'] ?? null,
                    $geoData['latitude'] ?? null,
                    $geoData['longitude'] ?? null,
                    $geoData['timezone'] ?? null,
                    $geoData['isp'] ?? null,
                    $geoData['organization'] ?? null
                ]
            );
        }
    }
    
    /**
     * Регистрирует ML аномалию с шифрованием
     */
    public function logMLAnomaly($userId, $ip, $anomalyType, $confidence, $riskLevel, $features) {
        $encryptedIP = $this->encryption->encryptIP($ip);
        $anonymizedIP = $this->encryption->anonymizeIP($ip);
        
        Database::execute(
            "INSERT INTO ml_anomalies 
             (user_id, ip_address, ip_address_encrypted, anomaly_type, confidence_score, risk_level, features_data)
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $userId,
                $anonymizedIP,
                $encryptedIP,
                $anomalyType,
                $confidence,
                $riskLevel,
                json_encode($features)
            ]
        );
    }
    
    /**
     * Создает алерт безопасности
     */
    public function createSecurityAlert($type, $severity, $title, $message, $userId = null, $ip = null, $metadata = []) {
        $encryptedIP = $ip ? $this->encryption->encryptIP($ip) : null;
        $anonymizedIP = $ip ? $this->encryption->anonymizeIP($ip) : null;
        
        Database::execute(
            "INSERT INTO security_alerts 
             (alert_type, severity, title, message, user_id, ip_address, ip_address_encrypted, metadata)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $type,
                $severity,
                $title,
                $message,
                $userId,
                $anonymizedIP,
                $encryptedIP,
                json_encode($metadata)
            ]
        );
        
        // Если это критический алерт, отправляем уведомление
        if ($severity === 'critical') {
            $this->sendCriticalAlertNotification($title, $message);
        }
    }
    
    /**
     * Получает расшифрованные данные для администратора
     */
    public function getDecryptedActivityForAdmin($limit = 100) {
        $activities = Database::fetchAll(
            "SELECT ua.*, u.user_login, u.user_fio 
             FROM user_activity ua
             LEFT JOIN users u ON ua.user_id = u.user_id
             ORDER BY ua.created_at DESC
             LIMIT ?",
            [$limit]
        );
        
        // Расшифровываем IP адреса для админа
        foreach ($activities as &$activity) {
            if (!empty($activity['ip_address_encrypted'])) {
                $activity['real_ip'] = $this->encryption->decryptIP($activity['ip_address_encrypted']);
            }
        }
        
        return $activities;
    }
    
    /**
     * Анализирует поведение пользователя
     */
    public function analyzeUserBehavior($userId, $sessionId, $pageData) {
        // Шифруем чувствительные данные
        $encryptedSearchQueries = !empty($pageData['search_queries']) 
            ? $this->encryption->encrypt(json_encode($pageData['search_queries']))
            : null;
        
        Database::execute(
            "INSERT INTO user_behavior 
             (user_id, session_id, page_url, page_title, time_spent, scroll_depth, 
              clicks_count, form_interactions, file_downloads, search_queries_encrypted,
              user_agent, screen_resolution, referrer, session_duration)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $userId,
                $sessionId,
                $pageData['url'] ?? '',
                $pageData['title'] ?? '',
                $pageData['time_spent'] ?? 0,
                $pageData['scroll_depth'] ?? 0,
                $pageData['clicks'] ?? 0,
                $pageData['form_interactions'] ?? 0,
                $pageData['file_downloads'] ?? 0,
                $encryptedSearchQueries,
                $pageData['user_agent'] ?? '',
                $pageData['screen_resolution'] ?? '',
                $pageData['referrer'] ?? '',
                $pageData['session_duration'] ?? 0
            ]
        );
    }
    
    /**
     * Отправляет критическое уведомление
     */
    private function sendCriticalAlertNotification($title, $message) {
        // Отправка email уведомления
        $this->queueEmailNotification(
            'security_alert',
            'Критический алерт безопасности: ' . $title,
            $message
        );
        
        // Отправка Telegram уведомления
        $this->queueTelegramNotification('alert', "🚨 КРИТИЧЕСКИЙ АЛЕРТ\n\n$title\n\n$message");
    }
    
    /**
     * Добавляет email уведомление в очередь
     */
    private function queueEmailNotification($type, $subject, $body) {
        Database::execute(
            "INSERT INTO email_notifications (recipient_email, subject, message_body, notification_type)
             VALUES ((SELECT value FROM settings WHERE name = 'admin_email'), ?, ?, ?)",
            [$subject, $body, $type]
        );
    }
    
    /**
     * Добавляет Telegram уведомление в очередь
     */
    private function queueTelegramNotification($type, $message) {
        Database::execute(
            "INSERT INTO telegram_notifications (chat_id, message_text, message_type)
             VALUES ((SELECT value FROM settings WHERE name = 'telegram_chat_id'), ?, ?)",
            [$message, $type]
        );
    }
    
    /**
     * Очищает старые данные согласно политике хранения
     */
    public function cleanupOldData($daysToKeep = 90) {
        // Удаляем старые логи активности
        Database::execute(
            "DELETE FROM user_activity WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)",
            [$daysToKeep]
        );
        
        // Удаляем старые данные поведения
        Database::execute(
            "DELETE FROM user_behavior WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)",
            [$daysToKeep]
        );
        
        // Архивируем старые алерты
        $oldAlerts = Database::fetchAll(
            "SELECT * FROM security_alerts WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)",
            [$daysToKeep]
        );
        
        if (!empty($oldAlerts)) {
            // Сохраняем в архивный файл
            $archiveData = json_encode($oldAlerts, JSON_PRETTY_PRINT);
            $archiveFile = __DIR__ . '/../../archives/alerts_' . date('Y-m-d') . '.json';
            
            $dir = dirname($archiveFile);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            file_put_contents($archiveFile, $archiveData);
            
            // Удаляем из БД
            Database::execute(
                "DELETE FROM security_alerts WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)",
                [$daysToKeep]
            );
        }
    }
} 