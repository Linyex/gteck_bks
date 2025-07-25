<?php

require_once APPLICATION_DIR . 'controllers/admin/api/BaseApiController.php';

class UsersApiController extends BaseApiController {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * GET /api/users
     * Получение списка пользователей
     */
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAccessLevel(5); // Только модераторы и выше
            
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
            $search = $_GET['search'] ?? '';
            $status = $_GET['status'] ?? '';
            $accessLevel = $_GET['access_level'] ?? '';
            
            require_once ENGINE_DIR . 'main/db.php';
            
            // Формируем базовый запрос
            $query = "SELECT user_id, user_login, user_fio, user_email, user_status, user_access_level, user_date_reg, user_last_login FROM users WHERE 1=1";
            $params = [];
            
            // Добавляем фильтры
            if (!empty($search)) {
                $query .= " AND (user_login LIKE ? OR user_fio LIKE ? OR user_email LIKE ?)";
                $searchParam = "%$search%";
                $params = array_merge($params, [$searchParam, $searchParam, $searchParam]);
            }
            
            if ($status !== '') {
                $query .= " AND user_status = ?";
                $params[] = (int)$status;
            }
            
            if ($accessLevel !== '') {
                $query .= " AND user_access_level = ?";
                $params[] = (int)$accessLevel;
            }
            
            $query .= " ORDER BY user_date_reg DESC";
            
            // Получаем данные с пагинацией
            $result = $this->paginate($query, $params, $page, $perPage);
            
            // Получаем статистику
            $stats = $this->getUserStats();
            
