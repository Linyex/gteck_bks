<?php

/**
 * Валидатор данных
 * Обеспечивает проверку и валидацию пользовательского ввода
 */
class Validator {
    
    private $errors = [];
    private $data = [];
    private $rules = [];
    
    public function __construct($data = []) {
        $this->data = $data;
    }
    
    /**
     * Устанавливает правила валидации
     */
    public function setRules($rules) {
        $this->rules = $rules;
        return $this;
    }
    
    /**
     * Добавляет правило для поля
     */
    public function addRule($field, $rule, $message = null) {
        if (!isset($this->rules[$field])) {
            $this->rules[$field] = [];
        }
        
        $this->rules[$field][] = [
            'rule' => $rule,
            'message' => $message
        ];
        
        return $this;
    }
    
    /**
     * Выполняет валидацию
     */
    public function validate() {
        $this->errors = [];
        
        foreach ($this->rules as $field => $fieldRules) {
            $value = $this->data[$field] ?? null;
            
            // Преобразуем правила в массив если это строка
            if (is_string($fieldRules)) {
                $fieldRules = [$fieldRules];
            }
            
            foreach ($fieldRules as $ruleData) {
                $rule = is_array($ruleData) ? $ruleData['rule'] : $ruleData;
                $message = is_array($ruleData) ? $ruleData['message'] : null;
                
                if (!$this->validateField($field, $value, $rule)) {
                    $this->errors[$field][] = $message ?: $this->getDefaultMessage($field, $rule);
                }
            }
        }
        
        return empty($this->errors);
    }
    
    /**
     * Валидирует одно поле
     */
    private function validateField($field, $value, $rule) {
        if (is_string($rule)) {
            return $this->validateSingleRule($field, $value, $rule);
        } elseif (is_array($rule)) {
            foreach ($rule as $singleRule) {
                if (!$this->validateSingleRule($field, $value, $singleRule)) {
                    return false;
                }
            }
            return true;
        }
        
        return true;
    }
    
    /**
     * Валидирует одно правило
     */
    private function validateSingleRule($field, $value, $rule) {
        $params = [];
        
        if (strpos($rule, ':') !== false) {
            list($rule, $paramString) = explode(':', $rule, 2);
            $params = explode(',', $paramString);
        }
        
        switch ($rule) {
            case 'required':
                return !empty($value) || $value === '0';
                
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
                
            case 'url':
                return filter_var($value, FILTER_VALIDATE_URL) !== false;
                
            case 'min':
                $min = (int)$params[0];
                return mb_strlen($value) >= $min;
                
            case 'max':
                $max = (int)$params[0];
                return mb_strlen($value) <= $max;
                
            case 'between':
                $min = (int)$params[0];
                $max = (int)$params[1];
                $length = mb_strlen($value);
                return $length >= $min && $length <= $max;
                
            case 'numeric':
                return is_numeric($value);
                
            case 'integer':
                return filter_var($value, FILTER_VALIDATE_INT) !== false;
                
            case 'float':
                return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
                
            case 'alpha':
                return preg_match('/^[a-zA-Z]+$/', $value);
                
            case 'alphanumeric':
                return preg_match('/^[a-zA-Z0-9]+$/', $value);
                
            case 'alpha_dash':
                return preg_match('/^[a-zA-Z0-9_-]+$/', $value);
                
            case 'regex':
                $pattern = $params[0];
                return preg_match($pattern, $value);
                
            case 'unique':
                return $this->validateUnique($field, $value, $params);
                
            case 'exists':
                return $this->validateExists($field, $value, $params);
                
            case 'confirmed':
                $confirmField = $params[0] ?? $field . '_confirmation';
                return $value === ($this->data[$confirmField] ?? null);
                
            case 'different':
                $otherField = $params[0];
                return $value !== ($this->data[$otherField] ?? null);
                
            case 'same':
                $otherField = $params[0];
                return $value === ($this->data[$otherField] ?? null);
                
            case 'date':
                return strtotime($value) !== false;
                
            case 'date_format':
                $format = $params[0];
                $date = DateTime::createFromFormat($format, $value);
                return $date !== false;
                
            case 'before':
                $date = $params[0];
                return strtotime($value) < strtotime($date);
                
            case 'after':
                $date = $params[0];
                return strtotime($value) > strtotime($date);
                
            case 'file':
                return isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK;
                
            case 'image':
                if (!$this->validateSingleRule($field, $value, 'file')) {
                    return false;
                }
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                return in_array($_FILES[$field]['type'], $allowedTypes);
                
            case 'mimes':
                $allowedMimes = $params;
                if (!$this->validateSingleRule($field, $value, 'file')) {
                    return false;
                }
                return in_array($_FILES[$field]['type'], $allowedMimes);
                
            case 'size':
                $maxSize = (int)$params[0];
                if (!$this->validateSingleRule($field, $value, 'file')) {
                    return false;
                }
                return $_FILES[$field]['size'] <= $maxSize;
                
            case 'dimensions':
                if (!$this->validateSingleRule($field, $value, 'image')) {
                    return false;
                }
                return $this->validateImageDimensions($field, $params);
                
            default:
                return true;
        }
    }
    
