<?php

class TranslationModel {
	public function __construct() {
		$this->ensureTables();
	}

	private function ensureTables() {
		Database::execute("CREATE TABLE IF NOT EXISTS translations (
			id INT AUTO_INCREMENT PRIMARY KEY,
			src_lang VARCHAR(8) NOT NULL,
			dst_lang VARCHAR(8) NOT NULL,
			src_hash CHAR(40) NOT NULL,
			src_text MEDIUMTEXT NOT NULL,
			dst_text MEDIUMTEXT NOT NULL,
			updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			UNIQUE KEY uniq_pair_hash (src_lang, dst_lang, src_hash),
			INDEX idx_pair (src_lang, dst_lang)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
	}

	public function getMany(string $srcLang, string $dstLang, array $srcTexts): array {
		if (empty($srcTexts)) return [];
		$map = [];
		$hashes = [];
		foreach ($srcTexts as $t) {
			$h = sha1($t);
			$hashes[$h] = true;
		}
		$in = [];
		$params = ['s'=>$srcLang, 'd'=>$dstLang];
		$i = 0;
		foreach (array_keys($hashes) as $h) {
			$key = ':h'.$i++;
			$in[] = $key;
			$params[substr($key,1)] = $h;
		}
		$sql = "SELECT src_hash, dst_text FROM translations WHERE src_lang = :s AND dst_lang = :d AND src_hash IN (".implode(',', $in).")";
		$rows = Database::fetchAll($sql, $params);
		foreach ($rows as $r) { $map[$r['src_hash']] = $r['dst_text']; }
		return $map;
	}

	public function saveMany(string $srcLang, string $dstLang, array $pairs): void {
		if (empty($pairs)) return;
		foreach ($pairs as $src => $dst) {
			$cols = ['src_lang','dst_lang','src_hash','src_text','dst_text'];
			$vals = [
				$srcLang,
				$dstLang,
				sha1($src),
				$src,
				$dst
			];
			// REPLACE INTO to upsert cache row
			$placeholders = implode(',', array_fill(0, count($vals), '?'));
			$sql = "REPLACE INTO translations (`" . implode('`,`', $cols) . "`) VALUES ($placeholders)";
			Database::query($sql, $vals);
		}
	}
}


