<?php

class newsController extends BaseController {
    private $limit = 4;
    
    public function index() {
        return $this->mane();
    }
    
    public function mane($page = 1) {
        // Включаем кэширование для улучшения производительности
        header('Cache-Control: public, max-age=3600');
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s \G\M\T', time()));
        
        $newsModel = $this->loadModel('news');
        
        $sort = ['news_date_add' => 'DESC'];
        $options = [
            'start' => ((int)$page - 1) * $this->limit,
            'limit' => $this->limit
        ];
        
        $total = $newsModel->getTotalnews();
        $news = $newsModel->getnews([], ['category'], $sort, $options);
        
        $pagination = $this->createPagination($total, $page, $this->limit, '/news/page/{page}');
        
        return $this->render('news/mane', [
            'news' => $news,
            'pagination' => $pagination,
            'title' => 'Новости'
        ]);
    }
    
    public function view($newsid = null) {
        // Включаем кэширование для улучшения производительности
        header('Cache-Control: public, max-age=3600');
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s \G\M\T', time()));
        
        if (!$newsid) {
            return $this->redirect('/news');
        }
        
        $newsModel = $this->loadModel('news');
        
        // Проверяем существование новости
        $news = $newsModel->getNewsById($newsid, ['category']);
        if (!$news) {
            return $this->redirect('/news');
        }
        
        return $this->render('news/view', [
            'news' => $news,
            'title' => $news['news_title']
        ]);
    }
    
    public function category($categoryname = null, $page = 1) {
        // Отключаем кеширование для разработки
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        if (!$categoryname) {
            return $this->redirect('/news');
        }
        
        $newsModel = $this->loadModel('news');
        
        // Проверяем существование категории
        $category = $newsModel->getCategoryByName($categoryname);
        if (!$category) {
            return $this->redirect('/news');
        }
        
        $total = $newsModel->getTotalNewsCat($categoryname, ['category']);
        
        if (empty($total)) {
            return $this->redirect('/news');
        }
        
        $sort = ['news_date_add' => 'DESC'];
        $options = [
            'start' => ((int)$page - 1) * $this->limit,
            'limit' => $this->limit
        ];
        
        $news = $newsModel->getnews($categoryname, ['category'], $sort, $options);
        
        $pagination = $this->createPagination($total, $page, $this->limit, "/news/category/{$categoryname}/page/{page}");
        
        $namecat = '';
        if (!empty($news)) {
            $namecat = $news[0]['category_text'];
        }
        
        return $this->render('news/category', [
            'news' => $news,
            'pagination' => $pagination,
            'namecat' => $namecat,
            'title' => "Новости - {$namecat}"
        ]);
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
