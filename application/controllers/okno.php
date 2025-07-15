<?php

class oknoController extends BaseController {
    
    public function index() {
        return $this->render('okno/index', [
            'title' => 'Приемная комиссия'
        ]);
    }
    
    public function forma() {
        return $this->render('okno/forma', [
            'title' => 'Форма заявления'
        ]);
    }
    
    public function info() {
        return $this->render('okno/info', [
            'title' => 'Информация'
        ]);
    }
    
    public function osnovi() {
        return $this->render('okno/osnovi', [
            'title' => 'Основы'
        ]);
    }
    
    public function poryad() {
        return $this->render('okno/poryad', [
            'title' => 'Порядок'
        ]);
    }
    
    public function proc() {
        return $this->render('okno/proc', [
            'title' => 'Процедура'
        ]);
    }
}
