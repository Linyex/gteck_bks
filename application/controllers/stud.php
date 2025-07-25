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
    
    public function spiski() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Получаем данные списков групп из БД
        $listgr = $this->db->fetchAll("SELECT * FROM `listgr` ORDER BY listgr_status ASC, listgr_id DESC");
        
        // Инициализируем пустые значения если данных нет
        if (!$listgr) $listgr = [];
        
        return $this->render('stud/spiski', [
            'listgr' => $listgr,
            'title' => 'Списки учебных групп'
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
    
    public function ymk() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        return $this->render('stud/ymk', [
            'title' => 'Учебно-методические комплексы'
        ]);
    }
    
    public function yslugi() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        return $this->render('stud/yslugi', [
            'title' => 'Платные услуги'
        ]);
    }
    
    public function prof() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        return $this->render('stud/prof', [
            'title' => 'Дополнительное образование'
        ]);
    }
    
    public function library() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        return $this->render('stud/library', [
            'title' => 'Библиотека'
        ]);
    }
    
    public function hostel() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        return $this->render('stud/hostel', [
            'title' => 'Общежитие'
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
    
    public function dnevnoe() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Получаем данные напрямую из БД
        $filesa = $this->db->fetchAll("SELECT * FROM `files` ORDER BY files_id DESC");
        $status1 = $this->db->fetchOne("SELECT statusb_code FROM `statusb` WHERE statusb_id = 1");
        $lastzamena = $this->db->fetchOne("SELECT * FROM `zamena` ORDER BY zamena_date_add DESC LIMIT 1");
        
        // Инициализируем пустые значения если данных нет
        if (!$filesa) $filesa = [];
        if (!$status1) $status1 = ['statusb_code' => 0];
        if (!$lastzamena) $lastzamena = [];
        
        // Подсчитываем количество экзаменационных и обычных файлов
        $empty1 = 0; // экзаменационные файлы
        $empty2 = 0; // обычные файлы
        
        foreach ($filesa as $file) {
            if ($file['files_ekzamen'] == 2) {
                $empty1++;
            } elseif ($file['files_ekzamen'] == 1) {
                $empty2++;
            }
        }
        
        return $this->render('stud/dnevnoe', [
            'filesa' => $filesa,
            'lastzamena' => $lastzamena,
            'status1' => $status1,
            'empty1' => $empty1,
            'empty2' => $empty2,
            'title' => 'Дневное отделение'
        ]);
    }
    
    public function zaoch() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Получаем данные напрямую из БД
        $filesa = $this->db->fetchAll("SELECT * FROM `files` ORDER BY files_id DESC");
        $status1 = $this->db->fetchOne("SELECT statusb_code FROM `statusb` WHERE statusb_id = 1");
        $lastzamena = $this->db->fetchOne("SELECT * FROM `zamena` ORDER BY zamena_date_add DESC LIMIT 1");
        
        // Инициализируем пустые значения если данных нет
        if (!$filesa) $filesa = [];
        if (!$status1) $status1 = ['statusb_code' => 0];
        if (!$lastzamena) $lastzamena = [];
        
        // Подсчитываем количество экзаменационных и обычных файлов
        $empty1 = 0; // экзаменационные файлы
        $empty2 = 0; // обычные файлы
        
        foreach ($filesa as $file) {
            if ($file['files_ekzamen'] == 2) {
                $empty1++;
            } elseif ($file['files_ekzamen'] == 1) {
                $empty2++;
            }
        }
        
        return $this->render('stud/zaoch', [
            'filesa' => $filesa,
            'lastzamena' => $lastzamena,
            'status1' => $status1,
            'empty1' => $empty1,
            'empty2' => $empty2,
            'title' => 'Заочная форма получения образования'
        ]);
    }
}
