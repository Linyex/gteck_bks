<?php

class groupsModel extends Model {
	
	/**
	 * Получить все группы
	 */
	public function getAllGroups() {
		$sql = "SELECT * FROM dkrgroups ORDER BY groupname";
		$result = $this->db->query($sql);
		return $result->rows;
	}

	/**
	 * Получить группу по ID
	 */
	public function getGroupById($group_id) {
		$sql = "SELECT * FROM dkrgroups WHERE id_group = '" . (int)$group_id . "'";
		$result = $this->db->query($sql);
		return $result->row;
	}
	
	/**
	 * Получить группу по названию
	 */
	public function getGroupByName($groupname) {
		$groupname = $this->db->escape($groupname);
		$sql = "SELECT * FROM dkrgroups WHERE groupname = '$groupname'";
		$result = $this->db->query($sql);
		return $result->row;
	}
	
	/**
	 * Создать новую группу
	 */
	public function createGroup($groupname) {
		$groupname = $this->db->escape($groupname);
		$sql = "INSERT INTO dkrgroups (groupname) VALUES ('$groupname')";
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	/**
	 * Обновить группу
	 */
	public function updateGroup($group_id, $groupname) {
		$groupname = $this->db->escape($groupname);
		$sql = "UPDATE dkrgroups SET groupname = '$groupname' WHERE id_group = '" . (int)$group_id . "'";
		return $this->db->query($sql);
	}
	
	/**
	 * Удалить группу
	 */
	public function deleteGroup($group_id) {
		// Сначала удаляем все связи с файлами
		$sql = "DELETE FROM dkrjointable WHERE groupid = '" . (int)$group_id . "'";
		$this->db->query($sql);
		
		// Затем удаляем саму группу
		$sql = "DELETE FROM dkrgroups WHERE id_group = '" . (int)$group_id . "'";
		return $this->db->query($sql);
	}
	
	/**
	 * Получить группы с количеством файлов
	 */
	public function getGroupsWithFileCount() {
		$sql = "SELECT g.*, COUNT(j.fileid) as file_count 
				FROM dkrgroups g 
				LEFT JOIN dkrjointable j ON g.id_group = j.groupid 
				GROUP BY g.id_group 
				ORDER BY g.groupname";
		
		$result = $this->db->query($sql);
		return $result->rows;
	}
	
	/**
	 * Получить общее количество групп
	 */
	public function getTotalGroups($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM dkrgroups";
		if (!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach ($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if ($count > 0) $sql .= " AND";
			}
		}
		$result = $this->db->query($sql);
		return $result->row['count'];
	}
	
	/**
	 * Проверить существование группы
	 */
	public function groupExists($group_id) {
		$sql = "SELECT COUNT(*) AS count FROM dkrgroups WHERE id_group = '" . (int)$group_id . "'";
		$result = $this->db->query($sql);
		return $result->row['count'] > 0;
	}
	
	/**
	 * Получить группы по курсу
	 */
	public function getGroupsByCourse($course) {
		$course = (int)$course;
		$sql = "SELECT * FROM dkrgroups WHERE groupname LIKE '%$course%' ORDER BY groupname";
		$result = $this->db->query($sql);
		return $result->rows;
	}
	
	/**
	 * Получить все курсы
	 */
	public function getAllCourses() {
		$sql = "SELECT DISTINCT 
					CASE 
						WHEN groupname LIKE '%101%' THEN 1
						WHEN groupname LIKE '%201%' THEN 2  
						WHEN groupname LIKE '%301%' THEN 3
						ELSE 0
					END as course
				FROM dkrgroups 
				ORDER BY course";
		
		$result = $this->db->query($sql);
		return $result->rows;
	}
	
	/**
	 * Получить группы с пагинацией
	 */
	public function getGroups($data = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM dkrgroups";
		
		if (!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach ($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if ($count > 0) $sql .= " AND";
			}
		}
		
		// Сортировка
		if (!empty($sort)) {
			$count = count($sort);
			$sql .= " ORDER BY";
			foreach ($sort as $key => $value) {
				$sql .= " $key $value";
				
				$count--;
				if ($count > 0) $sql .= ",";
			}
		} else {
			$sql .= " ORDER BY groupname";
		}
		
		// Лимиты
		if (!empty($options)) {
			if ($options['start'] < 0) {
				$options['start'] = 0;
			}
			if ($options['limit'] < 1) {
				$options['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$options['start'] . "," . (int)$options['limit'];
		}
		
		$result = $this->db->query($sql);
		return $result->rows;
	}
}
?> 