<?php

class abutController extends BaseController {
    
    public function index() {
        return $this->render('abut/index', [
            'title' => 'О колледже'
        ]);
    }
    
    public function spec() {
        return $this->render('abut/spec', [
            'title' => 'Специальности'
        ]);
    }
    
    public function prof() {
        return $this->render('abut/prof', [
            'title' => 'Профессии'
        ]);
    }
    
    public function spiski() {
        return $this->render('abut/spiski', [
            'title' => 'Списки'
        ]);
    }
    
    public function sroki() {
        return $this->render('abut/sroki', [
            'title' => 'Сроки'
        ]);
    }
    
    public function vups() {
        return $this->render('abut/vups', [
            'title' => 'ВУПС'
        ]);
    }
    
    public function grafik() {
        return $this->render('abut/grafik', [
            'title' => 'График'
        ]);
    }
    
    public function plan() {
        return $this->render('abut/plan', [
            'title' => 'План'
        ]);
    }
}
