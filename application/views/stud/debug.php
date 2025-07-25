<?php 
    $page_title = "Отладка загрузки файлов"; 
    include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/header.php'; 
?>

<section class="debug-hero">
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="hero-icon">🔧</span>
            Отладка загрузки файлов
        </h1>
        <p class="hero-subtitle">Техническая информация о системе</p>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="info-card">
                    <h4 class="card-title">Настройки PHP</h4>
                    <div class="debug-table">
                        <div class="debug-row">
                            <span class="debug-label">upload_max_filesize:</span>
                            <span class="debug-value"><?php echo $debug_info['php_settings']['upload_max_filesize']; ?></span>
                        </div>
                        <div class="debug-row">
                            <span class="debug-label">post_max_size:</span>
                            <span class="debug-value"><?php echo $debug_info['php_settings']['post_max_size']; ?></span>
                        </div>
                        <div class="debug-row">
                            <span class="debug-label">max_execution_time:</span>
                            <span class="debug-value"><?php echo $debug_info['php_settings']['max_execution_time']; ?></span>
                        </div>
                        <div class="debug-row">
                            <span class="debug-label">memory_limit:</span>
                            <span class="debug-value"><?php echo $debug_info['php_settings']['memory_limit']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="info-card">
                    <h4 class="card-title">Папка загрузки</h4>
                    <div class="debug-table">
                        <div class="debug-row">
                            <span class="debug-label">Путь:</span>
                            <span class="debug-value"><?php echo htmlspecialchars($debug_info['upload_dir']['path']); ?></span>
                        </div>
                        <div class="debug-row">
                            <span class="debug-label">Существует:</span>
                            <span class="debug-value <?php echo $debug_info['upload_dir']['exists'] ? 'text-success' : 'text-danger'; ?>">
                                <?php echo $debug_info['upload_dir']['exists'] ? 'ДА' : 'НЕТ'; ?>
                            </span>
                        </div>
                        <div class="debug-row">
                            <span class="debug-label">Доступна для записи:</span>
                            <span class="debug-value <?php echo $debug_info['upload_dir']['writable'] ? 'text-success' : 'text-danger'; ?>">
                                <?php echo $debug_info['upload_dir']['writable'] ? 'ДА' : 'НЕТ'; ?>
                            </span>
                        </div>
                        <div class="debug-row">
                            <span class="debug-label">Права:</span>
                            <span class="debug-value"><?php echo $debug_info['upload_dir']['permissions']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-card mb-4">
            <h4 class="card-title">Информация о сервере</h4>
            <div class="debug-table">
                <div class="debug-row">
                    <span class="debug-label">DOCUMENT_ROOT:</span>
                    <span class="debug-value"><?php echo htmlspecialchars($debug_info['server']['document_root']); ?></span>
                </div>
                <div class="debug-row">
                    <span class="debug-label">SCRIPT_NAME:</span>
                    <span class="debug-value"><?php echo htmlspecialchars($debug_info['server']['script_name']); ?></span>
                </div>
            </div>
        </div>

        <?php if (isset($debug_info['upload_test'])): ?>
            <div class="info-card">
                <h4 class="card-title">Результат теста загрузки</h4>
                <div class="debug-table">
                    <div class="debug-row">
                        <span class="debug-label">Имя файла:</span>
                        <span class="debug-value"><?php echo htmlspecialchars($debug_info['upload_test']['file_info']['name']); ?></span>
                    </div>
                    <div class="debug-row">
                        <span class="debug-label">Размер:</span>
                        <span class="debug-value"><?php echo number_format($debug_info['upload_test']['file_info']['size']); ?> байт</span>
                    </div>
                    <div class="debug-row">
                        <span class="debug-label">Тип:</span>
                        <span class="debug-value"><?php echo htmlspecialchars($debug_info['upload_test']['file_info']['type']); ?></span>
                    </div>
                    <div class="debug-row">
                        <span class="debug-label">Код ошибки:</span>
                        <span class="debug-value"><?php echo $debug_info['upload_test']['error_code']; ?></span>
                    </div>
                    <?php if (isset($debug_info['upload_test']['target_path'])): ?>
                        <div class="debug-row">
                            <span class="debug-label">Путь сохранения:</span>
                            <span class="debug-value"><?php echo htmlspecialchars($debug_info['upload_test']['target_path']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (isset($debug_info['upload_test']['error_message'])): ?>
                    <div class="alert alert-danger mt-3">
                        <h5 class="alert-heading">Ошибка загрузки:</h5>
                        <p class="mb-0"><?php echo htmlspecialchars($debug_info['upload_test']['error_message']); ?></p>
                    </div>
                <?php elseif (isset($debug_info['upload_test']['status']) && $debug_info['upload_test']['status'] === 'success'): ?>
                    <div class="alert alert-success mt-3">
                        <h5 class="alert-heading">Успех!</h5>
                        <p class="mb-0">Файл загружен во временную папку успешно!</p>
                        <?php if (isset($debug_info['upload_test']['target_path'])): ?>
                            <hr>
                            <p class="mb-0"><strong>Целевой путь:</strong> <?php echo htmlspecialchars($debug_info['upload_test']['target_path']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($debug_info['upload_test']['move_result'])): ?>
                    <div class="alert <?php echo $debug_info['upload_test']['move_result']['success'] ? 'alert-success' : 'alert-danger'; ?> mt-3">
                        <h5 class="alert-heading">Перемещение файла:</h5>
                        <p class="mb-0"><?php echo htmlspecialchars($debug_info['upload_test']['move_result']['message']); ?></p>
                        <?php if (isset($debug_info['upload_test']['move_result']['final_path'])): ?>
                            <hr>
                            <p class="mb-0"><strong>Финальный путь:</strong> <?php echo htmlspecialchars($debug_info['upload_test']['move_result']['final_path']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/footer.php'; ?> 