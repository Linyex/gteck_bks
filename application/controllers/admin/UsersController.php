<?php

require_once 'application/controllers/admin/BaseAdminController.php';

class UsersController extends BaseAdminController {
    
    public function __construct() {
        parent::__construct();
        require_once 'engine/main/encryption.php';
    }
    
    public function index() {
        $this->requireAccessLevel(5); // Только модераторы и выше
        
        try {
            require_once 'engine/main/db.php';
            
            // Получаем список пользователей с пагинацией
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 20;
            $offset = ($page - 1) * $limit;
            
            $users = Database::fetchAll("SELECT * FROM users ORDER BY user_date_reg DESC LIMIT ? OFFSET ?", [$limit, $offset]);
            $totalUsers = Database::fetchOne("SELECT COUNT(*) as count FROM users")['count'];
            $totalPages = ceil($totalUsers / $limit);
            
            // Получаем статистику безопасности
            $securityStats = $this->getSecurityStats();
            
            // Получаем последние подозрительные активности
            $suspiciousActivities = $this->getSuspiciousActivities();
            
            return $this->render('admin/users/index', [
                'title' => 'Управление пользователями',
                'users' => $users,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalUsers' => $totalUsers,
                'securityStats' => $securityStats,
                'suspiciousActivities' => $suspiciousActivities
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке пользователей: ' . $e->getMessage()
            ]);
        }
    }
    
    public function view($id) {
        $this->requireAccessLevel(5);
        
        try {
            require_once 'engine/main/db.php';
            
            $user = Database::fetchOne("SELECT * FROM users WHERE user_id = ?", [$id]);
            
            if (!$user) {
                return $this->render('admin/error/404', [
                    'title' => 'Пользователь не найден',
                    'message' => 'Пользователь с ID ' . $id . ' не найден'
                ]);
            }
            
            // Получаем активность пользователя
            $userActivity = $this->getUserActivity($id);
            
            // Получаем сессии пользователя
            $userSessions = $this->getUserSessions($id);
            
            // Получаем подозрительные действия пользователя
            $suspiciousActions = $this->getUserSuspiciousActions($id);
            
            // Анализ безопасности аккаунта
            $securityAnalysis = $this->analyzeUserSecurity($id);
            
            return $this->render('admin/users/view', [
                'title' => 'Детали пользователя',
                'user' => $user,
                'userActivity' => $userActivity,
                'userSessions' => $userSessions,
                'suspiciousActions' => $suspiciousActions,
                'securityAnalysis' => $securityAnalysis
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке пользователя: ' . $e->getMessage()
            ]);
        }
    }
    
    public function edit($id) {
        $this->requireAccessLevel(10); // Только администраторы
        
        try {
            require_once 'engine/main/db.php';
            
            $user = Database::fetchOne("SELECT * FROM users WHERE user_id = ?", [$id]);
            
            if (!$user) {
                return $this->render('admin/error/404', [
                    'title' => 'Пользователь не найден',
                    'message' => 'Пользователь с ID ' . $id . ' не найден'
                ]);
            }
            
            return $this->render('admin/users/edit', [
                'title' => 'Редактирование пользователя',
                'user' => $user
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке пользователя: ' . $e->getMessage()
            ]);
        }
    }
    
    public function update($id) {
        $this->requireAccessLevel(10);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/users');
        }
        
        $fio = $this->getPost('fio');
        $status = (int)$this->getPost('status', 1);
        $accessLevel = (int)$this->getPost('access_level', 1);
        $password = $this->getPost('password');
        $blockReason = $this->getPost('block_reason', '');
        $blockUntil = $this->getPost('block_until', '');
        
        // Валидация
        $errors = [];
        
        if (empty($fio)) {
            $errors[] = 'ФИО обязательно';
        }
        
        if (!empty($password)) {
            // Проверяем силу пароля
            $passwordStrength = Encryption::checkPasswordStrength($password);
            if ($passwordStrength['strength'] === 'weak') {
                $errors[] = 'Пароль слишком слабый: ' . implode(', ', $passwordStrength['feedback']);
            }
        }
        
