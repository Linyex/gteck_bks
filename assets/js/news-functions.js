/**
 * Функции для работы с новостями
 * Версия: 1.0.0
 * Автор: Олег
 */

// Функция для открытия модального окна с изображением
function openImageModal(imgElement) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const modalCaption = document.getElementById('modalCaption');
    
    if (!modal) {
        createImageModal();
    }
    
    const modal2 = document.getElementById('imageModal');
    const modalImg2 = document.getElementById('modalImage');
    const modalCaption2 = document.getElementById('modalCaption');
    
    modalImg2.src = imgElement.src;
    modalCaption2.textContent = imgElement.alt || 'Изображение';
    modal2.style.display = 'block';
    
    // Добавляем обработчик для закрытия по клику вне изображения
    modal2.addEventListener('click', function(e) {
        if (e.target === modal2) {
            closeImageModal();
        }
    });
    
    // Добавляем обработчик для клавиши Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
}

// Функция для создания модального окна
function createImageModal() {
    const modal = document.createElement('div');
    modal.id = 'imageModal';
    modal.className = 'image-modal';
    
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close-modal" onclick="closeImageModal()">&times;</span>
            <button class="modal-nav modal-prev" onclick="changeModalImage(-1)">
                <i class="fa fa-chevron-left"></i>
            </button>
            <button class="modal-nav modal-next" onclick="changeModalImage(1)">
                <i class="fa fa-chevron-right"></i>
            </button>
            <img id="modalImage" class="modal-image" src="" alt="">
            <div id="modalCaption" class="modal-caption"></div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

// Функция для закрытия модального окна
function closeImageModal() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Функция для изменения изображения в модальном окне
function changeModalImage(direction) {
    const galleryImages = document.querySelectorAll('.gallery-img');
    const modalImg = document.getElementById('modalImage');
    const modalCaption = document.getElementById('modalCaption');
    
    if (!galleryImages.length || !modalImg) return;
    
    let currentIndex = -1;
    for (let i = 0; i < galleryImages.length; i++) {
        if (galleryImages[i].src === modalImg.src) {
            currentIndex = i;
            break;
        }
    }
    
    if (currentIndex === -1) return;
    
    let newIndex = currentIndex + direction;
    
    if (newIndex < 0) {
        newIndex = galleryImages.length - 1;
    } else if (newIndex >= galleryImages.length) {
        newIndex = 0;
    }
    
    const newImg = galleryImages[newIndex];
    modalImg.src = newImg.src;
    modalCaption.textContent = newImg.alt || 'Изображение';
}

// Функция для определения пропорций изображений
function detectImageOrientation() {
    const images = document.querySelectorAll('.news-img, .article-img, .related-image img, .recent-news-image img');
    
    images.forEach(img => {
        if (img.complete) {
            setImageOrientation(img);
        } else {
            img.addEventListener('load', () => setImageOrientation(img));
        }
    });
}

// Функция для установки класса ориентации изображения
function setImageOrientation(img) {
    const container = img.closest('.news-image, .article-image, .related-image, .recent-news-image');
    if (!container) return;
    
    const aspectRatio = img.naturalWidth / img.naturalHeight;
    
    // Удаляем предыдущие классы
    container.classList.remove('portrait', 'landscape', 'square');
    
    // Определяем ориентацию
    if (aspectRatio > 1.2) {
        container.classList.add('landscape');
    } else if (aspectRatio < 0.8) {
        container.classList.add('portrait');
    } else {
        container.classList.add('square');
    }
}

// Функция для управления слайдером похожих новостей
let currentSlide = 0;
let totalSlides = 0;
let slidesPerView = 3;

function initRelatedNewsSlider() {
    const slider = document.getElementById('relatedSlider');
    const cards = slider.querySelectorAll('.related-card');
    
    if (!slider || cards.length === 0) return;
    
    totalSlides = cards.length;
    
    // Определяем количество слайдов на экране
    if (window.innerWidth <= 768) {
        slidesPerView = 1;
    } else if (window.innerWidth <= 1200) {
        slidesPerView = 2;
    } else {
        slidesPerView = 3;
    }
    
    updateSliderButtons();
}

function slideRelatedNews(direction) {
    const slider = document.getElementById('relatedSlider');
    if (!slider) return;
    
    const maxSlides = totalSlides - slidesPerView;
    
    if (direction === 'prev') {
        currentSlide = Math.max(0, currentSlide - 1);
    } else {
        currentSlide = Math.min(maxSlides, currentSlide + 1);
    }
    
    const translateX = -currentSlide * (100 / slidesPerView);
    slider.style.transform = `translateX(${translateX}%)`;
    
    updateSliderButtons();
}

function updateSliderButtons() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    if (prevBtn) {
        prevBtn.disabled = currentSlide === 0;
    }
    
    if (nextBtn) {
        nextBtn.disabled = currentSlide >= totalSlides - slidesPerView;
    }
}

