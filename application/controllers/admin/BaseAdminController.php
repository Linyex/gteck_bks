<?php

class BaseAdminController extends BaseController {
    
    protected $adminUser = null;
    
    public function __construct() {
        $this->checkAuth();
        $this->loadAdminUser();
    }
    
    protected function checkAuth() {
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            header('Location: /admin/login');
            exit;
        }
    }
    
    protected function loadAdminUser() {
        if (isset($_SESSION['admin_user_id'])) {
            try {
                require_once 'engine/main/db.php';
                $this->adminUser = Database::fetchOne("SELECT * FROM users WHERE user_id = ?", [$_SESSION['admin_user_id']]);
            } catch (Exception $e) {
                // Если не удалось загрузить пользователя, разлогиниваем
                $this->logout();
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
            
            // Начинаем новую буферизацию для layout
            ob_start();
            
            // Подключаем layout
            $layoutFile = APPLICATION_DIR . 'views/admin/layouts/main.php';
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
} 