            $this->setSuccess([
                'users' => $result['data'],
                'pagination' => $result['pagination'],
                'stats' => $stats
            ]);
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * GET /api/users/{id}
     * Получение информации о пользователе
     */
    public function show($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAccessLevel(5);
            
            require_once ENGINE_DIR . 'main/db.php';
            
            $user = Database::fetchOne(
                "SELECT user_id, user_login, user_fio, user_email, user_status, user_access_level, user_date_reg, user_last_login FROM users WHERE user_id = ?",
                [$id]
            );
            
            if (!$user) {
                $this->sendError('User not found', 404);
            }
            
            // Получаем активность пользователя
            $activity = Database::fetchAll(
                "SELECT action_type, description, ip_address, activity_time FROM user_activity_log WHERE user_id = ? ORDER BY activity_time DESC LIMIT 10",
                [$id]
            );
            
            // Получаем сессии пользователя
            $sessions = Database::fetchAll(
                "SELECT ip_address, user_agent, created_at, last_activity FROM user_sessions WHERE user_id = ? ORDER BY last_activity DESC LIMIT 5",
                [$id]
            );
            
            // Получаем настройки 2FA
            $user2fa = Database::fetchOne(
                "SELECT is_enabled FROM user_2fa WHERE user_id = ?",
                [$id]
            );
            
            $user['2fa_enabled'] = $user2fa ? (bool)$user2fa['is_enabled'] : false;
            $user['activity'] = $activity;
            $user['sessions'] = $sessions;
            
            $this->setSuccess($user);
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * POST /api/users
     * Создание нового пользователя
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAccessLevel(10); // Только администраторы
            
            $data = $this->getPostData();
            
            // Валидация данных
            $this->validateData($data, [
                'user_login' => 'required|string|min:3|max:50',
                'user_fio' => 'required|string|min:2|max:100',
                'user_email' => 'required|email',
                'user_password' => 'required|string|min:6|max:255',
                'user_access_level' => 'required|int'
            ]);
            
            require_once ENGINE_DIR . 'main/db.php';
            
            // Проверяем уникальность логина
            $existingUser = Database::fetchOne(
                "SELECT user_id FROM users WHERE user_login = ?",
                [$data['user_login']]
            );
            
            if ($existingUser) {
                $this->sendError('Username already exists', 400);
            }
            
            // Проверяем уникальность email
            $existingEmail = Database::fetchOne(
                "SELECT user_id FROM users WHERE user_email = ?",
                [$data['user_email']]
            );
            
            if ($existingEmail) {
                $this->sendError('Email already exists', 400);
            }
            
            // Хешируем пароль
            $hashedPassword = password_hash($data['user_password'], PASSWORD_DEFAULT);
            
            // Создаем пользователя
            Database::execute(
                "INSERT INTO users (user_login, user_fio, user_email, user_password, user_access_level, user_status, user_date_reg) VALUES (?, ?, ?, ?, ?, 1, NOW())",
                [
                    $data['user_login'],
                    $data['user_fio'],
                    $data['user_email'],
                    $hashedPassword,
                    $data['user_access_level']
                ]
            );
            
            $userId = Database::lastInsertId();
            
            // Логируем создание пользователя
            $this->logApiRequest('user_created', [
                'user_id' => $userId,
                'created_by' => $_SESSION['admin_user_id']
            ]);
            
            // Получаем созданного пользователя
            $user = Database::fetchOne(
                "SELECT user_id, user_login, user_fio, user_email, user_status, user_access_level, user_date_reg FROM users WHERE user_id = ?",
                [$userId]
            );
            
            $this->setSuccess($user, 'User created successfully');
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * PUT /api/users/{id}
     * Обновление пользователя
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAccessLevel(5);
            
            $data = $this->getPostData();
            
            // Валидация данных
            $this->validateData($data, [
                'user_fio' => 'required|string|min:2|max:100',
                'user_email' => 'required|email',
                'user_access_level' => 'required|int'
            ]);
            
            require_once ENGINE_DIR . 'main/db.php';
            
            // Проверяем существование пользователя
            $existingUser = Database::fetchOne(
                "SELECT user_id FROM users WHERE user_id = ?",
                [$id]
            );
            
            if (!$existingUser) {
                $this->sendError('User not found', 404);
            }
            
            // Проверяем уникальность email
            $existingEmail = Database::fetchOne(
                "SELECT user_id FROM users WHERE user_email = ? AND user_id != ?",
                [$data['user_email'], $id]
            );
            
            if ($existingEmail) {
                $this->sendError('Email already exists', 400);
            }
            
            // Обновляем пользователя
            Database::execute(
                "UPDATE users SET user_fio = ?, user_email = ?, user_access_level = ? WHERE user_id = ?",
                [
                    $data['user_fio'],
                    $data['user_email'],
                    $data['user_access_level'],
                    $id
                ]
            );
            
            // Логируем обновление
            $this->logApiRequest('user_updated', [
                'user_id' => $id,
                'updated_by' => $_SESSION['admin_user_id']
            ]);
            
            // Получаем обновленного пользователя
            $user = Database::fetchOne(
                "SELECT user_id, user_login, user_fio, user_email, user_status, user_access_level, user_date_reg FROM users WHERE user_id = ?",
                [$id]
            );
            
            $this->setSuccess($user, 'User updated successfully');
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * DELETE /api/users/{id}
     * Удаление пользователя
     */
    public function destroy($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAccessLevel(10); // Только администраторы
            
            require_once ENGINE_DIR . 'main/db.php';
            
            // Проверяем существование пользователя
            $user = Database::fetchOne(
                "SELECT user_id, user_login FROM users WHERE user_id = ?",
                [$id]
            );
            
            if (!$user) {
                $this->sendError('User not found', 404);
            }
            
            // Нельзя удалить самого себя
            if ($id == $_SESSION['admin_user_id']) {
                $this->sendError('Cannot delete yourself', 400);
            }
            
            // Удаляем пользователя
            Database::execute("DELETE FROM users WHERE user_id = ?", [$id]);
            
            // Логируем удаление
            $this->logApiRequest('user_deleted', [
                'user_id' => $id,
                'deleted_by' => $_SESSION['admin_user_id']
            ]);
            
            $this->setSuccess(null, 'User deleted successfully');
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * POST /api/users/{id}/block
     * Блокировка пользователя
     */
    public function block($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAccessLevel(5);
            
            require_once ENGINE_DIR . 'main/db.php';
            
            // Проверяем существование пользователя
            $user = Database::fetchOne(
                "SELECT user_id, user_status FROM users WHERE user_id = ?",
                [$id]
            );
            
            if (!$user) {
                $this->sendError('User not found', 404);
            }
            
            if ($user['user_status'] == 0) {
                $this->sendError('User is already blocked', 400);
            }
            
            // Блокируем пользователя
            Database::execute(
                "UPDATE users SET user_status = 0 WHERE user_id = ?",
                [$id]
            );
            
            // Завершаем все сессии пользователя
            Database::execute(
                "DELETE FROM user_sessions WHERE user_id = ?",
                [$id]
            );
            
            // Логируем блокировку
            $this->logApiRequest('user_blocked', [
                'user_id' => $id,
                'blocked_by' => $_SESSION['admin_user_id']
            ]);
            
            $this->setSuccess(null, 'User blocked successfully');
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * POST /api/users/{id}/unblock
     * Разблокировка пользователя
     */
    public function unblock($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAccessLevel(5);
            
            require_once ENGINE_DIR . 'main/db.php';
            
            // Проверяем существование пользователя
            $user = Database::fetchOne(
                "SELECT user_id, user_status FROM users WHERE user_id = ?",
                [$id]
            );
            
            if (!$user) {
                $this->sendError('User not found', 404);
            }
            
            if ($user['user_status'] == 1) {
                $this->sendError('User is not blocked', 400);
            }
            
            // Разблокируем пользователя
            Database::execute(
                "UPDATE users SET user_status = 1 WHERE user_id = ?",
                [$id]
            );
            
            // Логируем разблокировку
            $this->logApiRequest('user_unblocked', [
                'user_id' => $id,
                'unblocked_by' => $_SESSION['admin_user_id']
            ]);
            
            $this->setSuccess(null, 'User unblocked successfully');
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * Получение статистики пользователей
     */
    private function getUserStats() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $stats = [];
            
            // Общее количество пользователей
            $total = Database::fetchOne("SELECT COUNT(*) as count FROM users");
            $stats['total'] = $total['count'] ?? 0;
            
            // Активные пользователи
            $active = Database::fetchOne("SELECT COUNT(*) as count FROM users WHERE user_status = 1");
            $stats['active'] = $active['count'] ?? 0;
            
            // Заблокированные пользователи
            $blocked = Database::fetchOne("SELECT COUNT(*) as count FROM users WHERE user_status = 0");
            $stats['blocked'] = $blocked['count'] ?? 0;
            
            // Пользователи с 2FA
            $with2fa = Database::fetchOne("SELECT COUNT(*) as count FROM user_2fa WHERE is_enabled = 1");
            $stats['with_2fa'] = $with2fa['count'] ?? 0;
            
            // Новые пользователи за последние 30 дней
            $newUsers = Database::fetchOne(
                "SELECT COUNT(*) as count FROM users WHERE user_date_reg >= DATE_SUB(NOW(), INTERVAL 30 DAY)"
            );
            $stats['new_last_30_days'] = $newUsers['count'] ?? 0;
            
            return $stats;
        } catch (Exception $e) {
            return [];
        }
    }
} 