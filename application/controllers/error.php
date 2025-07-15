<?php

class errorController extends BaseController {
    
    public function index() {
        http_response_code(404);
        return $this->render('error/mane', [
            'title' => 'Страница не найдена',
            'message' => 'Запрашиваемая страница не существует.'
        ]);
    }
} 