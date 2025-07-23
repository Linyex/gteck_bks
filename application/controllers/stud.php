<?php

class studController extends BaseController {
    
    public function index() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        return $this->render('stud/index', [
            'title' => 'Студенческий раздел'
        ]);
    }
    
    public function kontrolnui() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $model = $this->loadModel('dkrfiles');
        $files = $model->getAllFilesWithGroups();
        
        return $this->render('stud/kontrolnui', [
            'files' => $files,
            'title' => 'Домашние контрольные работы'
        ]);
    }
    
    public function admin_upload() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $model = $this->loadModel('dkrfiles');
        $groups = $model->getAllGroups();
        $files = $model->getAllFilesWithGroups();
        
        $success_message = '';
        $error_message = '';
        
        // Обработка загрузки файла
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] === 'upload') {
                $group_id = (int)$_POST['group_id'];
                $uploaded_file = $_FILES['file'];
                
                if ($uploaded_file['error'] === UPLOAD_ERR_OK) {
                    $result = $model->uploadFile($uploaded_file, $group_id);
                    if ($result['success']) {
                        $success_message = 'Файл успешно загружен!';
                    } else {
                        $error_message = $result['error'];
                    }
                } else {
                    $error_message = 'Ошибка при загрузке файла';
                }
            } elseif ($_POST['action'] === 'delete') {
                $file_id = (int)$_POST['file_id'];
                $result = $model->deleteFile($file_id);
                if ($result['success']) {
                    $success_message = 'Файл успешно удален!';
                } else {
                    $error_message = $result['error'];
                }
            }
        }
        
        return $this->render('stud/admin_upload', [
            'groups' => $groups,
            'files' => $files,
            'success_message' => $success_message,
            'error_message' => $error_message,
            'title' => 'Административная панель - Загрузка контрольных работ'
        ]);
    }
    
    public function add_kontrolnui() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $model = $this->loadModel('dkrfiles');
        $groups = $model->getAllGroups();
        $message = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $group_id = (int)$_POST['group_id'];
            $uploaded_file = $_FILES['file'];
            
            if ($uploaded_file['error'] === UPLOAD_ERR_OK) {
                $result = $model->uploadFile($uploaded_file, $group_id);
                if ($result['success']) {
                    $message = 'Контрольная работа успешно добавлена!';
                } else {
                    $message = 'Ошибка: ' . $result['error'];
                }
            } else {
                $message = 'Ошибка при загрузке файла';
            }
        }
        
        return $this->render('stud/add_kontrolnui', [
            'groups' => $groups,
            'message' => $message,
            'title' => 'Добавить контрольную работу'
        ]);
    }
}
