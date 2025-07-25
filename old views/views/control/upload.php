<?php
class uploadController extends Controller {
    
    public function index() {
        $this->document->setActiveSection('control');
        $this->document->setActiveItem('upload');
        $this->document->setTitle('Загрузка контрольных работ | ');
        
        // Список доступных групп
        $this->data['groups'] = [
            'T101', 'T111', 'T201', 'T211', 'T301',
            'Э101', 'Э201', 'Э301',
            'Ю101', 'Ю201', 'Ю301',
            'Б301'
        ];
        
        // Получение загруженных файлов
        $this->load->model('control');
        $this->data['files'] = $this->controlModel->getUploadedFiles();
        
        $this->getChild(array('common/header', 'common/footer'));
        return $this->load->view('control/upload', $this->data);
    }
    
    public function upload() {
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $error = $this->validateUpload();
            
            if (!$error) {
                $group = $this->request->post['group'];
                $uploadedFile = $this->request->files['control_file'];
                
                $this->load->model('control');
                $result = $this->controlModel->uploadFile($group, $uploadedFile);
                
                if ($result['success']) {
                    $this->data['status'] = 'success';
                    $this->data['message'] = 'Файл успешно загружен для группы ' . $group;
                } else {
                    $this->data['status'] = 'error';
                    $this->data['error'] = $result['error'];
                }
            } else {
                $this->data['status'] = 'error';
                $this->data['error'] = $error;
            }
        }
        
        return json_encode($this->data);
    }
    
    public function delete() {
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $fileId = $this->request->post['file_id'];
            
            if ($fileId) {
                $this->load->model('control');
                $result = $this->controlModel->deleteFile($fileId);
                
                if ($result['success']) {
                    $this->data['status'] = 'success';
                    $this->data['message'] = 'Файл успешно удален';
                } else {
                    $this->data['status'] = 'error';
                    $this->data['error'] = $result['error'];
                }
            } else {
                $this->data['status'] = 'error';
                $this->data['error'] = 'Не указан ID файла';
            }
        }
        
        return json_encode($this->data);
    }
    
    public function getFiles() {
        $this->load->model('control');
        $files = $this->controlModel->getUploadedFiles();
        
        $this->data['status'] = 'success';
        $this->data['files'] = $files;
        
        return json_encode($this->data);
    }
    
    private function validateUpload() {
        $result = null;
        
        $group = @$this->request->post['group'];
        $uploadedFile = @$this->request->files['control_file'];
        
        if (empty($group)) {
            $result = 'Пожалуйста, выберите группу';
        } elseif (empty($uploadedFile) || $uploadedFile['error'] !== UPLOAD_ERR_OK) {
            $result = 'Пожалуйста, выберите файл для загрузки';
        } else {
            // Проверка типа файла
            $allowedTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];
            
            if (!in_array($uploadedFile['type'], $allowedTypes)) {
                $result = 'Разрешены только файлы PDF и Word документы';
            }
            
            // Проверка размера файла (максимум 10MB)
            if ($uploadedFile['size'] > 10485760) {
                $result = 'Размер файла не должен превышать 10MB';
            }
        }
        
        return $result;
    }
}
?> 