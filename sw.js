// ===== SERVICE WORKER ДЛЯ GTEC =====

const CACHE_NAME = 'gtec-v1.0.0';
const STATIC_CACHE = 'gtec-static-v1.0.0';
const DYNAMIC_CACHE = 'gtec-dynamic-v1.0.0';

// Файлы для кэширования
const STATIC_FILES = [
    '/assets/css/optimized.css',
    '/assets/css/main-styles.css',
    '/assets/css/main-page.css',
    '/assets/css/header.css',
    '/assets/css/footer.css',
    '/assets/js/optimized.js',
    '/assets/js/main-page.js',
    '/assets/js/header.js',
    '/assets/font-manrope/Manrope-VariableFont_wght.ttf',
    '/assets/css/font-awesome/css/font-awesome.css',
    '/assets/css/font-awesome/fonts/fontawesome-webfont.woff2'
];

// Установка Service Worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => {
                console.log('Кэширование статических файлов');
                return cache.addAll(STATIC_FILES);
            })
            .then(() => {
                console.log('Service Worker установлен');
                return self.skipWaiting();
            })
    );
});

// Активация Service Worker
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
                        console.log('Удаление старого кэша:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => {
            console.log('Service Worker активирован');
            return self.clients.claim();
        })
    );
});

// Перехват запросов
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Стратегия кэширования для статических файлов
    if (STATIC_FILES.includes(url.pathname)) {
        event.respondWith(
            caches.match(request)
                .then(response => {
                    if (response) {
                        return response;
                    }
                    return fetch(request).then(response => {
                        if (response.status === 200) {
                            const responseClone = response.clone();
                            caches.open(STATIC_CACHE).then(cache => {
                                cache.put(request, responseClone);
                            });
                        }
                        return response;
                    });
                })
        );
        return;
    }

    // Стратегия кэширования для изображений
    if (request.destination === 'image') {
        event.respondWith(
            caches.match(request)
                .then(response => {
                    if (response) {
                        return response;
                    }
                    return fetch(request).then(response => {
                        if (response.status === 200) {
                            const responseClone = response.clone();
                            caches.open(DYNAMIC_CACHE).then(cache => {
                                cache.put(request, responseClone);
                            });
                        }
                        return response;
                    });
                })
        );
        return;
    }

    // Стратегия Network First для остальных запросов
    event.respondWith(
        fetch(request)
            .then(response => {
                if (response.status === 200) {
                    const responseClone = response.clone();
                    caches.open(DYNAMIC_CACHE).then(cache => {
                        cache.put(request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                return caches.match(request);
            })
    );
});

// Обработка push уведомлений
self.addEventListener('push', event => {
    const options = {
        body: event.data ? event.data.text() : 'Новое уведомление',
        icon: '/assets/imageicons/favicon.png',
        badge: '/assets/imageicons/favicon.png',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        },
        actions: [
            {
                action: 'explore',
                title: 'Открыть сайт',
                icon: '/assets/imageicons/favicon.png'
            },
            {
                action: 'close',
                title: 'Закрыть',
                icon: '/assets/imageicons/favicon.png'
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification('GTEC', options)
    );
});

// Обработка кликов по уведомлениям
self.addEventListener('notificationclick', event => {
    event.notification.close();

    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});

// Периодическая синхронизация
self.addEventListener('sync', event => {
    if (event.tag === 'background-sync') {
        event.waitUntil(
            // Здесь можно добавить логику синхронизации
            console.log('Фоновая синхронизация')
        );
    }
}); 