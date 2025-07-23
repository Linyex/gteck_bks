<?php
/**
 * Класс для современного шифрования данных
 * Использует AES-256-GCM с солью и PBKDF2 для ключей
 */

class Encryption {
    private static $algorithm = 'aes-256-gcm';
    private static $keyLength = 32; // 256 bits
    private static $saltLength = 32;
    private static $ivLength = 16;
    private static $tagLength = 16;
    private static $iterations = 100000; // PBKDF2 iterations
    
    /**
     * Генерирует криптографически стойкую соль
     */
    public static function generateSalt($length = null) {
        $length = $length ?: self::$saltLength;
        return bin2hex(random_bytes($length));
    }
    
    /**
     * Генерирует криптографически стойкий ключ
     */
    public static function generateKey($length = null) {
        $length = $length ?: self::$keyLength;
        return bin2hex(random_bytes($length));
    }
    
    /**
     * Создает ключ из пароля с использованием PBKDF2
     */
    public static function deriveKey($password, $salt) {
        return hash_pbkdf2('sha256', $password, $salt, self::$iterations, self::$keyLength, true);
    }
    
    /**
     * Шифрует данные
     */
    public static function encrypt($data, $key = null) {
        try {
            // Если ключ не передан, используем системный ключ
            if ($key === null) {
                $key = self::getSystemKey();
            }
            
            // Генерируем соль и IV
            $salt = self::generateSalt();
            $iv = random_bytes(self::$ivLength);
            
            // Создаем ключ из пароля и соли
            $derivedKey = self::deriveKey($key, $salt);
            
            // Шифруем данные
            $encrypted = openssl_encrypt(
                $data,
                self::$algorithm,
                $derivedKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag,
                '',
                self::$tagLength
            );
            
            if ($encrypted === false) {
                throw new Exception('Ошибка шифрования: ' . openssl_error_string());
            }
            
            // Объединяем соль, IV, тег и зашифрованные данные
            $result = $salt . bin2hex($iv) . bin2hex($tag) . bin2hex($encrypted);
            
            return base64_encode($result);
            
        } catch (Exception $e) {
            error_log('Encryption error: ' . $e->getMessage());
            throw new Exception('Ошибка шифрования данных');
        }
    }
    
    /**
     * Расшифровывает данные
     */
    public static function decrypt($encryptedData, $key = null) {
        try {
            // Если ключ не передан, используем системный ключ
            if ($key === null) {
                $key = self::getSystemKey();
            }
            
            // Декодируем из base64
            $data = base64_decode($encryptedData);
            
            // Извлекаем компоненты
            $salt = substr($data, 0, self::$saltLength);
            $iv = hex2bin(substr($data, self::$saltLength, self::$ivLength * 2));
            $tag = hex2bin(substr($data, self::$saltLength + self::$ivLength * 2, self::$tagLength * 2));
            $encrypted = hex2bin(substr($data, self::$saltLength + self::$ivLength * 2 + self::$tagLength * 2));
            
            // Создаем ключ из пароля и соли
            $derivedKey = self::deriveKey($key, $salt);
            
            // Расшифровываем данные
            $decrypted = openssl_decrypt(
                $encrypted,
                self::$algorithm,
                $derivedKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );
            
            if ($decrypted === false) {
                throw new Exception('Ошибка расшифровки: ' . openssl_error_string());
            }
            
            return $decrypted;
            
        } catch (Exception $e) {
            error_log('Decryption error: ' . $e->getMessage());
            throw new Exception('Ошибка расшифровки данных');
        }
    }
    
    /**
     * Получает системный ключ шифрования
     */
    private static function getSystemKey() {
        $keyFile = __DIR__ . '/../../config/encryption.key';
        
        // Если ключ не существует, создаем его
        if (!file_exists($keyFile)) {
            $key = self::generateKey();
            $dir = dirname($keyFile);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            file_put_contents($keyFile, $key);
            chmod($keyFile, 0600); // Только для владельца
        }
        
        return file_get_contents($keyFile);
    }
    
    /**
     * Хеширует пароль с солью
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,    // 64MB
            'time_cost' => 4,          // 4 iterations
            'threads' => 3             // 3 threads
        ]);
    }
    
    /**
     * Проверяет пароль
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Создает безопасный токен
     */
    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }
    
    /**
     * Хеширует данные для хранения в БД
     */
    public static function hashData($data, $algorithm = 'sha256') {
        return hash($algorithm, $data);
    }
    
    /**
     * Создает HMAC подпись
     */
    public static function createHmac($data, $key = null) {
        if ($key === null) {
            $key = self::getSystemKey();
        }
        return hash_hmac('sha256', $data, $key);
    }
    
    /**
     * Проверяет HMAC подпись
     */
    public static function verifyHmac($data, $signature, $key = null) {
        $expectedSignature = self::createHmac($data, $key);
        return hash_equals($expectedSignature, $signature);
    }
    
    /**
     * Шифрует чувствительные данные для БД
     */
    public static function encryptForDatabase($data) {
        if (empty($data)) {
            return null;
        }
        return self::encrypt($data);
    }
    
    /**
     * Расшифровывает данные из БД
     */
    public static function decryptFromDatabase($encryptedData) {
        if (empty($encryptedData)) {
            return null;
        }
        return self::decrypt($encryptedData);
    }
    
    /**
     * Создает безопасный идентификатор сессии
     */
    public static function generateSessionId() {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Проверяет силу пароля
     */
    public static function checkPasswordStrength($password) {
        $score = 0;
        $feedback = [];
        
        // Длина пароля
        if (strlen($password) >= 12) {
            $score += 2;
        } elseif (strlen($password) >= 8) {
            $score += 1;
        } else {
            $feedback[] = 'Пароль должен содержать минимум 8 символов';
        }
        
        // Наличие цифр
        if (preg_match('/\d/', $password)) {
            $score += 1;
        } else {
            $feedback[] = 'Добавьте цифры';
        }
        
        // Наличие букв в разных регистрах
        if (preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password)) {
            $score += 1;
        } else {
            $feedback[] = 'Используйте буквы в разных регистрах';
        }
        
        // Наличие специальных символов
        if (preg_match('/[^a-zA-Z0-9]/', $password)) {
            $score += 1;
        } else {
            $feedback[] = 'Добавьте специальные символы';
        }
        
        // Определяем уровень безопасности
        if ($score >= 4) {
            $strength = 'strong';
        } elseif ($score >= 2) {
            $strength = 'medium';
        } else {
            $strength = 'weak';
        }
        
        return [
            'score' => $score,
            'strength' => $strength,
            'feedback' => $feedback
        ];
    }
} 