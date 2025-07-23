<?php

class abutController extends BaseController {
    
    public function index() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        return $this->render('abut/index', [
            'title' => 'О колледже'
        ]);
    }
    
    public function spec() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        return $this->render('abut/spec', [
            'title' => 'Специальности'
        ]);
    }
    
    public function prof() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        return $this->render('abut/prof', [
            'title' => 'Профессии'
        ]);
    }
    
    public function spiski() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        return $this->render('abut/spiski', [
            'title' => 'Списки'
        ]);
    }
    
    public function sroki() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        return $this->render('abut/sroki', [
            'title' => 'Сроки'
        ]);
    }
    
    public function vups() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        return $this->render('abut/vups', [
            'title' => 'ВУПС'
        ]);
    }
    
    public function grafik() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        return $this->render('abut/grafik', [
            'title' => 'График'
        ]);
    }
    
    public function plan() {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        return $this->render('abut/plan', [
            'title' => 'План'
        ]);
    }
}
