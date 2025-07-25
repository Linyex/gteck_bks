<div style="display: table; width: 100%;">
    <h2>Ошибка</h2>
    <hr>
    <div style="display: table; width: 100%; text-align: center;">
        <h1 style="text-transform: uppercase;font-size: 24px;font-weight: 900;color: #e74c3c;"><?php echo isset($errorCode) ? "Ошибка " . $errorCode : "Ошибка 404"; ?></h1>
        
        <div style="margin: 40px 0;">
            <div style="font-size: 72px; color: #e74c3c; margin-bottom: 20px;">
                <?php echo isset($errorCode) ? $errorCode : "404"; ?>
            </div>
            <div style="font-size: 18px; color: #666; max-width: 500px; margin: 0 auto;">
                <?php echo isset($message) ? $message : "Возможно вы нажали на неверную ссылку или ввели неверный URL-адрес."; ?>
            </div>
        </div>
        
        <div class="news-about" style="text-align: center; margin-bottom: 20px;">
            <a href="/" style="float: none; font-size: 20px; color: #8B5CF6; text-decoration: none; padding: 12px 24px; border: 2px solid #8B5CF6; border-radius: 25px; transition: all 0.3s;">Вернуться на главную страницу</a>
        </div>
    </div>
</div>

<style>
.news-about a:hover {
    background: #8B5CF6;
    color: white;
}
</style>