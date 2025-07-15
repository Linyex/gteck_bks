<?php

class adminController extends BaseController {
    
    public function index() {
        return $this->render('admin/index', [
            'title' => 'Административная панель'
        ]);
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->getPost('username');
            $password = $this->getPost('password');
            
            // Простая проверка (замените на реальную аутентификацию)
            if ($username === 'admin' && $password === 'admin') {
                $_SESSION['admin_logged_in'] = true;
                return $this->redirect('/admin');
            } else {
                $error = 'Неверные логин или пароль';
            }
        }
        
        return $this->render('admin/login', [
            'error' => $error ?? null,
            'title' => 'Вход в админ-панель'
        ]);
    }
    
    public function logout() {
        unset($_SESSION['admin_logged_in']);
        return $this->redirect('/admin/login');
    }
}
