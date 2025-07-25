<?php

/**
 * Сервис резервного копирования
 * Обеспечивает создание и восстановление резервных копий базы данных и файлов
 */
class BackupService {
    
    private $backupDir = 'backups/';
    private $maxBackups = 10;
    private $compressionLevel = 9;
    
    public function __construct() {
        // Создаем директорию для бэкапов если не существует
        if (!is_dir($this->backupDir)) {
            mkdir($this->backupDir, 0755, true);
        }
    }
    
    /**
     * Создает полный бэкап системы
     */
    public function createFullBackup($description = '') {
        try {
            $timestamp = date('Y-m-d_H-i-s');
            $backupName = "full_backup_{$timestamp}";
            $backupPath = $this->backupDir . $backupName;
            
            // Создаем директорию для бэкапа
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            // Бэкап базы данных
            $dbBackup = $this->backupDatabase($backupPath);
            
            // Бэкап файлов
            $filesBackup = $this->backupFiles($backupPath);
            
            // Создаем метаданные бэкапа
            $metadata = [
                'backup_name' => $backupName,
                'created_at' => date('Y-m-d H:i:s'),
                'description' => $description,
                'type' => 'full',
                'database_size' => $dbBackup['size'],
                'files_size' => $filesBackup['size'],
                'total_size' => $dbBackup['size'] + $filesBackup['size'],
                'created_by' => $_SESSION['admin_user_id'] ?? null
            ];
            
            file_put_contents($backupPath . '/metadata.json', json_encode($metadata, JSON_PRETTY_PRINT));
            
            // Создаем архив
            $archivePath = $this->createArchive($backupPath, $backupName);
            
            // Удаляем временную директорию
            $this->removeDirectory($backupPath);
            
            // Очищаем старые бэкапы
            $this->cleanOldBackups();
            
            return [
                'success' => true,
                'backup_name' => $backupName,
                'archive_path' => $archivePath,
                'size' => filesize($archivePath),
                'metadata' => $metadata
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Создает бэкап только базы данных
     */
    public function createDatabaseBackup($description = '') {
        try {
            $timestamp = date('Y-m-d_H-i-s');
            $backupName = "db_backup_{$timestamp}";
            $backupPath = $this->backupDir . $backupName;
            
            // Создаем директорию для бэкапа
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            // Бэкап базы данных
            $dbBackup = $this->backupDatabase($backupPath);
            
            // Создаем метаданные бэкапа
            $metadata = [
                'backup_name' => $backupName,
                'created_at' => date('Y-m-d H:i:s'),
                'description' => $description,
                'type' => 'database',
                'database_size' => $dbBackup['size'],
                'created_by' => $_SESSION['admin_user_id'] ?? null
            ];
            
            file_put_contents($backupPath . '/metadata.json', json_encode($metadata, JSON_PRETTY_PRINT));
            
            // Создаем архив
            $archivePath = $this->createArchive($backupPath, $backupName);
            
            // Удаляем временную директорию
            $this->removeDirectory($backupPath);
            
            // Очищаем старые бэкапы
            $this->cleanOldBackups();
            
            return [
                'success' => true,
                'backup_name' => $backupName,
                'archive_path' => $archivePath,
                'size' => filesize($archivePath),
                'metadata' => $metadata
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Создает бэкап только файлов
     */
    public function createFilesBackup($description = '') {
        try {
            $timestamp = date('Y-m-d_H-i-s');
            $backupName = "files_backup_{$timestamp}";
            $backupPath = $this->backupDir . $backupName;
            
            // Создаем директорию для бэкапа
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            // Бэкап файлов
            $filesBackup = $this->backupFiles($backupPath);
            
            // Создаем метаданные бэкапа
            $metadata = [
                'backup_name' => $backupName,
                'created_at' => date('Y-m-d H:i:s'),
                'description' => $description,
                'type' => 'files',
                'files_size' => $filesBackup['size'],
                'created_by' => $_SESSION['admin_user_id'] ?? null
            ];
            
            file_put_contents($backupPath . '/metadata.json', json_encode($metadata, JSON_PRETTY_PRINT));
            
            // Создаем архив
            $archivePath = $this->createArchive($backupPath, $backupName);
            
            // Удаляем временную директорию
            $this->removeDirectory($backupPath);
            
            // Очищаем старые бэкапы
            $this->cleanOldBackups();
            
            return [
                'success' => true,
                'backup_name' => $backupName,
                'archive_path' => $archivePath,
                'size' => filesize($archivePath),
                'metadata' => $metadata
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Восстанавливает бэкап
     */
    public function restoreBackup($backupPath) {
        try {
            if (!file_exists($backupPath)) {
                throw new Exception('Backup file not found');
            }
            
            // Создаем временную директорию для распаковки
            $tempDir = $this->backupDir . 'temp_' . uniqid();
            mkdir($tempDir, 0755, true);
            
            // Распаковываем архив
            $this->extractArchive($backupPath, $tempDir);
            
            // Читаем метаданные
            $metadataPath = $tempDir . '/metadata.json';
            if (!file_exists($metadataPath)) {
                throw new Exception('Backup metadata not found');
            }
            
            $metadata = json_decode(file_get_contents($metadataPath), true);
            
            // Восстанавливаем в зависимости от типа бэкапа
            if ($metadata['type'] === 'full' || $metadata['type'] === 'database') {
                $this->restoreDatabase($tempDir);
            }
            
            if ($metadata['type'] === 'full' || $metadata['type'] === 'files') {
                $this->restoreFiles($tempDir);
            }
            
            // Удаляем временную директорию
            $this->removeDirectory($tempDir);
            
            return [
                'success' => true,
                'backup_name' => $metadata['backup_name'],
                'restored_at' => date('Y-m-d H:i:s')
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Получает список доступных бэкапов
     */
    public function getBackupsList() {
        $backups = [];
        
        if (!is_dir($this->backupDir)) {
            return $backups;
        }
        
        $files = glob($this->backupDir . '*.zip');
        
        foreach ($files as $file) {
            $backupName = basename($file, '.zip');
            $backupInfo = $this->getBackupInfo($file);
            
            if ($backupInfo) {
                $backups[] = $backupInfo;
            }
        }
        
        // Сортируем по дате создания (новые сначала)
        usort($backups, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $backups;
    }
    
    /**
     * Удаляет бэкап
     */
    public function deleteBackup($backupName) {
        try {
            $backupPath = $this->backupDir . $backupName . '.zip';
            
            if (!file_exists($backupPath)) {
                throw new Exception('Backup not found');
            }
            
            if (unlink($backupPath)) {
                return [
                    'success' => true,
                    'message' => 'Backup deleted successfully'
                ];
            } else {
                throw new Exception('Failed to delete backup');
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Бэкап базы данных
     */
    private function backupDatabase($backupPath) {
        require_once ENGINE_DIR . 'main/db.php';
        
        $dbFile = $backupPath . '/database.sql';
        $size = 0;
        
        // Получаем все таблицы
        $tables = Database::fetchAll("SHOW TABLES");
        
        $sql = "-- Database Backup\n";
        $sql .= "-- Created: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Database: " . Database::getDatabaseName() . "\n\n";
        
        foreach ($tables as $table) {
            $tableName = array_values($table)[0];
            
            // Получаем структуру таблицы
            $createTable = Database::fetchOne("SHOW CREATE TABLE `$tableName`");
            $sql .= "\n-- Table structure for `$tableName`\n";
            $sql .= "DROP TABLE IF EXISTS `$tableName`;\n";
            $sql .= $createTable['Create Table'] . ";\n\n";
            
            // Получаем данные таблицы
            $rows = Database::fetchAll("SELECT * FROM `$tableName`");
            
            if (!empty($rows)) {
                $sql .= "-- Data for table `$tableName`\n";
                $sql .= "INSERT INTO `$tableName` VALUES\n";
                
                $values = [];
                foreach ($rows as $row) {
                    $rowValues = [];
                    foreach ($row as $value) {
                        if ($value === null) {
                            $rowValues[] = 'NULL';
                        } else {
                            $rowValues[] = "'" . addslashes($value) . "'";
                        }
                    }
                    $values[] = "(" . implode(', ', $rowValues) . ")";
                }
                
                $sql .= implode(",\n", $values) . ";\n\n";
            }
        }
        
        file_put_contents($dbFile, $sql);
        $size = filesize($dbFile);
        
        return [
            'file' => $dbFile,
            'size' => $size
        ];
    }
    
    /**
     * Бэкап файлов
     */
    private function backupFiles($backupPath) {
        $filesDir = $backupPath . '/files';
        mkdir($filesDir, 0755, true);
        
        $size = 0;
        $directories = ['uploads', 'assets', 'application/views'];
        
        foreach ($directories as $dir) {
            if (is_dir($dir)) {
                $this->copyDirectory($dir, $filesDir . '/' . $dir);
                $size += $this->getDirectorySize($dir);
            }
        }
        
        return [
            'directory' => $filesDir,
            'size' => $size
        ];
    }
    
    /**
     * Создает архив
     */
    private function createArchive($sourcePath, $backupName) {
        $archivePath = $this->backupDir . $backupName . '.zip';
        
        $zip = new ZipArchive();
        if ($zip->open($archivePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            throw new Exception('Cannot create ZIP archive');
        }
        
        $this->addFolderToZip($zip, $sourcePath, basename($sourcePath));
        $zip->close();
        
        return $archivePath;
    }
    
    /**
     * Распаковывает архив
     */
    private function extractArchive($archivePath, $extractPath) {
        $zip = new ZipArchive();
        if ($zip->open($archivePath) !== TRUE) {
            throw new Exception('Cannot open ZIP archive');
        }
        
        $zip->extractTo($extractPath);
        $zip->close();
    }
    
    /**
     * Восстанавливает базу данных
     */
    private function restoreDatabase($backupPath) {
        $dbFile = $backupPath . '/database.sql';
        
        if (!file_exists($dbFile)) {
            throw new Exception('Database backup file not found');
        }
        
        require_once ENGINE_DIR . 'main/db.php';
        
        $sql = file_get_contents($dbFile);
        $queries = explode(';', $sql);
        
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                Database::execute($query);
            }
        }
    }
    
    /**
     * Восстанавливает файлы
     */
    private function restoreFiles($backupPath) {
        $filesDir = $backupPath . '/files';
        
        if (!is_dir($filesDir)) {
            return; // Нет файлов для восстановления
        }
        
        $this->copyDirectory($filesDir, '.');
    }
    
    /**
     * Получает информацию о бэкапе
     */
    private function getBackupInfo($backupPath) {
        try {
            $tempDir = $this->backupDir . 'temp_info_' . uniqid();
            mkdir($tempDir, 0755, true);
            
            $this->extractArchive($backupPath, $tempDir);
            
            $metadataPath = $tempDir . '/metadata.json';
            if (!file_exists($metadataPath)) {
                $this->removeDirectory($tempDir);
                return null;
            }
            
            $metadata = json_decode(file_get_contents($metadataPath), true);
            $metadata['file_size'] = filesize($backupPath);
            $metadata['file_path'] = $backupPath;
            
            $this->removeDirectory($tempDir);
            
            return $metadata;
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Копирует директорию
     */
    private function copyDirectory($source, $destination) {
        if (!is_dir($source)) {
            return false;
        }
        
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        
        $files = scandir($source);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $sourcePath = $source . '/' . $file;
            $destPath = $destination . '/' . $file;
            
            if (is_dir($sourcePath)) {
                $this->copyDirectory($sourcePath, $destPath);
            } else {
                copy($sourcePath, $destPath);
            }
        }
        
        return true;
    }
    
    /**
     * Получает размер директории
     */
    private function getDirectorySize($path) {
        $size = 0;
        
        if (!is_dir($path)) {
            return $size;
        }
        
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $filePath = $path . '/' . $file;
            
            if (is_dir($filePath)) {
                $size += $this->getDirectorySize($filePath);
            } else {
                $size += filesize($filePath);
            }
        }
        
        return $size;
    }
    
    /**
     * Добавляет папку в ZIP
     */
    private function addFolderToZip($zip, $folder, $exclusiveLength) {
        $fileInfos = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folder),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($fileInfos as $path => $fileInfo) {
            if (!$fileInfo->isDir()) {
                $filePath = $fileInfo->getRealPath();
                $relativePath = substr($filePath, $exclusiveLength);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }
    
    /**
     * Удаляет директорию рекурсивно
     */
    private function removeDirectory($path) {
        if (!is_dir($path)) {
            return;
        }
        
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $filePath = $path . '/' . $file;
            
            if (is_dir($filePath)) {
                $this->removeDirectory($filePath);
            } else {
                unlink($filePath);
            }
        }
        
        rmdir($path);
    }
    
    /**
     * Очищает старые бэкапы
     */
    private function cleanOldBackups() {
        $backups = $this->getBackupsList();
        
        if (count($backups) > $this->maxBackups) {
            $toDelete = array_slice($backups, $this->maxBackups);
            
            foreach ($toDelete as $backup) {
                if (file_exists($backup['file_path'])) {
                    unlink($backup['file_path']);
                }
            }
        }
    }
} 