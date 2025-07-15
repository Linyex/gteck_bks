<?php
class usersModel extends Model {
	public function createUser($data) {
		$sql = "INSERT INTO `users` SET ";
		$sql .= "user_login = '" . $this->db->escape($data['user_email']) . "', ";
		$sql .= "user_password = '" . $this->db->escape($data['user_password']) . "', ";
		$sql .= "user_fio = '" . $this->db->escape($data['user_firstname']) . "', ";
		$sql .= "user_status = '" . (int)$data['user_status'] . "', ";
		$sql .= "user_access_level = '" . (int)$data['user_access_level'] . "', ";
		$sql .= "user_date_reg = NOW()";
		$this->db->query($sql);
		return $this->db->getLastId();
	}

	public function deleteUser($userid) {
		$sql = "DELETE FROM `users` WHERE user_id = '" . (int)$userid . "'";
		$this->db->query($sql);
	}

	public function updateUser($userid, $data = array()) {
		$sql = "UPDATE `users`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";

				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `user_id` = '" . (int)$userid . "'";
		$query = $this->db->query($sql);
		return true;
	}

	public function getUsers($data = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `users`";
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
			if($options['start'] < 0) {
				$options['start'] = 0;
			}
			if($options['limit'] < 1) {
				$options['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$options['start'] . "," . (int)$options['limit'];
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getUserById($userid) {
		$sql = "SELECT * FROM `users` WHERE `user_id` = '" . (int)$userid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function getUserByLogin($userlogin) {
		$sql = "SELECT * FROM `users` WHERE `user_login` = '" . $this->db->escape($useremail) . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function getTotalUsers($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `users`";
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

	public function createAuthLog($userid, $ip, $status, $password) {
	}

	public function getAuthLog($userid) {
		$sql = "SELECT * FROM `authlog` WHERE `user` = '".(int)$userid."' ORDER BY(`id`) DESC LIMIT 20";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getIdByLogin($login) {
		$sql = "SELECT `user_id` FROM `users` WHERE `user_login` = '" . $this->db->escape($email) . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
}
?>
