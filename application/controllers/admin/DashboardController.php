<?php

class DashboardController extends BaseAdminController {
    
    public function index() {
        // Получаем статистику для дашборда
        $stats = $this->getDashboardStats();
        
        return $this->render('admin/dashboard/index', [
            'title' => 'Дашборд',
            'currentPage' => 'dashboard',
            'totalUsers' => $stats['users'],
            'totalNews' => $stats['news'],
            'totalFiles' => $stats['files'],
            'totalPhotos' => $stats['photos'],
            'recentActivity' => $this->getRecentActivity()
        ]);
    }
    
    private function getDashboardStats() {
        try {
            require_once 'engine/main/db.php';
            
            $stats = [];
            
            // Количество пользователей
            $usersCount = Database::fetchOne("SELECT COUNT(*) as count FROM users");
            $stats['users'] = $usersCount['count'] ?? 0;
            
            // Количество новостей
            $newsCount = Database::fetchOne("SELECT COUNT(*) as count FROM news");
            $stats['news'] = $newsCount['count'] ?? 0;
            
            // Количество файлов
            $filesCount = Database::fetchOne("SELECT COUNT(*) as count FROM files");
            $stats['files'] = $filesCount['count'] ?? 0;
            
            // Количество фотографий
            $photosCount = Database::fetchOne("SELECT COUNT(*) as count FROM photos");
            $stats['photos'] = $photosCount['count'] ?? 0;
            
            return $stats;
        } catch (Exception $e) {
            return [
                'users' => 0,
                'news' => 0,
                'files' => 0,
                'photos' => 0
            ];
        }
    }
    
    private function getRecentActivity() {
        try {
            require_once 'engine/main/db.php';
            
            $activities = [];
            
            // Получаем последние новости
            $recentNews = Database::fetchAll("SELECT * FROM news ORDER BY news_date DESC LIMIT 3");
            foreach ($recentNews as $news) {
                $activities[] = [
                    'icon' => 'newspaper',
                    'description' => 'Создана новость: ' . ($news['news_title'] ?? 'Без названия'),
                    'time' => date('d.m.Y H:i', strtotime($news['news_date'] ?? 'now'))
                ];
            }
            
            // Получаем последних пользователей
            $recentUsers = Database::fetchAll("SELECT * FROM users ORDER BY user_date_reg DESC LIMIT 3");
            foreach ($recentUsers as $user) {
                $activities[] = [
                    'icon' => 'user-plus',
                    'description' => 'Зарегистрирован пользователь: ' . ($user['user_fio'] ?? 'Без имени'),
                    'time' => date('d.m.Y H:i', strtotime($user['user_date_reg'] ?? 'now'))
                ];
            }
            
            // Получаем последние файлы
            $recentFiles = Database::fetchAll("SELECT * FROM files ORDER BY files_date_add DESC LIMIT 2");
            foreach ($recentFiles as $file) {
                $activities[] = [
                    'icon' => 'file-upload',
                    'description' => 'Загружен файл: ' . ($file['files_text'] ?? 'Без названия'),
                    'time' => date('d.m.Y H:i', strtotime($file['files_date_add'] ?? 'now'))
                ];
            }
            
            // Сортируем по времени (новые сначала)
            usort($activities, function($a, $b) {
                return strtotime($b['time']) - strtotime($a['time']);
            });
            
            // Возвращаем только последние 8 активностей
            return array_slice($activities, 0, 8);
            
        } catch (Exception $e) {
            return [];
        }
    }
} 