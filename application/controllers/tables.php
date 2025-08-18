<?php

require_once ENGINE_DIR . 'main/db.php';
require_once APPLICATION_DIR . 'models/TableModel.php';

class tablesController extends BaseController {
    public function index($slug = null) {
        if (!$slug) {
            return $this->list();
        }
        return $this->view($slug);
    }

    public function list() {
        $model = new TableModel();
        $all = $model->listAll();
        $public = array_values(array_filter($all, fn($t) => (int)$t['is_public'] === 1));
        return $this->render('tables/list', [
            'title' => 'Публичные таблицы',
            'tables' => $public
        ]);
    }

    public function view($slug) {
        $model = new TableModel();
        $item = $model->getBySlug($slug);
        if (!$item || (int)$item['is_public'] !== 1) {
            return $this->redirect('/');
        }
        return $this->render('tables/view', [
            'title' => $item['title'] . ' - Таблица',
            'item' => $item
        ]);
    }
}


