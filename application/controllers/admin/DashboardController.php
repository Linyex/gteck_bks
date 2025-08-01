<?php

// Подключаем необходимые файлы
require_once ENGINE_DIR . 'BaseController.php';
require_once APPLICATION_DIR . 'controllers/admin/BaseAdminController.php';

class DashboardController extends BaseAdminController {
    
    public function index() {
        // Получаем статистику для дашборда
        $stats = $this->getDashboardStats();
        $serverInfo = $this->getServerInfo();
        $siteStatus = $this->getSiteStatus();
        
        $this->render('admin/dashboard/index', [
            'title' => 'Дашборд',
            'currentPage' => 'dashboard',
            'totalUsers' => $stats['users'],
            'totalNews' => $stats['news'],
            'totalFiles' => $stats['files'],
            'totalPhotos' => $stats['photos'],
            'serverInfo' => $serverInfo,
            'siteStatus' => $siteStatus,
            'recentActivity' => $this->getRecentActivity(),
            'additional_css' => [
                '/assets/css/admin-cyberpunk.css'
            ],
            'additional_js' => [
                '/assets/js/background-animations.js',
                '/assets/js/admin-common.js',
                '/assets/js/dashboard-animations.js'
            ]
        ]);
    }
    
    private function getDashboardStats() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
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
    
    private function getServerInfo() {
        $info = [];
        
        // Получаем информацию о диске
        $diskTotal = disk_total_space('.');
        $diskFree = disk_free_space('.');
        $diskUsed = $diskTotal - $diskFree;
        $diskPercent = round(($diskUsed / $diskTotal) * 100, 2);
        
        $info['disk'] = [
            'total' => $this->formatBytes($diskTotal),
            'used' => $this->formatBytes($diskUsed),
            'free' => $this->formatBytes($diskFree),
            'percent' => $diskPercent
        ];
        
        // Получаем информацию о памяти
        $memoryLimit = ini_get('memory_limit');
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        
        $info['memory'] = [
            'limit' => $memoryLimit,
            'usage' => $this->formatBytes($memoryUsage),
            'peak' => $this->formatBytes($memoryPeak)
        ];
        
        // Получаем информацию о PHP
        $info['php'] = [
            'version' => PHP_VERSION,
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size')
        ];
        
        return $info;
    }
    
    private function getSiteStatus() {
        $status = [];
        
        // Проверяем доступность базы данных
        try {
            require_once ENGINE_DIR . 'main/db.php';
            Database::fetchOne("SELECT 1");
            $status['database'] = 'online';
        } catch (Exception $e) {
            $status['database'] = 'offline';
        }
        
        // Проверяем доступность основных файлов
        $criticalFiles = [
            'index.php',
            'application/config.php',
            'engine/main/db.php'
        ];
        
        $status['files'] = 'online';
        foreach ($criticalFiles as $file) {
            if (!file_exists($file)) {
                $status['files'] = 'offline';
                break;
            }
        }
        
        // Проверяем права на запись
        $writableDirs = [
            'uploads',
            'uploads/news',
            'uploads/photos'
        ];
        
        $status['permissions'] = 'ok';
        foreach ($writableDirs as $dir) {
            if (!is_dir($dir) || !is_writable($dir)) {
                $status['permissions'] = 'error';
                break;
            }
        }
        
        // Общий статус
        if ($status['database'] === 'online' && $status['files'] === 'online' && $status['permissions'] === 'ok') {
            $status['overall'] = 'online';
        } else {
            $status['overall'] = 'offline';
        }
        
        return $status;
    }
    
    private function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    private function getRecentActivity() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $activities = [];
            
            // Получаем последние новости
            $recentNews = Database::fetchAll("SELECT * FROM news ORDER BY news_date_add DESC LIMIT 3");
            foreach ($recentNews as $news) {
                $activities[] = [
                    'icon' => 'newspaper',
                    'description' => 'Создана новость: ' . ($news['news_title'] ?? 'Без названия'),
                    'time' => date('d.m.Y H:i', strtotime($news['news_date_add'] ?? 'now'))
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
            
            return array_slice($activities, 0, 5);
        } catch (Exception $e) {
            return [];
        }
    }
} 