        try {
            require_once 'engine/main/db.php';
            
            // Проверяем, существует ли пользователь
            $user = Database::fetchOne("SELECT * FROM users WHERE user_id = ?", [$id]);
            if (!$user) {
                return $this->render('admin/error/404', [
                    'title' => 'Пользователь не найден',
                    'message' => 'Пользователь с ID ' . $id . ' не найден'
                ]);
            }
            
            if (empty($errors)) {
                // Шифруем причину блокировки
                $encryptedBlockReason = null;
                if (!empty($blockReason)) {
                    $encryptedBlockReason = Encryption::encryptForDatabase($blockReason);
                }
                
                // Обновляем пользователя
                $sql = "UPDATE users SET user_fio = ?, user_status = ?, user_access_level = ?, user_block_reason = ?, user_block_until = ?";
                $params = [$fio, $status, $accessLevel, $encryptedBlockReason, $blockUntil ?: null];
                
                // Если указан новый пароль, хешируем его
                if (!empty($password)) {
                    $passwordHash = Encryption::hashPassword($password);
                    $sql .= ", user_password = ?";
                    $params[] = $passwordHash;
                }
                
                $sql .= " WHERE user_id = ?";
                $params[] = $id;
                
                Database::execute($sql, $params);
                
                // Логируем действие администратора
                $this->logAdminAction('update_user', $id, [
                    'admin_id' => $_SESSION['admin_user_id'],
                    'changes' => [
                        'fio' => $fio,
                        'status' => $status,
                        'access_level' => $accessLevel,
                        'block_reason' => $blockReason,
                        'block_until' => $blockUntil
                    ]
                ]);
                
                return $this->redirect('/admin/users');
            }
        } catch (Exception $e) {
            $errors[] = 'Ошибка при обновлении пользователя: ' . $e->getMessage();
        }
        
