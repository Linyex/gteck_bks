<?php

class filesController extends BaseController {
    
    public function index() {
        return $this->render('files/index', [
            'title' => 'Файлы'
        ]);
    }
    
    public function mane() {
        return $this->render('files/mane', [
            'title' => 'Управление файлами'
        ]);
    }
    
    public function download($filename = null) {
        if (!$filename) {
            return $this->redirect('/files');
        }
        
        $filepath = 'assets/files/' . $filename;
        if (file_exists($filepath)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit;
        } else {
            return $this->redirect('/files');
        }
    }
}
