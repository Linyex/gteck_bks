<?php
if (isset($_POST['submit'])) {
    $subject = $_POST['subject']; // Предмет
    $group = $_POST['group'];     // Группа
    $file = $_FILES['file'];      // Файл

    // Проверяем, что файл был загружен без ошибок
    if ($file['error'] === UPLOAD_ERR_OK) {

        // Проверка и создание директории, если она не существует
        $uploadDir = "uploads/$subject/$group/";
        if (!is_dir($uploadDir)) {
            // Рекурсивное создание директорий
            mkdir($uploadDir, 0777, true);
        }

        // Получение имени файла и его пути
        $fileName = basename($file['name']);
        $uploadFile = $uploadDir . $fileName;

        // Проверка на допустимые расширения файлов (безопасность)
        $allowedExtensions = ['pdf', 'docx', 'xlsx', 'pptx', 'txt'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "Недопустимый формат файла. Разрешены только: " . implode(', ', $allowedExtensions);
            exit;
        }

        // Перемещение загруженного файла в нужную директорию
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            echo "Файл успешно загружен.";
        } else {
            echo "Ошибка при перемещении файла.";
        }
    } else {
        echo "Ошибка при загрузке файла.";
    }
} else {
    echo "Форма не была отправлена.";
}

$allowedMimeTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword'];

// Проверяем MIME-тип файла
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedMimeTypes)) {
    echo "Недопустимый тип файла.";
    exit;
}