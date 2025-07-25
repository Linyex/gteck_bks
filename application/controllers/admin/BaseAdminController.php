<?php
require_once __DIR__ . '/../../../engine/BaseController.php';

class BaseAdminController extends BaseController {
    
    protected $adminUser = null;
    
    public function __construct() {
        parent::__construct();
        $this->checkAuth();
        $this->loadAdminUser();
        $this->logActivity();
    }
    
    protected function checkAuth() {
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            if (!headers_sent()) {
                header('Location: /admin/login');
                exit;
            } else {
                echo '<script>window.location.href = "/admin/login";</script>';
                exit;
            }
        }
    }
    
    protected function loadAdminUser() {
        if (isset($_SESSION['admin_user_id'])) {
            try {
                require_once ENGINE_DIR . 'main/db.php';
                $this->adminUser = Database::fetchOne("SELECT * FROM users WHERE user_id = ?", [$_SESSION['admin_user_id']]);
            } catch (Exception $e) {
                // Если не удалось загрузить пользователя, разлогиниваем
                $this->logout();
            }
        }
    }
    
    protected function logActivity() {
        if (isset($_SESSION['admin_user_id']) && $this->adminUser) {
            try {
                require_once ENGINE_DIR . 'main/db.php';
                
                $action = $_SERVER['REQUEST_URI'] ?? '';
                $description = "Просмотр страницы: $action";
                $ip = $_SERVER['REMOTE_ADDR'] ?? '';
                $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
                
                // Логируем активность пользователя
                Database::execute(
                    "INSERT INTO user_activity (user_id, action_type, activity_description, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
                    [$_SESSION['admin_user_id'], 'page_view', $description, $ip, $userAgent]
                );
                
                // Обновляем время последней активности в сессии
                $sessionToken = session_id();
                Database::execute(
                    "UPDATE user_sessions SET last_activity = NOW() WHERE user_id = ? AND session_token = ? AND is_active = 1",
                    [$_SESSION['admin_user_id'], $sessionToken]
                );
                
            } catch (Exception $e) {
                // Игнорируем ошибки логирования
            }
        }
    }
    
    // Переопределяем метод render для админских страниц
    protected function render($view, $data = []) {
        // Добавляем данные админского пользователя
        $data['adminUser'] = $this->adminUser;
        
        // Извлекаем переменные для view
        extract($data);
        
        // Начинаем буферизацию
        ob_start();
        
        // Подключаем основной view
        $viewFile = APPLICATION_DIR . 'views/' . $view . '.php';
        if (file_exists($viewFile)) {
            include($viewFile);
        } else {
            throw new Exception("View {$view} not found");
        }
        
        // Получаем содержимое
        $content = ob_get_clean();
        
        // Если это не страница логина, используем layout
        if (strpos($view, 'admin/auth/login') === false) {
            // Извлекаем переменные снова для layout
            extract($data);
            
            // Добавляем контент страницы в данные для layout
            $page_content = $content;
            
            // Начинаем новую буферизацию для layout
            ob_start();
            
            // Подключаем layout
            $layoutFile = APPLICATION_DIR . 'views/admin/layouts/base.php';
            if (file_exists($layoutFile)) {
                include($layoutFile);
            } else {
                // Если layout не найден, возвращаем только content
                return $content;
            }
            
            return ob_get_clean();
        }
        
        // Для страницы логина возвращаем только content
        return $content;
    }
    
    protected function requireAccessLevel($minLevel = 1) {
        if (!$this->adminUser || $this->adminUser['user_access_level'] < $minLevel) {
            $this->render('admin/error/access_denied', [
                'title' => 'Доступ запрещен',
                'message' => 'У вас недостаточно прав для выполнения этого действия'
            ]);
            exit;
        }
    }
    
    protected function logout() {
        session_destroy();
        header('Location: /admin/login');
        exit;
    }
    
    protected function getAdminUser() {
        return $this->adminUser;
    }
    
    protected function isAdmin() {
        return $this->adminUser && $this->adminUser['user_access_level'] >= 10;
    }
    
    protected function isModerator() {
        return $this->adminUser && $this->adminUser['user_access_level'] >= 5;
    }
    
    protected function isEditor() {
        return $this->adminUser && $this->adminUser['user_access_level'] >= 1;
    }
    
    protected function getAccessLevelName($level) {
        switch ($level) {
            case 10:
                return 'Администратор';
            case 5:
                return 'Модератор';
            case 1:
                return 'Редактор';
            default:
                return 'Пользователь';
        }
    }
} 