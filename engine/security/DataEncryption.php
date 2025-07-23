<?php
/**
 * Класс для шифрования и дешифрования чувствительных данных
 * Использует AES-256-GCM для максимальной безопасности
 */

class DataEncryption {
    private static $instance = null;
    private $encryptionKey;
    private $cipher = 'aes-256-gcm';
    
    private function __construct() {
        // Генерируем или загружаем ключ шифрования
        $this->loadOrGenerateKey();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Шифрует данные
     */
    public function encrypt($data) {
        if (empty($data)) {
            return null;
        }
        
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $tag = '';
        
        $encrypted = openssl_encrypt(
            $data,
            $this->cipher,
            $this->encryptionKey,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
        
        if ($encrypted === false) {
            throw new Exception('Encryption failed');
        }
        
        // Возвращаем base64 строку содержащую IV, tag и зашифрованные данные
        return base64_encode($iv . $tag . $encrypted);
    }
    
    /**
     * Расшифровывает данные
     */
    public function decrypt($encryptedData) {
        if (empty($encryptedData)) {
            return null;
        }
        
        $data = base64_decode($encryptedData);
        if ($data === false) {
            return null;
        }
        
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = substr($data, 0, $ivLength);
        $tag = substr($data, $ivLength, 16);
        $encrypted = substr($data, $ivLength + 16);
        
        $decrypted = openssl_decrypt(
            $encrypted,
            $this->cipher,
            $this->encryptionKey,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
        
        if ($decrypted === false) {
            return null;
        }
        
        return $decrypted;
    }
    
    /**
     * Хеширует пароли с использованием bcrypt
     */
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    
    /**
     * Проверяет пароль
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Анонимизирует IP адрес
     */
    public function anonymizeIP($ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            // Для IPv4 обнуляем последний октет
            $parts = explode('.', $ip);
            $parts[3] = '0';
            return implode('.', $parts);
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // Для IPv6 обнуляем последние 64 бита
            $parts = explode(':', $ip);
            for ($i = 4; $i < 8; $i++) {
                $parts[$i] = '0';
            }
            return implode(':', $parts);
        }
        return $ip;
    }
    
    /**
     * Шифрует IP адрес для хранения
     */
    public function encryptIP($ip) {
        return $this->encrypt($ip);
    }
    
    /**
     * Расшифровывает IP адрес
     */
    public function decryptIP($encryptedIP) {
        return $this->decrypt($encryptedIP);
    }
    
    /**
     * Загружает или генерирует ключ шифрования
     */
    private function loadOrGenerateKey() {
        $keyFile = __DIR__ . '/../../config/encryption.key';
        
        if (file_exists($keyFile)) {
            $this->encryptionKey = file_get_contents($keyFile);
        } else {
            // Генерируем новый ключ
            $this->encryptionKey = openssl_random_pseudo_bytes(32);
            
            // Создаем директорию если её нет
            $dir = dirname($keyFile);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            // Сохраняем ключ
            file_put_contents($keyFile, $this->encryptionKey);
            chmod($keyFile, 0600); // Только владелец может читать
            
            // Добавляем в .gitignore
            $gitignore = __DIR__ . '/../../.gitignore';
            $ignoreContent = file_exists($gitignore) ? file_get_contents($gitignore) : '';
            if (strpos($ignoreContent, 'config/encryption.key') === false) {
                file_put_contents($gitignore, "\n# Encryption key\nconfig/encryption.key\n", FILE_APPEND);
            }
        }
    }
    
    /**
     * Маскирует email адрес
     */
    public function maskEmail($email) {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }
        
        $name = $parts[0];
        $domain = $parts[1];
        
        $nameLen = strlen($name);
        if ($nameLen <= 3) {
            $maskedName = str_repeat('*', $nameLen);
        } else {
            $maskedName = substr($name, 0, 2) . str_repeat('*', $nameLen - 3) . substr($name, -1);
        }
        
        return $maskedName . '@' . $domain;
    }
    
    /**
     * Генерирует безопасный токен
     */
    public function generateSecureToken($length = 32) {
        return bin2hex(openssl_random_pseudo_bytes($length));
    }
} 