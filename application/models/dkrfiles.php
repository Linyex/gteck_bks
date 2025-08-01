<?php

class dkrfilesModel {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
	
	/**
	 * Получить все файлы с информацией о группах
	 */
	public function getAllFilesWithGroups() {
		$sql = "SELECT f.*, GROUP_CONCAT(j.group_name) as group_names 
				FROM dkrfiles f 
				LEFT JOIN dkrjointable j ON f.id = j.fileid 
				GROUP BY f.id 
				ORDER BY f.upload_date DESC";
		
        return $this->db->fetchAll($sql);
	}
	
	/**
	 * Получить файлы по группе
	 */
	public function getFilesByGroup($group_name) {
		$sql = "SELECT f.* 
				FROM dkrfiles f 
				JOIN dkrjointable j ON f.id = j.fileid 
                WHERE j.group_name = :group_name 
				ORDER BY f.filename";
		
        return $this->db->fetchAll($sql, ['group_name' => $group_name]);
	}
	
	/**
	 * Получить файл по ID
	 */
	public function getFileById($file_id) {
		$sql = "SELECT f.*, j.group_name 
				FROM dkrfiles f 
				LEFT JOIN dkrjointable j ON f.id = j.fileid 
                WHERE f.id = :file_id";
        
        return $this->db->fetch($sql, ['file_id' => (int)$file_id]);
    }
    
    /**
     * Получить все группы
     */
    public function getAllGroups() {
        $sql = "SELECT group_name FROM group_passwords WHERE is_active = 1 ORDER BY group_name";
        return $this->db->fetchAll($sql);
	}

	/**
	 * Загрузить файл
	 */
	public function uploadFile($file, $group_name) {
		try {
			// Абсолютный путь для загрузки
			$upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/assets/files/kontrolnui/';
			
			if (!is_dir($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			
			// Оригинальное имя файла, защита от коллизий
			$filename = basename($file['name']);
            $filename = preg_replace('/[^A-Za-z0-9_\-\.\sА-Яа-яЁё]/u', '', $filename);
			$target_path = $upload_dir . $filename;
			$web_path = '/assets/files/kontrolnui/' . $filename;
			
			$i = 1;
			while (file_exists($target_path)) {
				$filename_parts = pathinfo($filename);
				$filename = $filename_parts['filename'] . "_" . $i . "." . $filename_parts['extension'];
				$target_path = $upload_dir . $filename;
				$web_path = '/assets/files/kontrolnui/' . $filename;
				$i++;
            }

			// Перемещаем загруженный файл
			if (!move_uploaded_file($file['tmp_name'], $target_path)) {
                return ['success' => false, 'error' => 'Ошибка при сохранении файла'];
			}

			// Сохраняем информацию о файле в базу данных
            $file_id = $this->db->insert('dkrfiles', [
                'filename' => $filename,
                'path' => $web_path,
                'upload_date' => date('Y-m-d H:i:s')
            ]);

			// Связываем файл с группой
            $this->db->insert('dkrjointable', [
                'fileid' => $file_id,
                'group_name' => $group_name
            ]);
            
            return ['success' => true, 'file_id' => $file_id];

		} catch (Exception $e) {
            return ['success' => false, 'error' => 'Ошибка базы данных: ' . $e->getMessage()];
		}
	}
	
	/**
	 * Удалить файл
	 */
	public function deleteFile($file_id) {
		try {
			// Получаем информацию о файле
			$file = $this->getFileById($file_id);
			
			if (!$file) {
                return ['success' => false, 'error' => 'Файл не найден'];
			}
			
            // Удаляем физический файл
            $file_path = ltrim($file['path'], '/');
			if (file_exists($file_path)) {
				unlink($file_path);
			}
			
			// Удаляем связи с группами
            $this->db->delete('dkrjointable', 'fileid = :fileid', ['fileid' => (int)$file_id]);
			
			// Удаляем запись о файле
            $this->db->delete('dkrfiles', 'id = :id', ['id' => (int)$file_id]);
			
            return ['success' => true];
			
		} catch (Exception $e) {
            return ['success' => false, 'error' => 'Ошибка при удалении файла: ' . $e->getMessage()];
		}
	}
	
	/**
	 * Получить общее количество файлов
	 */
    public function getTotalFiles() {
        $sql = "SELECT COUNT(*) AS count FROM dkrfiles";
        $result = $this->db->fetch($sql);
        return $result['count'];
	}
}
?> 