<?php

class mainController extends BaseController {
    private $limit = 10; // Изменено с 4 на 10
    
    public function index($page = 1) {
        // Загружаем модели
        $newsModel = $this->loadModel('news');
        $zamenaModel = $this->loadModel('zamena');
        
        // Получаем новости
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
            'news' => $news,
            'pagination' => $pagination,
            'lastzamena' => $lastzamena,
            'status1' => $status1,
            'status3' => $status3,
            'total_news' => $total,
            'current_page' => $page
        ];
        
        return $this->render('mane/mane', $data);
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
        $filesFile = "application/views/allfile.dat";
        $results = [];
        
        if (!file_exists($filesFile)) {
            return $results;
        }
        
        $files = file($filesFile);
        $totalFound = 0;
        
        // Сначала подсчитываем общее количество найденных файлов
        foreach ($files as $file) {
            $content = file_get_contents(trim($file));
            if (strpos(mb_strtolower($content), mb_strtolower($searchText)) !== false) {
                $totalFound++;
            }
        }
        
        // Теперь формируем результаты
        foreach ($files as $file) {
            $content = file_get_contents(trim($file));
            if (strpos(mb_strtolower($content), mb_strtolower($searchText)) !== false) {
                $fullstr = mb_strtolower(mb_substr(
                    preg_replace('/[^а-яА-Я,.]/ui', ' ', 
                    stristr(mb_strtolower($content), mb_strtolower($searchText))), 0, 500));
                
                $fullstr2 = $this->mb_ucfirst($fullstr);
                
                $silka = mb_substr(trim($file), 18);
                $extension = trim($silka, ".php");
                
                $mytext = preg_replace('/'.$searchText.'/', 
                    '<span style="background-color: #ffe403;">'.$searchText.'</span>', $fullstr2);
                $mytext2 = preg_replace('/'.$this->mb_ucfirst($searchText).'/', 
                    '<span style="background-color: #ffe403;">'.$this->mb_ucfirst($searchText).'</span>', $mytext);
                
                $results[] = [
                    'title' => "<h3>".trim($file)."</h3>",
                    'stroka' => $searchText,
                    'total' => $totalFound,
                    'fullstr' => $mytext2,
                    'silks' => $extension,
                ];
            }
        }
        
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