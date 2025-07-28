<?php

require_once __DIR__ . '/BaseAdminController.php';

class GroupPasswordsController extends BaseAdminController {
    
    public function index() {
        $this->requireAccessLevel(10); // Только администраторы
        
        try {
            $passwords = Database::fetchAll("SELECT * FROM group_passwords ORDER BY group_name");
            
            echo $this->render('admin/group-passwords/index', [
                'passwords' => $passwords,
                'title' => 'Управление паролями групп',
                'currentPage' => 'group-passwords'
            ]);
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }
    
    public function create() {
        $this->requireAccessLevel(10); // Только администраторы
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $group_name = trim($_POST['group_name']);
                $password = trim($_POST['password']);
                $description = trim($_POST['description']);
                
                if (empty($group_name) || empty($password)) {
                    throw new Exception('Название группы и пароль обязательны для заполнения');
                }
                
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                Database::execute(
                    "INSERT INTO group_passwords (group_name, password, description) VALUES (?, ?, ?)",
                    [$group_name, $hashed_password, $description]
                );
                
                $_SESSION['success'] = 'Пароль группы добавлен успешно';
                header('Location: /admin/group-passwords');
                exit;
                
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        echo $this->render('admin/group-passwords/create', [
            'title' => 'Добавить пароль группы',
            'currentPage' => 'group-passwords'
        ]);
    }
    
    public function edit($id = null) {
        $this->requireAccessLevel(10); // Только администраторы
        
        if (!$id) {
            header('Location: /admin/group-passwords');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $group_name = trim($_POST['group_name']);
                $password = trim($_POST['password']);
                $description = trim($_POST['description']);
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                if (empty($group_name)) {
                    throw new Exception('Название группы обязательно для заполнения');
                }
                
                $params = [$group_name, $description, $is_active, $id];
                $sql = "UPDATE group_passwords SET group_name = ?, description = ?, is_active = ? ";
                
                if (!empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $sql .= ", password = ? ";
                    array_splice($params, 3, 0, [$hashed_password]);
                }
                
                $sql .= "WHERE id = ?";
                
                Database::execute($sql, $params);
                
                $_SESSION['success'] = 'Пароль группы обновлен успешно';
                header('Location: /admin/group-passwords');
                exit;
                
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        try {
            $password = Database::fetchOne("SELECT * FROM group_passwords WHERE id = ?", [$id]);
            if (!$password) {
                throw new Exception('Пароль группы не найден');
            }
            
            echo $this->render('admin/group-passwords/edit', [
                'password' => $password,
                'title' => 'Редактировать пароль группы',
                'currentPage' => 'group-passwords'
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /admin/group-passwords');
            exit;
        }
    }
    
    public function delete() {
        $this->requireAccessLevel(10); // Только администраторы
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID не указан']);
            return;
        }
        
        try {
            Database::execute("DELETE FROM group_passwords WHERE id = ?", [$id]);
            $this->jsonResponse(['success' => true, 'message' => 'Пароль группы удален']);
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function toggle() {
        $this->requireAccessLevel(10); // Только администраторы
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID не указан']);
            return;
        }
        
        try {
            $current = Database::fetchOne("SELECT is_active FROM group_passwords WHERE id = ?", [$id]);
            $new_status = $current['is_active'] ? 0 : 1;
            
            Database::execute("UPDATE group_passwords SET is_active = ? WHERE id = ?", [$new_status, $id]);
            
            $this->jsonResponse([
                'success' => true, 
                'message' => 'Статус изменен',
                'is_active' => $new_status
            ]);
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    protected function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function handleError($e) {
        $_SESSION['error'] = 'Произошла ошибка: ' . $e->getMessage();
        echo $this->render('admin/error/500', [
            'title' => 'Ошибка сервера',
            'message' => $e->getMessage(),
            'currentPage' => 'group-passwords'
        ]);
    }
}
?> 