// Обработчик изменения размера окна для слайдера
function handleSliderResize() {
    const slider = document.getElementById('relatedSlider');
    if (!slider) return;
    
    // Сбрасываем позицию слайдера
    currentSlide = 0;
    slider.style.transform = 'translateX(0)';
    
    // Переопределяем количество слайдов на экране
    if (window.innerWidth <= 768) {
        slidesPerView = 1;
    } else if (window.innerWidth <= 1200) {
        slidesPerView = 2;
    } else {
        slidesPerView = 3;
    }
    
    updateSliderButtons();
}

// Функция для добавления в закладки новости
function bookmarkNews(newsId) {
    const bookmarks = JSON.parse(localStorage.getItem('newsBookmarks') || '[]');
    
    if (bookmarks.includes(newsId)) {
        // Удаляем из закладок
        const index = bookmarks.indexOf(newsId);
        bookmarks.splice(index, 1);
        showNotification('Новость удалена из закладок', 'info');
    } else {
        // Добавляем в закладки
        bookmarks.push(newsId);
        showNotification('Новость добавлена в закладки', 'success');
    }
    
    localStorage.setItem('newsBookmarks', JSON.stringify(bookmarks));
    updateBookmarkButton(newsId);
}

// Функция для добавления в закладки статьи
function bookmarkArticle() {
    const currentUrl = window.location.href;
    const bookmarks = JSON.parse(localStorage.getItem('articleBookmarks') || '[]');
    
    if (bookmarks.includes(currentUrl)) {
        // Удаляем из закладок
        const index = bookmarks.indexOf(currentUrl);
        bookmarks.splice(index, 1);
        showNotification('Статья удалена из закладок', 'info');
    } else {
        // Добавляем в закладки
        bookmarks.push(currentUrl);
        showNotification('Статья добавлена в закладки', 'success');
    }
    
    localStorage.setItem('articleBookmarks', JSON.stringify(bookmarks));
    updateArticleBookmarkButton();
}

// Обновление кнопки закладки для новости
function updateBookmarkButton(newsId) {
    const bookmarks = JSON.parse(localStorage.getItem('newsBookmarks') || '[]');
    const button = document.querySelector(`[onclick="bookmarkNews(${newsId})"]`);
    
    if (button) {
        const icon = button.querySelector('i');
        if (bookmarks.includes(newsId)) {
            icon.className = 'fa fa-bookmark';
            button.classList.add('bookmarked');
        } else {
            icon.className = 'fa fa-bookmark-o';
            button.classList.remove('bookmarked');
        }
    }
}

// Обновление кнопки закладки для статьи
function updateArticleBookmarkButton() {
    const currentUrl = window.location.href;
    const bookmarks = JSON.parse(localStorage.getItem('articleBookmarks') || '[]');
    const button = document.querySelector('.bookmark-btn');
    
    if (button) {
        const icon = button.querySelector('i');
        if (bookmarks.includes(currentUrl)) {
            icon.className = 'fa fa-bookmark';
            button.classList.add('bookmarked');
        } else {
            icon.className = 'fa fa-bookmark-o';
            button.classList.remove('bookmarked');
        }
    }
}

// Функция для поделиться новостью
function shareNews(newsId) {
    const url = window.location.origin + '/news/view/' + newsId;
    const title = document.title;
    
    if (navigator.share) {
        // Нативная функция поделиться (для мобильных устройств)
        navigator.share({
            title: title,
            url: url
        }).catch(console.error);
    } else {
        // Fallback для десктопа
        showShareModal(url, title);
    }
}

// Функция для поделиться статьей
function shareArticle() {
    const url = window.location.href;
    const title = document.title;
    
    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        }).catch(console.error);
    } else {
        showShareModal(url, title);
    }
}

