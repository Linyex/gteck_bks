<?php
class photosModel extends Model {
	public function createPhotos($data) {
		$sql = "INSERT INTO `photos` SET ";
        $sql .= "photos_title = '" . $data['photos_title'] . "', ";
        $sql .= "photos_images = '" . $data['photos_images'] . "', ";
		$sql .= "photos_date_add = NOW()";
        
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	public function deletePhotos($photosid) {
		$sql = "DELETE FROM `photos` WHERE photos_id = '" . (int)$photosid . "'";
		$this->db->query($sql);
	}
	
	public function updatePhotos($photosid, $data = array()) {
		$sql = "UPDATE `photos`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `photos_id` = '" . (int)$photosid . "'";
		$query = $this->db->query($sql);
		return true;
	}
    
	public function getPhotos($data = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `photos`";
		
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
	
	public function getLastPhotos() {
		$sql = "SELECT * FROM `photos` ORDER BY photos.photos_id DESC LIMIT 1";
        
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
    
	public function getTotalPhotos($data = array(), $data2 = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `photos`";
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
    
	public function getPhotosById($photosid) {
		$sql = "SELECT * FROM `photos`";
        $sql .= " WHERE `photos_id` = '" . (int)$photosid . "' LIMIT 1";
        
		$query = $this->db->query($sql);
		return $query->row;
        
	}
}
?>