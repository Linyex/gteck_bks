<?php

require_once ENGINE_DIR . 'main/db.php';
require_once APPLICATION_DIR . 'models/ContentModel.php';

class ContentApiController {
    public function getOverrides() {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $path = $_GET['path'] ?? '/';
            $model = new ContentModel();
            $rows = $model->getOverridesForPath($path);
            echo json_encode($rows, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([]);
        }
        exit;
    }
}