// Модальное окно для поделиться
function showShareModal(url, title) {
    const modal = document.createElement('div');
    modal.className = 'share-modal';
    modal.innerHTML = `
        <div class="share-modal-content">
            <div class="share-modal-header">
                <h3>Поделиться</h3>
                <button class="close-btn" onclick="closeShareModal()">&times;</button>
            </div>
            <div class="share-modal-body">
                <div class="share-options">
                    <button onclick="shareToFacebook('${url}', '${title}')" class="share-option facebook">
                        <i class="fa fa-facebook"></i>
                        Facebook
                    </button>
                    <button onclick="shareToTwitter('${url}', '${title}')" class="share-option twitter">
                        <i class="fa fa-twitter"></i>
                        Twitter
                    </button>
                    <button onclick="shareToVK('${url}', '${title}')" class="share-option vk">
                        <i class="fa fa-vk"></i>
                        VKontakte
                    </button>
                    <button onclick="shareToTelegram('${url}', '${title}')" class="share-option telegram">
                        <i class="fa fa-telegram"></i>
                        Telegram
                    </button>
                    <button onclick="copyToClipboard('${url}')" class="share-option copy">
                        <i class="fa fa-copy"></i>
                        Копировать ссылку
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Анимация появления
    setTimeout(() => modal.classList.add('show'), 10);
}

// Закрыть модальное окно
function closeShareModal() {
    const modal = document.querySelector('.share-modal');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.remove(), 300);
    }
}

// Поделиться в Facebook
function shareToFacebook(url, title) {
    const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
    window.open(shareUrl, '_blank', 'width=600,height=400');
    closeShareModal();
}

// Поделиться в Twitter
function shareToTwitter(url, title) {
    const shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`;
    window.open(shareUrl, '_blank', 'width=600,height=400');
    closeShareModal();
}

// Поделиться в VK
function shareToVK(url, title) {
    const shareUrl = `https://vk.com/share.php?url=${encodeURIComponent(url)}&title=${encodeURIComponent(title)}`;
    window.open(shareUrl, '_blank', 'width=600,height=400');
    closeShareModal();
}

// Поделиться в Telegram
function shareToTelegram(url, title) {
    const shareUrl = `https://t.me/share/url?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`;
    window.open(shareUrl, '_blank', 'width=600,height=400');
    closeShareModal();
}

// Копировать в буфер обмена
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showNotification('Ссылка скопирована!', 'success');
        }).catch(() => {
            fallbackCopyToClipboard(text);
        });
    } else {
        fallbackCopyToClipboard(text);
    }
    closeShareModal();
}

// Fallback для копирования
function fallbackCopyToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.select();
    try {
        document.execCommand('copy');
        showNotification('Ссылка скопирована!', 'success');
    } catch (err) {
        showNotification('Не удалось скопировать ссылку', 'error');
    }
    document.body.removeChild(textArea);
}

// Показать уведомление
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fa fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Анимация появления
    setTimeout(() => notification.classList.add('show'), 10);
    
    // Автоматическое скрытие
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Поиск новостей
function searchNews() {
    const searchInput = document.querySelector('.search-input');
    const searchTerm = searchInput.value.trim();
    
    if (searchTerm.length < 3) {
        showNotification('Введите минимум 3 символа для поиска', 'error');
        return;
    }
    
    // Показываем индикатор загрузки
    showLoadingIndicator();
    
    // Отправляем AJAX запрос
    fetch('/news/search', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            search: searchTerm
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoadingIndicator();
        
        if (data.success) {
            updateNewsList(data.news);
        } else {
            showNotification(data.error || 'Ошибка поиска', 'error');
        }
    })
    .catch(error => {
        hideLoadingIndicator();
        showNotification('Ошибка соединения', 'error');
        console.error('Search error:', error);
    });
}

// Функция для обновления списка новостей при поиске
function updateNewsList(newsData) {
    const newsGrid = document.querySelector('.news-grid');
    if (!newsGrid) return;
    
    newsGrid.innerHTML = '';
    
    newsData.forEach(item => {
        const newsCard = document.createElement('div');
        newsCard.className = 'news-card';
        newsCard.setAttribute('data-aos', 'fade-up');
        
        const imageHtml = item.news_image ? 
            `<div class="news-image">
                <img src="/${item.news_image}" alt="${item.news_title}" class="news-img">
                <div class="news-image-overlay">
                    <div class="news-category-badge">
                        ${item.category_name || 'Новость'}
                    </div>
                </div>
            </div>` : '';
        
        newsCard.innerHTML = `
            ${imageHtml}
            <div class="news-card-content">
                <div class="news-meta">
                    <span><i class="fa fa-calendar"></i> ${formatDate(item.news_date_add)}</span>
                    <span><i class="fa fa-user"></i> ${item.news_author || 'Администрация'}</span>
                    <span class="news-time"><i class="fa fa-clock-o"></i> ${formatTime(item.news_date_add)}</span>
                </div>
                <h3 class="news-title">
                    <a href="/news/view/${item.news_id}">${item.news_title}</a>
                </h3>
                <p class="news-excerpt">${item.news_text.substring(0, 200)}...</p>
                <div class="news-actions">
                    <a href="/news/view/${item.news_id}" class="btn btn-primary">
                        <i class="fa fa-eye"></i> Читать далее
                    </a>
                    <button class="bookmark-btn" onclick="bookmarkNews(${item.news_id})" data-news-id="${item.news_id}">
                        <i class="fa fa-bookmark"></i> Закладка
                    </button>
                </div>
            </div>
        `;
        
        newsGrid.appendChild(newsCard);
    });
    
    // Инициализируем пропорции изображений для новых карточек
    setTimeout(() => {
        detectImageOrientation();
        initializeBookmarks();
    }, 100);
}

