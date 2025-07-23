<div class="admin-error" style="max-width:600px;margin:40px auto;padding:32px 24px;background:#fff;border-radius:8px;box-shadow:0 2px 16px rgba(0,0,0,0.08);text-align:center;">
    <h1 style="color:#d32f2f;font-size:2.5em;margin-bottom:0.5em;">
        <?= isset(
            $title) ? htmlspecialchars($title) : 'Ошибка' ?>
    </h1>
    <p style="font-size:1.2em; color:#444; margin-bottom:1.5em;">
        <?= isset($message) ? htmlspecialchars($message) : 'Произошла неизвестная ошибка.' ?>
    </p>
    <?php if (!empty($errorDetails)): ?>
        <pre style="background:#f8f8f8;padding:12px;border-radius:4px;text-align:left;overflow-x:auto;max-height:300px;"> <?= htmlspecialchars($errorDetails) ?> </pre>
    <?php endif; ?>
    <a href="/admin/dashboard" style="display:inline-block;margin-top:2em;padding:10px 24px;background:#1976d2;color:#fff;border-radius:4px;text-decoration:none;font-weight:bold;">Вернуться в админ-панель</a>
</div> 