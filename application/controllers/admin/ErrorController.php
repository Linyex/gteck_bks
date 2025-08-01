<?php

require_once ENGINE_DIR . 'controllers/admin/BaseAdminController.php';

class ErrorController extends BaseAdminController {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Страница 404
     */
    public function notFound() {
        $this->render('admin/error/404', [
            'title' => 'Страница не найдена',
            'message' => 'Запрашиваемая страница не существует или была перемещена.'
        ]);
    }
    
    /**
     * Страница 500
     */
    public function serverError() {
        $this->render('admin/error/500', [
            'title' => 'Внутренняя ошибка сервера',
            'message' => 'Произошла внутренняя ошибка сервера. Попробуйте обновить страницу.'
        ]);
    }
    
    /**
     * Страница доступа запрещен
     */
    public function accessDenied() {
        $this->render('admin/error/access_denied', [
            'title' => 'Доступ запрещен',
            'message' => 'У вас нет прав для доступа к этой странице.'
        ]);
    }
    
    /**
     * Страница ошибки
     */
    public function error($title = null, $message = null) {
        $this->render('admin/error/error', [
            'title' => $title ?: 'Ошибка',
            'message' => $message ?: 'Произошла ошибка при загрузке страницы.'
        ]);
    }
    
    /**
     * Страница обслуживания
     */
    public function maintenance() {
        $this->render('admin/error/maintenance', [
            'title' => 'Техническое обслуживание',
            'message' => 'Система находится на техническом обслуживании. Пожалуйста, попробуйте позже.'
        ]);
    }
} 