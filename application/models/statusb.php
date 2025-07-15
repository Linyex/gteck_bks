<?php
class statusbModel extends Model {
	
	public function updateStatusb($newsid, $data = array()) {
		$sql = "UPDATE `statusb`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `statusb_id` = '" . (int)$newsid . "'";
		$query = $this->db->query($sql);
		return true;
	}
	
	public function getStatusb($sort = array(), $options = array()) {
		$sql = "SELECT * FROM `statusb`";

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
    
	public function getStatusbById($statusbid) {
		$sql = "SELECT `statusb_code` FROM `statusb`";
        $sql .= " WHERE `statusb_id` = '" . (int)$statusbid . "' LIMIT 1";
        
		$query = $this->db->query($sql);
		return $query->row;
	}
}
?>
