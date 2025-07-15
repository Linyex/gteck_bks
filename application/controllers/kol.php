<?php

class kolController extends BaseController {
    
    public function index() {
        return $this->render('kol/index', [
            'title' => 'Колледж'
        ]);
    }
    
    public function mane() {
        return $this->render('kol/mane', [
            'title' => 'Главная колледжа'
        ]);
    }
    
    public function history() {
        return $this->render('kol/history', [
            'title' => 'История колледжа'
        ]);
    }
    
    public function contact() {
        return $this->render('kol/contact', [
            'title' => 'Контакты'
        ]);
    }
    
    public function grafik() {
        return $this->render('kol/grafik', [
            'title' => 'График работы'
        ]);
    }
    
    public function achiv() {
        return $this->render('kol/achiv', [
            'title' => 'Достижения'
        ]);
    }
    
    public function video() {
        return $this->render('kol/video', [
            'title' => 'Видео'
        ]);
    }
}
