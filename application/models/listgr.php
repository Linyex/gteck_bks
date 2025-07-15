<?php
class listgrModel extends Model {
	public function createListgr($data) {
		$sql = "INSERT INTO `listgr` SET ";
        $sql .= "listgr_file = '" . $data['listgr_file'] . "', ";
        $sql .= "listgr_text = '" . $data['listgr_text'] . "', ";
        $sql .= "listgr_status = '" . (int)$data['listgr_status'] . "', ";
		$sql .= "listgr_date_add = NOW()";
        
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	public function deleteListgr($listgrid) {
		$sql = "DELETE FROM `listgr` WHERE listgr_id = '" . (int)$listgrid . "'";
		$this->db->query($sql);
	}
	
	public function updateListgr($listgrid, $data = array()) {
		$sql = "UPDATE `listgr`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `listgr_id` = '" . (int)$listgrid . "'";
		$query = $this->db->query($sql);
		return true;
	}
    
	public function getListgr($sort = array(), $options = array()) {
		$sql = "SELECT * FROM `listgr`";
		
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
	
	public function getLastListgr() {
		$sql = "SELECT * FROM `listgr` ORDER BY listgr.listgr_id DESC LIMIT 1";
        
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
    
	public function getTotalListgr($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `listgr`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
		$query = $this->db->query($sql);
		return $query->row['count'];
	}
    
	public function getListgrById($listgrid) {
		$sql = "SELECT * FROM `listgr`";
        $sql .= " WHERE `listgr_id` = '" . (int)$listgrid . "' LIMIT 1";
        
		$query = $this->db->query($sql);
		return $query->row;
        
	}
}
?>
