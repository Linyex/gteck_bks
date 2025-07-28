<?php
session_start();
require_once __DIR__ . '/engine/main/db.php';
require_once __DIR__ . '/engine/libs/GroupPasswordChecker.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$password = trim($input['password'] ?? '');
$page = trim($input['page'] ?? '');

if (empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Пароль не может быть пустым']);
    exit;
}

// Сначала пробуем новую систему паролей
$groups = GroupPasswordChecker::getActiveGroups();
$accessGranted = false;
$groupInfo = null;

foreach ($groups as $group) {
    $result = GroupPasswordChecker::checkPassword($group['group_name'], $password);
    if ($result['success']) {
        $accessGranted = true;
        $groupInfo = $result['group_info'];
        break;
    }
}

// Если не подошел новый способ, пробуем старый (для обратной совместимости)
if (!$accessGranted) {
    $legacyResult = GroupPasswordChecker::checkPasswordLegacy($password);
    if ($legacyResult['success']) {
        $accessGranted = true;
        $groupInfo = [
            'name' => $legacyResult['group'],
            'description' => ''
        ];
    }
}

if ($accessGranted) {
    echo json_encode([
        'success' => true,
        'message' => 'Доступ предоставлен',
        'group' => $groupInfo,
        'redirect' => $page === 'ymk' ? '/stud/ymk' : '/stud/kontrolnui'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Неверный пароль. Обратитесь к куратору группы.'
    ]);
}
?> 