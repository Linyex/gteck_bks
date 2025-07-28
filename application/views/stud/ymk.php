<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/main/db.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/libs/GroupPasswordChecker.php';

// Инициализация переменных
$selectedGroup = null;
$group_files = [];
$passwordRequired = true;
$groups = [];
$passwordError = null;

// Получаем все группы из dkrgroups
try {
    $groups = Database::fetchAll("SELECT * FROM dkrgroups ORDER BY groupname");
} catch (Exception $e) {
    $groups = [];
}

// Если форма отправлена с паролем
if ($_POST && isset($_POST['group_name']) && isset($_POST['password'])) {
    $group_name = trim($_POST['group_name']);
    $password = trim($_POST['password']);
    
    // Проверяем пароль (тот же что и для контрольных работ)
    if (GroupPasswordChecker::checkPassword($group_name, $password)) {
        $selectedGroup = $group_name;
        $passwordRequired = false;
        
        // Получаем УМК файлы для выбранной группы
        // TODO: Создать таблицу umk_files аналогично dkrfiles
        // Пока используем заглушку
        try {
            // Временно - пустой массив, пока не создана таблица УМК
            $group_files[$group_name] = [];
            
            /* Будущий запрос когда создадим таблицу umk_files:
            $group_files[$group_name] = Database::fetchAll("
                SELECT f.* 
                FROM umk_files f 
                JOIN umk_jointable j ON f.id = j.fileid 
                JOIN dkrgroups g ON j.groupid = g.id_group 
                WHERE g.groupname = ? 
                ORDER BY f.upload_date DESC
            ", [$group_name]);
            */
        } catch (Exception $e) {
            $group_files[$group_name] = [];
        }
    } else {
        $passwordError = "Неверный пароль для группы $group_name";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>УМК - ГТЭК</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/gtec.css">
    <link rel="stylesheet" href="/assets/css/main-styles.css">
</head>
<body>

<?php if ($passwordRequired): ?>
    <!-- Форма ввода пароля -->
    <section class="password-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="password-form-container">
                        <h2 class="text-center mb-4">📖 Доступ к УМК</h2>
                        <p class="text-center text-muted mb-4">Введите пароль вашей группы для доступа к учебно-методическому комплексу</p>
                        
                        <?php if ($passwordError): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($passwordError); ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="form-group mb-3">
                                <label for="group_name">Выберите группу:</label>
                                <select name="group_name" id="group_name" class="form-control" required>
                                    <option value="">-- Выберите группу --</option>
                                    <?php foreach ($groups as $group): ?>
                                        <option value="<?php echo htmlspecialchars($group['groupname']); ?>"
                                                <?php echo (isset($_POST['group_name']) && $_POST['group_name'] === $group['groupname']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($group['groupname']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="password">Пароль группы:</label>
                                <input type="password" name="password" id="password" class="form-control" 
                                       placeholder="Введите пароль для доступа к УМК" required>
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-block">📚 Войти в УМК</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">Пароль такой же как для контрольных работ</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php else: ?>
    <!-- Показываем УМК для авторизованной группы -->
    <section class="umk-hero">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="hero-content text-center">
                        <h1 class="hero-title">📚 Учебно-методический комплекс</h1>
                        <h2 class="hero-subtitle">Группа: <?php echo htmlspecialchars($selectedGroup); ?></h2>
                        <p class="hero-description">Учебные материалы, методички и дополнительная литература</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="umk-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    
                    <?php if (!empty($group_files[$selectedGroup])): ?>
                        <div class="files-grid">
                            <?php foreach ($group_files[$selectedGroup] as $file): ?>
                                <div class="file-card umk-card">
                                    <div class="file-icon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <div class="file-info">
                                        <h4 class="file-title"><?php echo htmlspecialchars($file['filename']); ?></h4>
                                        <p class="file-meta">
                                            📅 Загружено: <?php echo date('d.m.Y', strtotime($file['upload_date'])); ?>
                                        </p>
                                        <?php if (isset($file['description']) && $file['description']): ?>
                                            <p class="file-description"><?php echo htmlspecialchars($file['description']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="file-actions">
                                        <a href="<?php echo htmlspecialchars($file['path']); ?>" 
                                           class="btn btn-success" target="_blank">
                                            📖 Открыть
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state text-center">
                            <div class="empty-icon">📚</div>
                            <h3>УМК в разработке</h3>
                            <p>Для группы <?php echo htmlspecialchars($selectedGroup); ?> УМК находится в стадии подготовки</p>
                            <div class="alert alert-info mt-3">
                                <strong>💡 Информация:</strong> УМК будет доступен в ближайшее время. 
                                Следите за обновлениями!
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Кнопка выхода -->
                    <div class="text-center mt-4">
                        <a href="?logout=1" class="btn btn-secondary">🔓 Выйти</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<style>
.password-section {
    min-height: 70vh;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
}

.password-form-container {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.btn-block {
    width: 100%;
    padding: 12px;
    font-weight: 600;
}

.umk-hero {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    color: white;
    padding: 4rem 0;
    text-align: center;
}

.files-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.file-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
    transition: transform 0.3s ease;
}

.umk-card {
    border-left: 4px solid #56ab2f;
}

.file-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.empty-state {
    padding: 4rem 0;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}
</style>

</body>
</html> 