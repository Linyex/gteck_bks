<?php

class mainController extends BaseController {
    private $limit = 10; // Изменено с 4 на 10
    
    public function index() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            // Получаем важные новости для слайдера
            $importantNews = Database::fetchAll("
                SELECT n.*, nc.name as category_name 
                FROM news n 
                LEFT JOIN news_categories nc ON n.category_id = nc.id 
                WHERE nc.type = 'important' AND nc.is_active = 1 
                ORDER BY n.news_date_add DESC 
                LIMIT 5
            ");
            
            // Получаем обычные новости для сетки
            $regularNews = Database::fetchAll("
                SELECT n.*, nc.name as category_name 
                FROM news n 
                LEFT JOIN news_categories nc ON n.category_id = nc.id 
                WHERE nc.type = 'regular' AND nc.is_active = 1 
                ORDER BY n.news_date_add DESC 
                LIMIT 12
            ");
            
            // Получаем статистику
            $stats = [
                'years' => 80,
                'specialties' => 4,
                'graduates' => 30
            ];
            
            return $this->render('main/index', [
                'title' => 'Главная',
                'importantNews' => $importantNews,
                'regularNews' => $regularNews,
                'stats' => $stats
            ]);
        } catch (Exception $e) {
            return $this->render('error/error', [
                'title' => 'Ошибка',
                'message' => 'Не удалось загрузить данные: ' . $e->getMessage()
            ]);
        }
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