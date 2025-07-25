<?php

/**
 * Google Authenticator библиотека
 * Реализация TOTP (Time-based One-Time Password)
 */
class GoogleAuthenticator {
    
    private $codeLength = 6;
    private $timeSlice = 30;
    private $secretLength = 16;
    
    /**
     * Генерирует секретный ключ для пользователя
     */
    public function generateSecret($length = null) {
        $length = $length ?: $this->secretLength;
        $validChars = $this->_getBase32LookupTable();
        $secret = '';
        
        if ($length < 16 || $length > 128) {
            throw new Exception('Bad secret length');
        }
        
        $rnd = random_bytes($length);
        for ($i = 0; $i < $length; ++$i) {
            $secret .= $validChars[ord($rnd[$i]) & 31];
        }
        
        return $secret;
    }
    
    /**
     * Генерирует QR код для Google Authenticator
     */
    public function generateQRCode($name, $secret, $title = null, $params = array()) {
        $width = !empty($params['width']) && (int) $params['width'] > 0 ? (int) $params['width'] : 200;
        $height = !empty($params['height']) && (int) $params['height'] > 0 ? (int) $params['height'] : 200;
        $level = !empty($params['level']) && in_array($params['level'], array('L', 'M', 'Q', 'H')) ? $params['level'] : 'M';
        
        $urlencoded = urlencode('otpauth://totp/' . $name . '?secret=' . $secret . '');
        if (isset($params['issuer'])) {
            $urlencoded .= urlencode('&issuer=' . urlencode($params['issuer']));
        }
        
        return 'https://chart.googleapis.com/chart?chs=' . $width . 'x' . $height . '&chld=' . $level . '|0&cht=qr&chl=' . $urlencoded . '';
    }
    
    /**
     * Проверяет код пользователя
     */
    public function verifyCode($secret, $code, $discrepancy = 1, $currentTimeSlice = null) {
        if ($currentTimeSlice === null) {
            $currentTimeSlice = floor(time() / $this->timeSlice);
        }
        
        if (strlen($code) != 6) {
            return false;
        }
        
        for ($i = -$discrepancy; $i <= $discrepancy; ++$i) {
            $calculatedCode = $this->getCode($secret, $currentTimeSlice + $i);
            if ($this->timingSafeEquals($calculatedCode, $code)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Генерирует код для текущего времени
     */
    public function getCode($secret, $timeSlice = null) {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / $this->timeSlice);
        }
        
        $secretkey = $this->_base32Decode($secret);
        
        $time = chr(0) . chr(0) . chr(0) . chr(0) . pack('N*', $timeSlice);
        $hm = hash_hmac('SHA1', $time, $secretkey, true);
        $offset = ord(substr($hm, -1)) & 0x0F;
        $hashpart = substr($hm, $offset, 4);
        
        $value = unpack('N', $hashpart);
        $value = $value[1];
        $value = $value & 0x7FFFFFFF;
        
        return str_pad($value % pow(10, $this->codeLength), $this->codeLength, '0', STR_PAD_LEFT);
    }
    
    /**
     * Генерирует резервные коды
     */
    public function generateBackupCodes($count = 8) {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = $this->generateBackupCode();
        }
        return $codes;
    }
    
    /**
     * Генерирует один резервный код
     */
    private function generateBackupCode() {
        $code = '';
        for ($i = 0; $i < 8; $i++) {
            $code .= mt_rand(0, 9);
        }
        return $code;
    }
    
    /**
     * Проверяет резервный код
     */
    public function verifyBackupCode($userId, $code) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            // Проверяем, не был ли код уже использован
            $used = Database::fetchOne(
                "SELECT id FROM used_backup_codes WHERE user_id = ? AND code_hash = ?",
                [$userId, hash('sha256', $code)]
            );
            
            if ($used) {
                return false;
            }
            
            // Получаем резервные коды пользователя
            $user2fa = Database::fetchOne(
                "SELECT backup_codes FROM user_2fa WHERE user_id = ? AND is_enabled = 1",
                [$userId]
            );
            
            if (!$user2fa) {
                return false;
            }
            
            $backupCodes = json_decode($user2fa['backup_codes'], true);
            
            if (in_array($code, $backupCodes)) {
                // Помечаем код как использованный
                Database::execute(
                    "INSERT INTO used_backup_codes (user_id, code_hash) VALUES (?, ?)",
                    [$userId, hash('sha256', $code)]
                );
                
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Безопасное сравнение строк
     */
    private function timingSafeEquals($safeString, $userString) {
        if (function_exists('hash_equals')) {
            return hash_equals($safeString, $userString);
        }
        
        $safeLen = strlen($safeString);
        $userLen = strlen($userString);
        
        if ($userLen != $safeLen) {
            return false;
        }
        
        $result = 0;
        for ($i = 0; $i < $userLen; $i++) {
            $result |= (ord($safeString[$i]) ^ ord($userString[$i]));
        }
        
        return $result === 0;
    }
    
    /**
     * Декодирование Base32
     */
    private function _base32Decode($secret) {
        if (empty($secret)) {
            return '';
        }
        
        $base32chars = $this->_getBase32LookupTable();
        $base32charsFlipped = array_flip($base32chars);
        
        $paddingCharCount = substr_count($secret, $base32chars[32]);
        $allowedValues = array(6, 4, 3, 1, 0);
        if (!in_array($paddingCharCount, $allowedValues)) {
            return false;
        }
        for ($i = 0; $i < 4; ++$i) {
            if ($paddingCharCount == $allowedValues[$i] &&
                substr($secret, -($allowedValues[$i])) != str_repeat($base32chars[32], $allowedValues[$i])) {
                return false;
            }
        }
        $secret = str_replace('=', '', $secret);
        $secret = str_split($secret);
        $binaryString = "";
        for ($i = 0; $i < count($secret); $i = $i + 8) {
            $x = "";
            if (!in_array($secret[$i], $base32chars)) {
                return false;
            }
            for ($j = 0; $j < 8; ++$j) {
                $x .= str_pad(base_convert(@$base32charsFlipped[@$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
            }
            $eightBits = str_split($x, 8);
            for ($z = 0; $z < count($eightBits); ++$z) {
                $binaryString .= (($y = chr(base_convert($eightBits[$z], 2, 10))) || ord($y) == 48) ? $y : "";
            }
        }
        return $binaryString;
    }
    
    /**
     * Таблица символов Base32
     */
    private function _getBase32LookupTable() {
        return array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
            'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
            'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
            'Y', 'Z', '2', '3', '4', '5', '6', '7',
            '='
        );
    }
} 