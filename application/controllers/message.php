<?php

class messageController extends BaseController {
    
    public function index() {
        return $this->mane();
    }
    
    public function mane() {
        $message = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $this->getPost('name');
            $email = $this->getPost('email');
            $subject = $this->getPost('subject');
            $message_text = $this->getPost('message');
            
            if ($name && $email && $subject && $message_text) {
                // Здесь можно добавить отправку email или сохранение в БД
                $message = 'Сообщение отправлено успешно!';
            } else {
                $message = 'Пожалуйста, заполните все поля!';
            }
        }
        
        return $this->render('message/mane', [
            'message' => $message,
            'title' => 'Обратная связь'
        ]);
    }
}
