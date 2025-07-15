<?php

class dopageController extends BaseController {
    
    public function index() {
        return $this->render('dopage/index', [
            'title' => 'Дополнительные страницы'
        ]);
    }
    
    public function bestsport() {
        return $this->render('dopage/bestsport', [
            'title' => 'Лучшие спортсмены'
        ]);
    }
    
    public function bestsportphoto() {
        return $this->render('dopage/bestsportphoto', [
            'title' => 'Фото спортсменов'
        ]);
    }
    
    public function brsm() {
        return $this->render('dopage/brsm', [
            'title' => 'БРСМ'
        ]);
    }
    
    public function elections() {
        return $this->render('dopage/elections', [
            'title' => 'Выборы'
        ]);
    }
    
    public function faq() {
        return $this->render('dopage/faq', [
            'title' => 'Часто задаваемые вопросы'
        ]);
    }
    
    public function kryjki() {
        return $this->render('dopage/kryjki', [
            'title' => 'Кружки'
        ]);
    }
    
    public function muzei() {
        return $this->render('dopage/muzei', [
            'title' => 'Музей'
        ]);
    }
    
    public function oblisp() {
        return $this->render('dopage/oblisp', [
            'title' => 'Областная исполнительная власть'
        ]);
    }
    
    public function praktiki() {
        return $this->render('dopage/praktiki', [
            'title' => 'Практики'
        ]);
    }
    
    public function pravila() {
        return $this->render('dopage/pravila', [
            'title' => 'Правила'
        ]);
    }
    
    public function priomp() {
        return $this->render('dopage/priomp', [
            'title' => 'Приемная комиссия'
        ]);
    }
    
    public function profs() {
        return $this->render('dopage/profs', [
            'title' => 'Профессии'
        ]);
    }
    
    public function psixolog() {
        return $this->render('dopage/psixolog', [
            'title' => 'Психолог'
        ]);
    }
    
    public function social() {
        return $this->render('dopage/social', [
            'title' => 'Социальная работа'
        ]);
    }
    
    public function sport() {
        return $this->render('dopage/sport', [
            'title' => 'Спорт'
        ]);
    }
    
    public function sportkr() {
        return $this->render('dopage/sportkr', [
            'title' => 'Спортивные кружки'
        ]);
    }
    
    public function vesti() {
        return $this->render('dopage/vesti', [
            'title' => 'Вести'
        ]);
    }
    
    public function ychpraktika() {
        return $this->render('dopage/ychpraktika', [
            'title' => 'Учебная практика'
        ]);
    }
    
    public function ychxod() {
        return $this->render('dopage/ychxod', [
            'title' => 'Учебные ходы'
        ]);
    }
    
    public function yearquality() {
        return $this->render('dopage/yearquality', [
            'title' => 'Качество года'
        ]);
    }
}