// Показать индикатор загрузки
function showLoadingIndicator() {
    const loading = document.createElement('div');
    loading.className = 'loading-indicator';
    loading.innerHTML = `
        <div class="loading-spinner">
            <i class="fa fa-spinner fa-spin"></i>
            <span>Поиск...</span>
        </div>
    `;
    
    document.body.appendChild(loading);
    setTimeout(() => loading.classList.add('show'), 10);
}

// Скрыть индикатор загрузки
function hideLoadingIndicator() {
    const loading = document.querySelector('.loading-indicator');
    if (loading) {
        loading.classList.remove('show');
        setTimeout(() => loading.remove(), 300);
    }
}

// Вспомогательные функции
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('ru-RU');
}

function formatTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function substr(text, start, length) {
    return text.substring(start, start + length);
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Обработчик поиска
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            searchNews();
        });
    }
    
    // Обработчик клика вне модального окна
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('share-modal')) {
            closeShareModal();
        }
    });
    
    // Обработчик клавиши Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeShareModal();
        }
    });
    
    // Инициализация кнопок закладок
    initializeBookmarks();

    // Инициализация слайдера похожих новостей
    initRelatedNewsSlider();
    window.addEventListener('resize', handleSliderResize);

    // Инициализация определения пропорций изображений
    detectImageOrientation();
});

// Инициализация закладок
function initializeBookmarks() {
    // Обновляем кнопки закладок для новостей
    const bookmarkButtons = document.querySelectorAll('.bookmark-btn');
    bookmarkButtons.forEach(button => {
        const onclick = button.getAttribute('onclick');
        if (onclick && onclick.includes('bookmarkNews')) {
            const newsId = onclick.match(/bookmarkNews\((\d+)\)/)?.[1];
            if (newsId) {
                updateBookmarkButton(newsId);
            }
        }
    });
    
    // Обновляем кнопку закладки для статьи
    updateArticleBookmarkButton();
}

// CSS стили для модального окна и уведомлений
const styles = `
<style>
.share-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.share-modal.show {
    opacity: 1;
}

.share-modal-content {
    background: white;
    border-radius: 15px;
    padding: 0;
    max-width: 400px;
    width: 90%;
    transform: scale(0.9);
    transition: transform 0.3s ease;
}

.share-modal.show .share-modal-content {
    transform: scale(1);
}

.share-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #eee;
}

.share-modal-header h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 18px;
}

.close-btn {
    background: none;
    border: none;
    font-size: 24px;
    color: #6c757d;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.close-btn:hover {
    background: #f8f9fa;
    color: #dc3545;
}

.share-modal-body {
    padding: 25px;
}

.share-options {
    display: grid;
    gap: 15px;
}

.share-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px 20px;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    color: white;
}

.share-option.facebook { background: #3b5998; }
.share-option.twitter { background: #1da1f2; }
.share-option.vk { background: #4a76a8; }
.share-option.telegram { background: #0088cc; }
.share-option.copy { background: #6c757d; }

.share-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border-radius: 12px;
    padding: 15px 20px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    z-index: 10001;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    max-width: 300px;
}

.notification.show {
    transform: translateX(0);
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-success { border-left: 4px solid #28a745; }
.notification-error { border-left: 4px solid #dc3545; }
.notification-info { border-left: 4px solid #17a2b8; }

.loading-indicator {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10002;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.loading-indicator.show {
    opacity: 1;
}

.loading-spinner {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    color: #667eea;
    font-size: 18px;
    font-weight: 600;
}

.loading-spinner i {
    font-size: 32px;
}

/* Стили для кнопок закладок */
.btn-icon.bookmarked {
    background: #667eea;
    color: white;
}

.btn-icon.bookmarked:hover {
    background: #5a6fd8;
}
</style>
`;

// Добавляем стили в head
document.head.insertAdjacentHTML('beforeend', styles); 