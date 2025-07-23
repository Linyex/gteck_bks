<?php

class mainController extends BaseController {
    private $limit = 10; // Изменено с 4 на 10
    
    public function index($page = 1) {
        // Включаем кэширование для улучшения производительности
        header('Cache-Control: public, max-age=3600');
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s \G\M\T', time()));
        
        // Загружаем модели
        $newsModel = $this->loadModel('news');
        $zamenaModel = $this->loadModel('zamena');
        
        // Получаем важные новости для слайдера
        $importantNews = $newsModel->getImportantNews(6);
        
        // Получаем обычные новости для сетки
        $regularNews = $newsModel->getRegularNews(4);
        
        // Получаем все новости для пагинации
        $sort = ['news_date_add' => 'DESC'];
        $options = [
            'start' => ((int)$page - 1) * $this->limit,
            'limit' => $this->limit
        ];
        
        $total = $newsModel->getTotalnews();
        $news = $newsModel->getnews([], ['category'], $sort, $options);
        
        // Создаем пагинацию
        $pagination = $this->createPagination($total, $page, $this->limit, '/news/page/{page}');
        
        // Получаем последние замены
        $lastzamena = $zamenaModel->getLastZamenas();
        
        // Определяем статусы для приемной кампании
        $status1 = ['statusb_code' => 0]; // По умолчанию выключено
        $status3 = ['statusb_code' => 0]; // По умолчанию выключено
        
        // Подготавливаем данные для view
        $data = [
            'important_news' => $importantNews,
            'regular_news' => $regularNews,
            'news' => $news,
            'pagination' => $pagination,
            'lastzamena' => $lastzamena,
            'status1' => $status1,
            'status3' => $status3,
            'total_news' => $total,
            'current_page' => $page
        ];
        
        // Рендерим новый файл main/index.php вместо старого mane/mane.php
        return $this->render('main/index', $data);
    }
    
    public function ajax() {
        if ($this->isAjax() && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $searchText = $this->getPost('Search_text2');
            
            $error = $this->validateSearch($searchText);
            if ($error) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'error' => $error
                ]);
            }
            
            $results = $this->performSearch($searchText);
            
            if (!empty($results)) {
                return $this->jsonResponse([
                    'status' => 'success',
                    'success' => $results
                ]);
            } else {
                return $this->jsonResponse([
                    'status' => 'error',
                    'error' => 'По данному запросу не было ничего найдено!'
                ]);
            }
        }
        
        return $this->jsonResponse(['status' => 'error', 'error' => 'Invalid request']);
    }
    
    private function validateSearch($searchText) {
        if (mb_strlen($searchText) < 3 || mb_strlen($searchText) > 70) {
            return "Текст поиска должен содержать от 3 до 70 символов!";
        }
        return null;
    }
    
    private function performSearch($searchText) {
        // Временно отключаем поиск по файлам, так как allfile.dat был удален
        $results = [];
        return $results;
    }
    
    private function mb_ucfirst($str, $encoding = 'UTF-8') {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
               mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }
    
    private function createPagination($total, $page, $limit, $url) {
        $totalPages = ceil($total / $limit);
        
        if ($totalPages <= 1) {
            return '';
        }
        
        $html = '<div class="pagination">';
        
        // Предыдущая страница
        if ($page > 1) {
            $prevUrl = str_replace('{page}', $page - 1, $url);
            $html .= '<a href="' . $prevUrl . '" class="prev">&laquo; Предыдущая</a>';
        }
        
        // Номера страниц
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $page) {
                $html .= '<span class="current">' . $i . '</span>';
            } else {
                $pageUrl = str_replace('{page}', $i, $url);
                $html .= '<a href="' . $pageUrl . '">' . $i . '</a>';
            }
        }
        
        // Следующая страница
        if ($page < $totalPages) {
            $nextUrl = str_replace('{page}', $page + 1, $url);
            $html .= '<a href="' . $nextUrl . '" class="next">Следующая &raquo;</a>';
        }
        
        $html .= '</div>';
        return $html;
    }
} 