<?php

class filesModel extends Model {
	public function createFiles($data) {
		$sql = "INSERT INTO `files` SET ";
        $sql .= "files_file = '" . $data['files_file'] . "', ";
        $sql .= "files_text = '" . $data['files_text'] . "', ";
        $sql .= "files_group = '" . (int)$data['files_group'] . "', ";
        $sql .= "files_ekzamen = '" . (int)$data['files_ekzamen'] . "', ";
		$sql .= "files_date_add = NOW()";
        
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	public function deleteFiles($filesid) {
		$sql = "DELETE FROM `files` WHERE files_id = '" . (int)$filesid . "'";
		$this->db->query($sql);
	}
	
	public function updateFiles($filesid, $data = array()) {
		$sql = "UPDATE `files`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `files_id` = '" . (int)$filesid . "'";
		$query = $this->db->query($sql);
		return true;
	}
	
	public function getFiles($data = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `files`";
		
		if(!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
		
		if(!empty($sort)) {
			$count = count($sort);
			$sql .= " ORDER BY";
			foreach($sort as $key => $value) {
				$sql .= " $key " . $value;
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		
		if(!empty($options)) {
			if ($options['start'] < 0) {
				$options['start'] = 0;
			}
			if ($options['limit'] < 1) {
				$options['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$options['start'] . "," . (int)$options['limit'];
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getLastFiles() {
		$sql = "SELECT * FROM `files` ORDER BY files.files_id DESC LIMIT 1";
        
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
    
    public function getTotalFiles($data = array(), $data2 = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `files`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
        if(!empty($data2)) {
			$count = count($data2);
			$sql .= " AND";
			foreach($data2 as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
		$query = $this->db->query($sql);
		return $query->row['count'];
	}
    
	public function getFilesById($filesid) {
		$sql = "SELECT * FROM `files`";
        $sql .= " WHERE `files_id` = '" . (int)$filesid . "' LIMIT 1";
        
		$query = $this->db->query($sql);
		return $query->row;
        
	}

}
?>