        return $this->render('admin/users/edit', [
            'title' => 'Редактирование пользователя',
            'user' => $user,
            'errors' => $errors
        ]);
    }
    
    public function block($id) {
        $this->requireAccessLevel(10);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/users');
        }
        
        $reason = $this->getPost('reason', '');
        $duration = $this->getPost('duration', 'permanent'); // permanent, temporary
        $blockUntil = $this->getPost('block_until', '');
        
        try {
            require_once 'engine/main/db.php';
            
            $blockUntilDate = null;
            if ($duration === 'temporary' && !empty($blockUntil)) {
                $blockUntilDate = $blockUntil;
            }
            
            // Шифруем причину блокировки
            $encryptedReason = null;
            if (!empty($reason)) {
                $encryptedReason = Encryption::encryptForDatabase($reason);
            }
            
            Database::execute(
                "UPDATE users SET user_status = 0, user_block_reason = ?, user_block_until = ? WHERE user_id = ?",
                [$encryptedReason, $blockUntilDate, $id]
            );
            
            // Логируем блокировку
            $this->logAdminAction('block_user', $id, [
                'admin_id' => $_SESSION['admin_user_id'],
                'reason' => $reason,
                'duration' => $duration,
                'block_until' => $blockUntilDate
            ]);
            
            return $this->redirect('/admin/users');
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при блокировке пользователя: ' . $e->getMessage()
            ]);
        }
    }
    
    public function unblock($id) {
        $this->requireAccessLevel(10);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/users');
        }
        
        try {
            require_once 'engine/main/db.php';
            
            Database::execute(
                "UPDATE users SET user_status = 1, user_block_reason = NULL, user_block_until = NULL WHERE user_id = ?",
                [$id]
            );
            
            // Логируем разблокировку
            $this->logAdminAction('unblock_user', $id, [
                'admin_id' => $_SESSION['admin_user_id']
            ]);
            
            return $this->redirect('/admin/users');
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при разблокировке пользователя: ' . $e->getMessage()
            ]);
        }
    }
    
    public function delete($id) {
        $this->requireAccessLevel(10);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/users');
        }
        
        try {
            require_once 'engine/main/db.php';
            
            // Проверяем, не пытается ли пользователь удалить сам себя
            if ($id == $_SESSION['admin_user_id']) {
                return $this->render('admin/error/error', [
                    'title' => 'Ошибка',
                    'message' => 'Вы не можете удалить свой собственный аккаунт'
                ]);
            }
            
            // Удаляем пользователя
            Database::execute("DELETE FROM users WHERE user_id = ?", [$id]);
            
            // Логируем удаление
            $this->logAdminAction('delete_user', $id, [
                'admin_id' => $_SESSION['admin_user_id']
            ]);
            
            return $this->redirect('/admin/users');
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при удалении пользователя: ' . $e->getMessage()
            ]);
        }
    }
    
    public function massAction() {
        $this->requireAccessLevel(10); // Только администраторы
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/users');
        }
        
        $action = $this->getPost('action');
        $userIds = $this->getPost('user_ids', []);
        
        if (empty($userIds) || !is_array($userIds)) {
            return $this->redirect('/admin/users');
        }
        
        try {
            require_once 'engine/main/db.php';
            
            $successCount = 0;
            $errors = [];
            
            switch ($action) {
                case 'block':
                    foreach ($userIds as $userId) {
                        try {
                            Database::execute(
                                "UPDATE users SET user_status = 0, user_block_reason = ?, user_block_until = ? WHERE user_id = ? AND user_id != ?",
                                [Encryption::encryptForDatabase('Массовая блокировка'), null, $userId, $_SESSION['admin_user_id']]
                            );
                            $successCount++;
                            $this->logAdminAction('mass_block_user', $userId, [
                                'admin_id' => $_SESSION['admin_user_id'],
                                'reason' => 'Массовая блокировка'
                            ]);
                        } catch (Exception $e) {
                            $errors[] = "Ошибка блокировки пользователя ID {$userId}: " . $e->getMessage();
                        }
                    }
                    break;
                    
                case 'unblock':
                    foreach ($userIds as $userId) {
                        try {
                            Database::execute(
                                "UPDATE users SET user_status = 1, user_block_reason = NULL, user_block_until = NULL WHERE user_id = ?",
                                [$userId]
                            );
                            $successCount++;
                            $this->logAdminAction('mass_unblock_user', $userId, [
                                'admin_id' => $_SESSION['admin_user_id']
                            ]);
                        } catch (Exception $e) {
                            $errors[] = "Ошибка разблокировки пользователя ID {$userId}: " . $e->getMessage();
                        }
                    }
                    break;
                    
                case 'delete':
                    foreach ($userIds as $userId) {
                        if ($userId == $_SESSION['admin_user_id']) {
                            $errors[] = "Нельзя удалить свой аккаунт";
                            continue;
                        }
                        try {
                            Database::execute("DELETE FROM users WHERE user_id = ?", [$userId]);
                            $successCount++;
                            $this->logAdminAction('mass_delete_user', $userId, [
                                'admin_id' => $_SESSION['admin_user_id']
                            ]);
                        } catch (Exception $e) {
                            $errors[] = "Ошибка удаления пользователя ID {$userId}: " . $e->getMessage();
                        }
                    }
                    break;
                    
                case 'change_access':
                    $newAccessLevel = (int)$this->getPost('new_access_level', 1);
                    foreach ($userIds as $userId) {
                        try {
                            Database::execute(
                                "UPDATE users SET user_access_level = ? WHERE user_id = ?",
                                [$newAccessLevel, $userId]
                            );
                            $successCount++;
                            $this->logAdminAction('mass_change_access', $userId, [
                                'admin_id' => $_SESSION['admin_user_id'],
                                'new_access_level' => $newAccessLevel
                            ]);
                        } catch (Exception $e) {
                            $errors[] = "Ошибка изменения уровня доступа пользователя ID {$userId}: " . $e->getMessage();
                        }
                    }
                    break;
                    
                case 'force_logout':
                    foreach ($userIds as $userId) {
                        try {
                            // Удаляем все сессии пользователя
                            Database::execute("DELETE FROM user_sessions WHERE user_id = ?", [$userId]);
                            $successCount++;
                            $this->logAdminAction('mass_force_logout', $userId, [
                                'admin_id' => $_SESSION['admin_user_id']
                            ]);
                        } catch (Exception $e) {
                            $errors[] = "Ошибка принудительного выхода пользователя ID {$userId}: " . $e->getMessage();
                        }
                    }
                    break;
                    
                default:
                    return $this->redirect('/admin/users');
            }
            
            // Сохраняем результат в сессии для отображения
            $_SESSION['mass_action_result'] = [
                'success_count' => $successCount,
                'errors' => $errors,
                'action' => $action
            ];
            
        } catch (Exception $e) {
            $_SESSION['mass_action_result'] = [
                'success_count' => 0,
                'errors' => ['Общая ошибка: ' . $e->getMessage()],
                'action' => $action
            ];
        }
        
        return $this->redirect('/admin/users');
    }
    
    public function resetPassword($id) {
        $this->requireAccessLevel(10);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/users');
        }
        
        try {
            require_once 'engine/main/db.php';
            
            $user = Database::fetchOne("SELECT * FROM users WHERE user_id = ?", [$id]);
            if (!$user) {
                return $this->render('admin/error/404', [
                    'title' => 'Пользователь не найден',
                    'message' => 'Пользователь с ID ' . $id . ' не найден'
                ]);
            }
            
            // Генерируем новый пароль
            $newPassword = $this->generateSecurePassword();
            $passwordHash = Encryption::hashPassword($newPassword);
            
            // Обновляем пароль
            Database::execute(
                "UPDATE users SET user_password = ?, user_force_password_change = 1 WHERE user_id = ?",
                [$passwordHash, $id]
            );
            
            // Логируем действие
            $this->logAdminAction('reset_password', $id, [
                'admin_id' => $_SESSION['admin_user_id']
            ]);
            
            // Возвращаем новый пароль для отображения администратору
            $_SESSION['password_reset_result'] = [
                'user_id' => $id,
                'user_login' => $user['user_login'],
                'new_password' => $newPassword
            ];
            
        } catch (Exception $e) {
            $_SESSION['password_reset_error'] = 'Ошибка сброса пароля: ' . $e->getMessage();
        }
        
        return $this->redirect('/admin/users');
    }
    
    public function forcePasswordChange($id) {
        $this->requireAccessLevel(10);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/users');
        }
        
        try {
            require_once 'engine/main/db.php';
            
            Database::execute(
                "UPDATE users SET user_force_password_change = 1 WHERE user_id = ?",
                [$id]
            );
            
            $this->logAdminAction('force_password_change', $id, [
                'admin_id' => $_SESSION['admin_user_id']
            ]);
            
            $_SESSION['success_message'] = 'Пользователь будет вынужден сменить пароль при следующем входе';
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Ошибка: ' . $e->getMessage();
        }
        
        return $this->redirect('/admin/users');
    }
    
    public function terminateSessions($id) {
        $this->requireAccessLevel(10);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/users');
        }
        
        try {
            require_once 'engine/main/db.php';
            
            // Удаляем все сессии пользователя
            Database::execute("DELETE FROM user_sessions WHERE user_id = ?", [$id]);
            
            $this->logAdminAction('terminate_sessions', $id, [
                'admin_id' => $_SESSION['admin_user_id']
            ]);
            
            $_SESSION['success_message'] = 'Все сессии пользователя завершены';
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Ошибка: ' . $e->getMessage();
        }
        
        return $this->redirect('/admin/users');
    }
    
    private function generateSecurePassword($length = 12) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        $password = '';
        
        // Гарантируем наличие разных типов символов
        $password .= chr(rand(65, 90)); // Заглавная буква
        $password .= chr(rand(97, 122)); // Строчная буква
        $password .= rand(0, 9); // Цифра
        $password .= '!@#$%^&*()'[rand(0, 9)]; // Специальный символ
        
        // Добавляем остальные символы
        for ($i = 4; $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }
        
        // Перемешиваем пароль
        return str_shuffle($password);
    }
    
    // Приватные методы для анализа безопасности
    
    private function getSecurityStats() {
        try {
            require_once 'engine/main/db.php';
            
            $stats = [];
            
            // Количество заблокированных пользователей
            $blockedUsers = Database::fetchOne("SELECT COUNT(*) as count FROM users WHERE user_status = 0");
            $stats['blocked_users'] = $blockedUsers['count'] ?? 0;
            
            // Количество подозрительных входов за последние 24 часа
            $suspiciousLogins = Database::fetchOne("SELECT COUNT(*) as count FROM user_sessions WHERE suspicious = 1 AND login_time > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
            $stats['suspicious_logins_24h'] = $suspiciousLogins['count'] ?? 0;
            
            // Количество неудачных попыток входа за последние 24 часа
            $failedLogins = Database::fetchOne("SELECT COUNT(*) as count FROM login_attempts WHERE success = 0 AND attempt_time > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
            $stats['failed_logins_24h'] = $failedLogins['count'] ?? 0;
            
            // Количество активных сессий
            $activeSessions = Database::fetchOne("SELECT COUNT(*) as count FROM user_sessions WHERE logout_time IS NULL");
            $stats['active_sessions'] = $activeSessions['count'] ?? 0;
            
            return $stats;
        } catch (Exception $e) {
            return [
                'blocked_users' => 0,
                'suspicious_logins_24h' => 0,
                'failed_logins_24h' => 0,
                'active_sessions' => 0
            ];
        }
    }
    
    private function getSuspiciousActivities() {
        try {
            require_once 'engine/main/db.php';
            
            $activities = [];
            
            // Подозрительные входы
            $suspiciousLogins = Database::fetchAll("
                SELECT us.*, u.user_login, u.user_fio 
                FROM user_sessions us 
                JOIN users u ON us.user_id = u.user_id 
                WHERE us.suspicious = 1 
                ORDER BY us.login_time DESC 
                LIMIT 10
            ");
            
            foreach ($suspiciousLogins as $login) {
                $activities[] = [
                    'type' => 'suspicious_login',
                    'user' => $login['user_fio'] . ' (' . $login['user_login'] . ')',
                    'description' => 'Подозрительный вход с IP: ' . $login['ip_address'],
                    'time' => $login['login_time'],
                    'severity' => 'high'
                ];
            }
            
            // Множественные неудачные попытки входа
            $failedAttempts = Database::fetchAll("
                SELECT la.*, u.user_login, u.user_fio 
                FROM login_attempts la 
                JOIN users u ON la.user_id = u.user_id 
                WHERE la.success = 0 
                AND la.attempt_time > DATE_SUB(NOW(), INTERVAL 1 HOUR)
                GROUP BY la.user_id, la.ip_address 
                HAVING COUNT(*) > 5
            ");
            
            foreach ($failedAttempts as $attempt) {
                $activities[] = [
                    'type' => 'multiple_failed_logins',
                    'user' => $attempt['user_fio'] . ' (' . $attempt['user_login'] . ')',
                    'description' => 'Множественные неудачные попытки входа с IP: ' . $attempt['ip_address'],
                    'time' => $attempt['attempt_time'],
                    'severity' => 'medium'
                ];
            }
            
            return $activities;
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getUserActivity($userId) {
        try {
            require_once 'engine/main/db.php';
            
            return Database::fetchAll("
                SELECT * FROM user_activity 
                WHERE user_id = ? 
                ORDER BY activity_time DESC 
                LIMIT 50
            ", [$userId]);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getUserSessions($userId) {
        try {
            require_once 'engine/main/db.php';
            
            return Database::fetchAll("
                SELECT * FROM user_sessions 
                WHERE user_id = ? 
                ORDER BY login_time DESC 
                LIMIT 20
            ", [$userId]);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getUserSuspiciousActions($userId) {
        try {
            require_once 'engine/main/db.php';
            
            return Database::fetchAll("
                SELECT * FROM user_activity 
                WHERE user_id = ? AND suspicious = 1 
                ORDER BY activity_time DESC 
                LIMIT 10
            ", [$userId]);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function analyzeUserSecurity($userId) {
        try {
            require_once 'engine/main/db.php';
            
            $analysis = [
                'risk_level' => 'low',
                'issues' => [],
                'recommendations' => []
            ];
            
            // Проверяем последние входы
            $recentSessions = Database::fetchAll("
                SELECT * FROM user_sessions 
                WHERE user_id = ? 
                ORDER BY login_time DESC 
                LIMIT 10
            ", [$userId]);
            
            $uniqueIPs = array_unique(array_column($recentSessions, 'ip_address'));
            if (count($uniqueIPs) > 3) {
                $analysis['issues'][] = 'Множественные IP адреса для входа';
                $analysis['risk_level'] = 'medium';
            }
            
            // Проверяем неудачные попытки входа
            $failedAttempts = Database::fetchOne("
                SELECT COUNT(*) as count FROM login_attempts 
                WHERE user_id = ? AND success = 0 
                AND attempt_time > DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ", [$userId]);
            
            if (($failedAttempts['count'] ?? 0) > 10) {
                $analysis['issues'][] = 'Множественные неудачные попытки входа';
                $analysis['risk_level'] = 'high';
            }
            
            // Проверяем подозрительные действия
            $suspiciousActions = Database::fetchOne("
                SELECT COUNT(*) as count FROM user_activity 
                WHERE user_id = ? AND suspicious = 1 
                AND activity_time > DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ", [$userId]);
            
            if (($suspiciousActions['count'] ?? 0) > 5) {
                $analysis['issues'][] = 'Подозрительная активность';
                $analysis['risk_level'] = 'high';
            }
            
            // Рекомендации
            if ($analysis['risk_level'] === 'high') {
                $analysis['recommendations'][] = 'Рекомендуется временно заблокировать аккаунт';
                $analysis['recommendations'][] = 'Требуется смена пароля';
            } elseif ($analysis['risk_level'] === 'medium') {
                $analysis['recommendations'][] = 'Рекомендуется мониторинг активности';
                $analysis['recommendations'][] = 'Проверить подлинность входов';
            }
            
            return $analysis;
        } catch (Exception $e) {
            return [
                'risk_level' => 'unknown',
                'issues' => ['Ошибка анализа'],
                'recommendations' => []
            ];
        }
    }
    
    private function logAdminAction($action, $userId, $data = []) {
        try {
            require_once 'engine/main/db.php';
            
            // Шифруем данные действия
            $encryptedData = Encryption::encryptForDatabase(json_encode($data));
            
            Database::execute("
                INSERT INTO admin_actions (admin_id, action, user_id, action_data, action_time) 
                VALUES (?, ?, ?, ?, NOW())
            ", [
                $_SESSION['admin_user_id'],
                $action,
                $userId,
                $encryptedData
            ]);
        } catch (Exception $e) {
            // Логируем ошибку, но не прерываем выполнение
            error_log("Error logging admin action: " . $e->getMessage());
        }
    }
    
    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    // Вспомогательные методы для аналитики
    public function getThreatTypeName($actionType) {
        $names = [
            'suspicious_login' => 'Подозрительный вход',
            'failed_login' => 'Неудачная попытка входа',
            'blocked_ip' => 'IP заблокирован',
            'multiple_failures' => 'Множественные неудачи',
            'password_reset' => 'Сброс пароля',
            'account_blocked' => 'Аккаунт заблокирован',
            'session_terminated' => 'Сессия завершена'
        ];
        
        return $names[$actionType] ?? $actionType;
    }
    
    public function getNotificationIcon($notificationType) {
        $icons = [
            'suspicious_login' => 'user-secret',
            'failed_login' => 'times-circle',
            'blocked_ip' => 'ban',
            'multiple_failures' => 'exclamation-triangle',
            'password_reset' => 'key',
            'account_blocked' => 'user-slash',
            'session_terminated' => 'sign-out-alt'
        ];
        
        return $icons[$notificationType] ?? 'bell';
    }
    
    public function getIncidentIcon($notificationType) {
        return $this->getNotificationIcon($notificationType);
    }
    
    public function getAccessLevelName($level) {
        switch ($level) {
            case 10:
                return 'Администратор';
            case 5:
                return 'Модератор';
            default:
                return 'Пользователь';
        }
    }
} 