<?php

/**
 * Сервис уведомлений
 * Обеспечивает отправку уведомлений через различные каналы
 */
class NotificationService {
    
    private $channels = [];
    private $templates = [];
    private $settings = [];
    
    public function __construct() {
        $this->loadSettings();
        $this->loadTemplates();
        $this->initializeChannels();
    }
    
    /**
     * Отправляет уведомление
     */
    public function send($type, $recipients, $data = [], $channels = null) {
        try {
            $template = $this->getTemplate($type);
            if (!$template) {
                throw new Exception("Template not found: $type");
            }
            
            $channels = $channels ?: $template['channels'];
            $results = [];
            
            foreach ($channels as $channel) {
                if ($this->isChannelEnabled($channel)) {
                    $result = $this->sendToChannel($channel, $recipients, $template, $data);
                    $results[$channel] = $result;
                }
            }
            
            // Логируем отправку
            $this->logNotification($type, $recipients, $data, $results);
            
            return [
                'success' => true,
                'results' => $results
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Отправляет уведомление в конкретный канал
     */
    private function sendToChannel($channel, $recipients, $template, $data) {
        try {
            switch ($channel) {
                case 'email':
                    return $this->sendEmail($recipients, $template, $data);
                case 'sms':
                    return $this->sendSMS($recipients, $template, $data);
                case 'telegram':
                    return $this->sendTelegram($recipients, $template, $data);
                case 'slack':
                    return $this->sendSlack($recipients, $template, $data);
                case 'discord':
                    return $this->sendDiscord($recipients, $template, $data);
                case 'push':
                    return $this->sendPush($recipients, $template, $data);
                case 'in_app':
                    return $this->sendInApp($recipients, $template, $data);
                default:
                    throw new Exception("Unknown channel: $channel");
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Отправляет email уведомление
     */
    private function sendEmail($recipients, $template, $data) {
        try {
            $subject = $this->renderTemplate($template['email']['subject'], $data);
            $body = $this->renderTemplate($template['email']['body'], $data);
            
            $headers = [
                'From: ' . $this->settings['email']['from'],
                'Reply-To: ' . $this->settings['email']['reply_to'],
                'Content-Type: text/html; charset=UTF-8'
            ];
            
            $success = 0;
            $failed = 0;
            
            foreach ($recipients as $recipient) {
                if (mail($recipient, $subject, $body, implode("\r\n", $headers))) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            
            return [
                'success' => true,
                'sent' => $success,
                'failed' => $failed
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Отправляет SMS уведомление
     */
    private function sendSMS($recipients, $template, $data) {
        try {
            $message = $this->renderTemplate($template['sms']['message'], $data);
            
            // Здесь должна быть интеграция с SMS сервисом
            // Например, Twilio, SMS.ru и т.д.
            
            return [
                'success' => true,
                'message' => 'SMS sending not implemented yet'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Отправляет Telegram уведомление
     */
    private function sendTelegram($recipients, $template, $data) {
        try {
            $message = $this->renderTemplate($template['telegram']['message'], $data);
            $botToken = $this->settings['telegram']['bot_token'];
            
            $success = 0;
            $failed = 0;
            
            foreach ($recipients as $chatId) {
                $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
                $postData = [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'HTML'
                ];
                
                $response = $this->makeHttpRequest($url, $postData);
                $result = json_decode($response, true);
                
                if ($result && $result['ok']) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            
            return [
                'success' => true,
                'sent' => $success,
                'failed' => $failed
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Отправляет Slack уведомление
     */
    private function sendSlack($recipients, $template, $data) {
        try {
            $message = $this->renderTemplate($template['slack']['message'], $data);
            $webhookUrl = $this->settings['slack']['webhook_url'];
            
            $payload = [
                'text' => $message,
                'username' => $this->settings['slack']['username'],
                'icon_emoji' => $this->settings['slack']['icon_emoji']
            ];
            
            $response = $this->makeHttpRequest($webhookUrl, json_encode($payload), 'POST', [
                'Content-Type: application/json'
            ]);
            
            return [
                'success' => $response !== false,
                'response' => $response
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Отправляет Discord уведомление
     */
    private function sendDiscord($recipients, $template, $data) {
        try {
            $message = $this->renderTemplate($template['discord']['message'], $data);
            $webhookUrl = $this->settings['discord']['webhook_url'];
            
            $payload = [
                'content' => $message,
                'username' => $this->settings['discord']['username']
            ];
            
            $response = $this->makeHttpRequest($webhookUrl, json_encode($payload), 'POST', [
                'Content-Type: application/json'
            ]);
            
            return [
                'success' => $response !== false,
                'response' => $response
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Отправляет push уведомление
     */
    private function sendPush($recipients, $template, $data) {
        try {
            $title = $this->renderTemplate($template['push']['title'], $data);
            $body = $this->renderTemplate($template['push']['body'], $data);
            
            // Здесь должна быть интеграция с push сервисом
            // Например, Firebase Cloud Messaging
            
            return [
                'success' => true,
                'message' => 'Push notifications not implemented yet'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Отправляет внутриприложенное уведомление
     */
    private function sendInApp($recipients, $template, $data) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $title = $this->renderTemplate($template['in_app']['title'], $data);
            $message = $this->renderTemplate($template['in_app']['message'], $data);
            
            $success = 0;
            $failed = 0;
            
            foreach ($recipients as $userId) {
                try {
                    Database::execute(
                        "INSERT INTO notifications (user_id, title, message, type, data, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
                        [
                            $userId,
                            $title,
                            $message,
                            $template['type'],
                            json_encode($data)
                        ]
                    );
                    $success++;
                } catch (Exception $e) {
                    $failed++;
                }
            }
            
            return [
                'success' => true,
                'sent' => $success,
                'failed' => $failed
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Рендерит шаблон
     */
    private function renderTemplate($template, $data) {
        foreach ($data as $key => $value) {
            $template = str_replace("{{$key}}", $value, $template);
        }
        
        return $template;
    }
    
    /**
     * Получает шаблон по типу
     */
    private function getTemplate($type) {
        return $this->templates[$type] ?? null;
    }
    
    /**
     * Проверяет включен ли канал
     */
    private function isChannelEnabled($channel) {
        return isset($this->settings['channels'][$channel]) && $this->settings['channels'][$channel]['enabled'];
    }
    
    /**
     * Выполняет HTTP запрос
     */
    private function makeHttpRequest($url, $data, $method = 'POST', $headers = []) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        
        return $httpCode === 200 ? $response : false;
    }
    
    /**
     * Логирует уведомление
     */
    private function logNotification($type, $recipients, $data, $results) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            Database::execute(
                "INSERT INTO notification_logs (type, recipients, data, results, sent_at) VALUES (?, ?, ?, ?, NOW())",
                [
                    $type,
                    json_encode($recipients),
                    json_encode($data),
                    json_encode($results)
                ]
            );
        } catch (Exception $e) {
            // Игнорируем ошибки логирования
        }
    }
    
    /**
     * Загружает настройки
     */
    private function loadSettings() {
        $settingsFile = 'config/notifications.json';
        
        if (file_exists($settingsFile)) {
            $this->settings = json_decode(file_get_contents($settingsFile), true);
        } else {
            $this->settings = $this->getDefaultSettings();
            file_put_contents($settingsFile, json_encode($this->settings, JSON_PRETTY_PRINT));
        }
    }
    
    /**
     * Загружает шаблоны
     */
    private function loadTemplates() {
        $templatesFile = 'config/notification_templates.json';
        
        if (file_exists($templatesFile)) {
            $this->templates = json_decode(file_get_contents($templatesFile), true);
        } else {
            $this->templates = $this->getDefaultTemplates();
            file_put_contents($templatesFile, json_encode($this->templates, JSON_PRETTY_PRINT));
        }
    }
    
    /**
     * Инициализирует каналы
     */
    private function initializeChannels() {
        // Здесь можно инициализировать различные каналы
        // Например, подключение к внешним сервисам
    }
    
    /**
     * Получает настройки по умолчанию
     */
    private function getDefaultSettings() {
        return [
            'channels' => [
                'email' => ['enabled' => true],
                'sms' => ['enabled' => false],
                'telegram' => ['enabled' => false],
                'slack' => ['enabled' => false],
                'discord' => ['enabled' => false],
                'push' => ['enabled' => false],
                'in_app' => ['enabled' => true]
            ],
            'email' => [
                'from' => 'noreply@example.com',
                'reply_to' => 'support@example.com'
            ],
            'telegram' => [
                'bot_token' => ''
            ],
            'slack' => [
                'webhook_url' => '',
                'username' => 'Admin Bot',
                'icon_emoji' => ':robot_face:'
            ],
            'discord' => [
                'webhook_url' => '',
                'username' => 'Admin Bot'
            ]
        ];
    }
    
    /**
     * Получает шаблоны по умолчанию
     */
    private function getDefaultTemplates() {
        return [
            'user_registered' => [
                'type' => 'user_registered',
                'channels' => ['email', 'in_app'],
                'email' => [
                    'subject' => 'Добро пожаловать!',
                    'body' => '<h1>Добро пожаловать, {{name}}!</h1><p>Ваш аккаунт успешно создан.</p>'
                ],
                'in_app' => [
                    'title' => 'Новый пользователь',
                    'message' => 'Зарегистрирован новый пользователь: {{name}}'
                ]
            ],
            'login_attempt' => [
                'type' => 'security',
                'channels' => ['email', 'telegram'],
                'email' => [
                    'subject' => 'Попытка входа',
                    'body' => '<p>Обнаружена попытка входа в аккаунт {{username}} с IP {{ip}}.</p>'
                ],
                'telegram' => [
                    'message' => '🔐 Попытка входа\nПользователь: {{username}}\nIP: {{ip}}\nВремя: {{time}}'
                ]
            ],
            'backup_created' => [
                'type' => 'system',
                'channels' => ['email', 'slack'],
                'email' => [
                    'subject' => 'Резервная копия создана',
                    'body' => '<p>Создана резервная копия системы: {{backup_name}}</p>'
                ],
                'slack' => [
                    'message' => '💾 Создана резервная копия: {{backup_name}}'
                ]
            ],
            'error_alert' => [
                'type' => 'error',
                'channels' => ['email', 'telegram', 'discord'],
                'email' => [
                    'subject' => 'Ошибка системы',
                    'body' => '<h2>Обнаружена ошибка:</h2><p>{{error_message}}</p>'
                ],
                'telegram' => [
                    'message' => '🚨 Ошибка системы\n{{error_message}}\nВремя: {{time}}'
                ],
                'discord' => [
                    'message' => '🚨 **Ошибка системы**\n{{error_message}}\nВремя: {{time}}'
                ]
            ]
        ];
    }
    
    /**
     * Получает уведомления пользователя
     */
    public function getUserNotifications($userId, $page = 1, $perPage = 20) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $offset = ($page - 1) * $perPage;
            
            $notifications = Database::fetchAll(
                "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?",
                [$userId, $perPage, $offset]
            );
            
            $total = Database::fetchOne(
                "SELECT COUNT(*) as count FROM notifications WHERE user_id = ?",
                [$userId]
            );
            
            return [
                'notifications' => $notifications,
                'total' => $total['count'] ?? 0,
                'page' => $page,
                'per_page' => $perPage
            ];
            
        } catch (Exception $e) {
            return [
                'notifications' => [],
                'total' => 0,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Отмечает уведомление как прочитанное
     */
    public function markAsRead($notificationId, $userId) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            Database::execute(
                "UPDATE notifications SET read_at = NOW() WHERE id = ? AND user_id = ?",
                [$notificationId, $userId]
            );
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Отмечает все уведомления как прочитанные
     */
    public function markAllAsRead($userId) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            Database::execute(
                "UPDATE notifications SET read_at = NOW() WHERE user_id = ? AND read_at IS NULL",
                [$userId]
            );
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
} 