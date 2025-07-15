<?php
class sabutModel extends Model {
	public function createSabut($data) {
		$sql = "INSERT INTO `sabut` SET ";
        $sql .= "sabut_file = '" . $data['sabut_file'] . "', ";
        $sql .= "sabut_text = '" . $data['sabut_text'] . "', ";
        $sql .= "sabut_status = '" . (int)$data['sabut_status'] . "', ";
		$sql .= "sabut_date_add = NOW()";
        
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	public function deleteSabut($sabutid) {
		$sql = "DELETE FROM `sabut` WHERE sabut_id = '" . (int)$sabutid . "'";
		$this->db->query($sql);
	}
	
	public function updateSabut($sabutid, $data = array()) {
		$sql = "UPDATE `sabut`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `sabut_id` = '" . (int)$sabutid . "'";
		$query = $this->db->query($sql);
		return true;
	}
    
	public function getSabut($sort = array(), $options = array()) {
		$sql = "SELECT * FROM `sabut`";
		
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
	
	public function getLastSabut() {
		$sql = "SELECT * FROM `sabut` ORDER BY sabut.sabut_id DESC LIMIT 1";
        
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
    
	public function getTotalSabut($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `sabut`";
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
    
	public function getSabutById($sabutid) {
		$sql = "SELECT * FROM `sabut`";
        $sql .= " WHERE `sabut_id` = '" . (int)$sabutid . "' LIMIT 1";
        
		$query = $this->db->query($sql);
		return $query->row;
        
	}
}
?>
