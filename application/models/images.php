<?php
class imagesModel extends Model {
	public function createImages($data) {
		$sql = "INSERT INTO `images` SET ";
        $sql .= "images_file = '" . $data['images_file'] . "', ";
        $sql .= "images_text = '" . $data['images_text'] . "', ";
        $sql .= "images_group = '" . (int)$data['images_group'] . "', ";
		$sql .= "images_date_add = NOW()";
        
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	public function deleteImages($imagesid) {
		$sql = "DELETE FROM `images` WHERE images_id = '" . (int)$imagesid . "'";
		$this->db->query($sql);
	}
	
	public function updateImages($imagesid, $data = array()) {
		$sql = "UPDATE `images`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `images_id` = '" . (int)$imagesid . "'";
		$query = $this->db->query($sql);
		return true;
	}
    
	public function getImages($data = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `images`";
		
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
	
	public function getLastImages() {
		$sql = "SELECT * FROM `images` ORDER BY images.images_id DESC LIMIT 1";
        
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
    
	public function getTotalImages($data = array(), $data2 = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `images`";
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
    
	public function getImagesById($imagesid) {
		$sql = "SELECT * FROM `images`";
        $sql .= " WHERE `images_id` = '" . (int)$imagesid . "' LIMIT 1";
        
		$query = $this->db->query($sql);
		return $query->row;
        
	}
}
?>
