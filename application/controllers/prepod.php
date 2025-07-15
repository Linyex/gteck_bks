<?php

class prepodController extends BaseController {
    
    public function index() {
        return $this->render('prepod/index', [
            'title' => 'Преподаватели'
        ]);
    }
    
    public function kyrator() {
        return $this->render('prepod/kyrator', [
            'title' => 'Кураторы'
        ]);
    }
    
    public function metod() {
        return $this->render('prepod/metod', [
            'title' => 'Методические материалы'
        ]);
    }
}
