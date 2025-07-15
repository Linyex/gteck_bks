<?php

/**
 * Миграционный скрипт для переноса старой MVC архитектуры в новую
 */

define('ENGINE_DIR', dirname(__FILE__) . '/engine/');
define('APPLICATION_DIR', dirname(__FILE__) . '/application/');

echo "Начинаем миграцию...\n";

// Список контроллеров для миграции
$controllers = [
    'news' => 'application/controllers/news/',
    'admin' => 'application/controllers/admin/',
    'stud' => 'application/controllers/stud/',
    'prepod' => 'application/controllers/prepod/',
    'kol' => 'application/controllers/kol/',
    'abut' => 'application/controllers/abut/',
    'okno' => 'application/controllers/okno/',
    'dopage' => 'application/controllers/dopage/',
    'search' => 'application/controllers/search/',
    'message' => 'application/controllers/message/',
    'files' => 'application/controllers/files/'
];

foreach ($controllers as $controllerName => $oldPath) {
    if (is_dir($oldPath)) {
        echo "Мигрируем контроллер: {$controllerName}\n";
        
        // Создаем новый контроллер
        $newControllerContent = generateNewController($controllerName, $oldPath);
        $newControllerFile = APPLICATION_DIR . 'controllers/' . $controllerName . '.php';
        
        file_put_contents($newControllerFile, $newControllerContent);
        echo "Создан новый контроллер: {$newControllerFile}\n";
    }
}

echo "Миграция завершена!\n";

function generateNewController($controllerName, $oldPath) {
    $className = ucfirst($controllerName) . 'Controller';
    
    $content = "<?php\n\n";
    $content .= "class {$className} extends BaseController {\n";
    $content .= "    \n";
    $content .= "    public function index() {\n";
    $content .= "        // TODO: Реализовать логику для {$controllerName}\n";
    $content .= "        return \$this->render('{$controllerName}/index', [\n";
    $content .= "            'title' => '{$controllerName}'\n";
    $content .= "        ]);\n";
    $content .= "    }\n";
    $content .= "    \n";
    $content .= "    // TODO: Добавить остальные методы из старого контроллера\n";
    $content .= "}\n";
    
    return $content;
}

echo "\nСледующие шаги:\n";
echo "1. Проверьте работу сайта\n";
echo "2. Постепенно перенесите логику из старых контроллеров в новые\n";
echo "3. Обновите view файлы для работы с новой архитектурой\n";
echo "4. Удалите старые файлы после полного тестирования\n"; 