<?php

class newsController extends BaseController {
    private $limit = 4;
    
    public function index() {
        return $this->mane();
    }
    
    public function mane($page = 1) {
        try {
            // Включаем кэширование для улучшения производительности
            header('Cache-Control: public, max-age=3600');
            header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s \G\M\T', time()));
            
            $newsModel = $this->loadModel('news');
            // Загружаем категории для сайдбара
            $categories = method_exists($newsModel, 'getAllCategories') ? ($newsModel->getAllCategories() ?? []) : [];
            
            // Используем новый метод для получения новостей с пагинацией
            $news = $newsModel->getAllNewsWithPagination($page, $this->limit);
            $total = $newsModel->getTotalnews();
            
            // Исправляем URL для пагинации
            $pagination = $this->createPagination($total, $page, $this->limit, '/news?page={page}');
            
            return $this->render('news/mane', [
                'news' => $news ?? [],
                'pagination' => $pagination,
                'title' => 'Новости',
                'currentPage' => $page,
                'totalNews' => $total,
                'newsModel' => $newsModel, // Передаем модель в представление
                'categories' => $categories,
                'additional_css' => [
                    '/assets/css/news-modern.css'
                ],
                'additional_js' => [
                    '/assets/js/news-functions.js'
                ]
            ]);
        } catch (Exception $e) {
            error_log('Error in news controller: ' . $e->getMessage());
            return $this->render('news/mane', [
                'news' => [],
                'pagination' => '',
                'title' => 'Новости',
                'error' => 'Не удалось загрузить новости',
                'newsModel' => $this->loadModel('news'), // Передаем модель даже при ошибке
                'categories' => [],
                'additional_css' => [
                    '/assets/css/news-modern.css'
                ],
                'additional_js' => [
                    '/assets/js/news-functions.js'
                ]
            ]);
        }
    }
    
    public function view($newsId = null) {
        try {
            // Проверяем, что передан ID новости
            if (!$newsId) {
                header('Location: /news');
                exit;
            }
            
            $newsModel = $this->loadModel('news');
            
            // Сначала пробуем получить новость с дополнительными изображениями
            $news = $newsModel->getNewsWithImages($newsId, ['category']);
            
            // Если не получилось, получаем обычную новость
            if (!$news) {
                $news = $newsModel->getNewsById($newsId, ['category']);
                if ($news) {
                    $news['additional_images'] = []; // Пустой массив для дополнительных изображений
                }
            }
            
            if (!$news) {
                header('Location: /news');
                exit;
            }
            
            return $this->render('news/view', [
                'title' => $news['news_title'],
                'news' => $news,
                'newsModel' => $newsModel, // Передаем модель в представление
                'additional_css' => [
                    '/assets/css/news-modern.css'
                ],
                'additional_js' => [
                    '/assets/js/news-functions.js'
                ]
            ]);
        } catch (Exception $e) {
            error_log('Error in news view: ' . $e->getMessage());
            header('Location: /news');
            exit;
        }
    }
    
    public function category($categoryname = null, $page = 1) {
        try {
            // Отключаем кеширование для разработки
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            if (!$categoryname) {
                return $this->redirect('/news');
            }
            
            $newsModel = $this->loadModel('news');
            // Загружаем категории для сайдбара
            $categories = method_exists($newsModel, 'getAllCategories') ? ($newsModel->getAllCategories() ?? []) : [];
            
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
            
            // Исправляем URL для пагинации
            $pagination = $this->createPagination($total, $page, $this->limit, "/news/category/{$categoryname}?page={page}");
            
            $namecat = '';
            if (!empty($news)) {
                $namecat = $news[0]['category_name'] ?? 'Категория';
            }
            
            return $this->render('news/category', [
                'news' => $news ?? [],
                'pagination' => $pagination,
                'namecat' => $namecat,
                'title' => "Новости - {$namecat}",
                'newsModel' => $newsModel, // Передаем модель в представление
                'categories' => $categories,
                'additional_css' => [
                    '/assets/css/news-modern.css'
                ],
                'additional_js' => [
                    '/assets/js/news-functions.js'
                ]
            ]);
        } catch (Exception $e) {
            error_log('Error in news category: ' . $e->getMessage());
            return $this->redirect('/news');
        }
    }
    
    public function search() {
        if (!$this->isAjax() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['success' => false, 'error' => 'Invalid request']);
        }
        
        try {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            $searchTerm = $data['search'] ?? '';
            
            if (mb_strlen($searchTerm) < 3) {
                return $this->jsonResponse([
                    'success' => false, 
                    'error' => 'Поисковый запрос должен содержать минимум 3 символа'
                ]);
            }
            
            $newsModel = $this->loadModel('news');
            $news = $newsModel->searchNews($searchTerm, 20);
            
            return $this->jsonResponse([
                'success' => true,
                'news' => $news
            ]);
            
        } catch (Exception $e) {
            error_log('Error in news search: ' . $e->getMessage());
            return $this->jsonResponse([
                'success' => false, 
                'error' => 'Ошибка при выполнении поиска'
            ]);
        }
    }
    
    private function createPagination($total, $page, $limit, $url) {
        if ($total <= $limit) {
            return '';
        }
        
        $totalPages = ceil($total / $limit);
        $currentPage = max(1, min($page, $totalPages));
        
        $html = '<nav aria-label="Навигация по страницам"><ul class="pagination">';
        
        // Кнопка "Предыдущая"
        if ($currentPage > 1) {
            $prevUrl = str_replace('{page}', $currentPage - 1, $url);
            $html .= '<li class="page-item"><a class="page-link" href="' . $prevUrl . '">&laquo;</a></li>';
        }
        
        // Номера страниц
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
        
        for ($i = $start; $i <= $end; $i++) {
            $pageUrl = str_replace('{page}', $i, $url);
            $activeClass = ($i == $currentPage) ? ' active' : '';
            $html .= '<li class="page-item' . $activeClass . '"><a class="page-link" href="' . $pageUrl . '">' . $i . '</a></li>';
        }
        
        // Кнопка "Следующая"
        if ($currentPage < $totalPages) {
            $nextUrl = str_replace('{page}', $currentPage + 1, $url);
            $html .= '<li class="page-item"><a class="page-link" href="' . $nextUrl . '">&raquo;</a></li>';
        }
        
        $html .= '</ul></nav>';
        
        return $html;
    }
    
    /**
     * Проверяет существование изображения и возвращает правильный путь
     */
    private function getImagePath($imagePath) {
        if (empty($imagePath)) {
            return false;
        }
        
        // Если путь уже полный (начинается с /)
        if (strpos($imagePath, '/') === 0) {
            $fullPath = substr($imagePath, 1);
        } else {
            $fullPath = $imagePath;
        }
        
        // Проверяем существование файла
        if (file_exists($fullPath)) {
            return $imagePath;
        }
        
        return false;
    }
}