    /**
     * Валидирует уникальность
     */
    private function validateUnique($field, $value, $params) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $table = $params[0];
            $column = $params[1] ?? $field;
            $ignoreId = $params[2] ?? null;
            
            $query = "SELECT COUNT(*) as count FROM $table WHERE $column = ?";
            $queryParams = [$value];
            
            if ($ignoreId) {
                $query .= " AND id != ?";
                $queryParams[] = $ignoreId;
            }
            
            $result = Database::fetchOne($query, $queryParams);
            
            return ($result['count'] ?? 0) === 0;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Валидирует существование
     */
    private function validateExists($field, $value, $params) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $table = $params[0];
            $column = $params[1] ?? $field;
            
            $result = Database::fetchOne(
                "SELECT COUNT(*) as count FROM $table WHERE $column = ?",
                [$value]
            );
            
            return ($result['count'] ?? 0) > 0;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Валидирует размеры изображения
     */
    private function validateImageDimensions($field, $params) {
        $imageInfo = getimagesize($_FILES[$field]['tmp_name']);
        if (!$imageInfo) {
            return false;
        }
        
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        
        foreach ($params as $param) {
            if (strpos($param, 'min_width=') === 0) {
                $minWidth = (int)substr($param, 10);
                if ($width < $minWidth) return false;
            } elseif (strpos($param, 'max_width=') === 0) {
                $maxWidth = (int)substr($param, 10);
                if ($width > $maxWidth) return false;
            } elseif (strpos($param, 'min_height=') === 0) {
                $minHeight = (int)substr($param, 11);
                if ($height < $minHeight) return false;
            } elseif (strpos($param, 'max_height=') === 0) {
                $maxHeight = (int)substr($param, 11);
                if ($height > $maxHeight) return false;
            }
        }
        
        return true;
    }
    
    /**
     * Получает сообщение об ошибке по умолчанию
     */
    private function getDefaultMessage($field, $rule) {
        $messages = [
            'required' => "Поле '$field' обязательно для заполнения",
            'email' => "Поле '$field' должно содержать корректный email адрес",
            'url' => "Поле '$field' должно содержать корректный URL",
            'min' => "Поле '$field' должно содержать минимум :min символов",
            'max' => "Поле '$field' должно содержать максимум :max символов",
            'between' => "Поле '$field' должно содержать от :min до :max символов",
            'numeric' => "Поле '$field' должно быть числом",
            'integer' => "Поле '$field' должно быть целым числом",
            'float' => "Поле '$field' должно быть числом с плавающей точкой",
            'alpha' => "Поле '$field' должно содержать только буквы",
            'alphanumeric' => "Поле '$field' должно содержать только буквы и цифры",
            'alpha_dash' => "Поле '$field' должно содержать только буквы, цифры, дефисы и подчеркивания",
            'regex' => "Поле '$field' имеет неверный формат",
            'unique' => "Значение поля '$field' уже существует",
            'exists' => "Выбранное значение для поля '$field' не существует",
            'confirmed' => "Поле '$field' не совпадает с подтверждением",
            'different' => "Поле '$field' должно отличаться от :other",
            'same' => "Поле '$field' должно совпадать с :other",
            'date' => "Поле '$field' должно быть корректной датой",
            'date_format' => "Поле '$field' должно соответствовать формату :format",
            'before' => "Поле '$field' должно быть раньше :date",
            'after' => "Поле '$field' должно быть позже :date",
            'file' => "Поле '$field' должно быть файлом",
            'image' => "Поле '$field' должно быть изображением",
            'mimes' => "Поле '$field' должно быть файлом одного из типов: :mimes",
            'size' => "Размер файла '$field' не должен превышать :size байт",
            'dimensions' => "Размеры изображения '$field' не соответствуют требованиям"
        ];
        
        return $messages[$rule] ?? "Поле '$field' не прошло валидацию";
    }
    
    /**
     * Получает ошибки валидации
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Получает первую ошибку для поля
     */
    public function getFirstError($field) {
        return $this->errors[$field][0] ?? null;
    }
    
    /**
     * Проверяет есть ли ошибки
     */
    public function hasErrors() {
        return !empty($this->errors);
    }
    
    /**
     * Проверяет есть ли ошибки для конкретного поля
     */
    public function hasError($field) {
        return isset($this->errors[$field]);
    }
    
    /**
     * Получает валидированные данные
     */
    public function getValidatedData() {
        $validated = [];
        
        foreach ($this->rules as $field => $fieldRules) {
            if (isset($this->data[$field])) {
                $validated[$field] = $this->sanitizeValue($this->data[$field]);
            }
        }
        
        return $validated;
    }
    
    /**
     * Очищает значение
     */
    private function sanitizeValue($value) {
        if (is_string($value)) {
            $value = trim($value);
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        
        return $value;
    }
    
    /**
     * Создает экземпляр валидатора
     */
    public static function make($data, $rules) {
        $validator = new self($data);
        return $validator->setRules($rules);
    }
    
    /**
     * Быстрая валидация
     */
    public static function validateData($data, $rules) {
        $validator = self::make($data, $rules);
        return $validator->validate();
    }
    
    /**
     * Получает ошибки после быстрой валидации
     */
    public static function getValidationErrors($data, $rules) {
        $validator = self::make($data, $rules);
        $validator->validate();
        return $validator->getErrors();
    }
} 