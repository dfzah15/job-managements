// Service Worker untuk PWA - Versi dengan Strategi Hybrid
const CACHE_NAME = 'job-management-v3';
const STATIC_CACHE = 'static-v3';
const DYNAMIC_CACHE = 'dynamic-v3';

// Asset statis yang di-cache (hanya file penting)
const urlsToCache = [
    '/',
    '/manifest.json'
    // Jangan cache CSS/JS dulu, biar dinamis
];

// Install Service Worker
self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(function(cache) {
                console.log('Opened static cache');
                return cache.addAll(urlsToCache);
            })
            .then(() => self.skipWaiting())
    );
});

// Activate Service Worker
self.addEventListener('activate', function(event) {
    const cacheWhitelist = [STATIC_CACHE, DYNAMIC_CACHE];
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
        .then(() => self.clients.claim())
    );
});

// ============================================
// STRATEGI FETCH YANG LEBIH CERDAS
// ============================================
self.addEventListener('fetch', function(event) {
    const url = new URL(event.request.url);
    
    // ============================================
    // 1. API REQUESTS - Network First (No Cache)
    // ============================================
    if (url.pathname.includes('/api/') || 
        url.pathname.includes('/layout/') || 
        url.pathname.includes('/upload-bg') ||
        url.pathname.includes('/device') ||
        url.pathname.includes('/connection') ||
        url.pathname.includes('/update-positions')) {
        
        event.respondWith(
            fetch(event.request, {
                headers: {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                }
            })
            .catch(() => {
                return new Response(JSON.stringify({
                    success: false,
                    message: 'Network error, please try again.'
                }), {
                    headers: { 'Content-Type': 'application/json' }
                });
            })
        );
        return;
    }
    
    // ============================================
    // 2. LAYOUT BACKGROUND IMAGES - Network First
    // ============================================
    if (url.pathname.includes('/layouts/backgrounds/')) {
        event.respondWith(
            fetch(event.request, {
                headers: {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                }
            })
            .then(response => {
                // Jangan cache gambar layout agar selalu fresh
                return response;
            })
            .catch(() => {
                // Fallback ke cache jika offline
                return caches.match(event.request);
            })
        );
        return;
    }
    
    // ============================================
    // 3. IMAGES (Non-Layout) - Cache First dengan Update
    // ============================================
    if (url.pathname.match(/\.(png|jpg|jpeg|gif|svg|webp|ico)$/)) {
        event.respondWith(
            caches.match(event.request)
                .then(response => {
                    // Jika ada di cache, kembalikan
                    if (response) {
                        // Update cache di background (stale-while-revalidate)
                        fetch(event.request)
                            .then(freshResponse => {
                                if (freshResponse.status === 200) {
                                    caches.open(DYNAMIC_CACHE)
                                        .then(cache => cache.put(event.request, freshResponse));
                                }
                            })
                            .catch(() => {});
                        return response;
                    }
                    // Jika tidak ada di cache, fetch dan cache
                    return fetch(event.request)
                        .then(freshResponse => {
                            const clonedResponse = freshResponse.clone();
                            caches.open(DYNAMIC_CACHE)
                                .then(cache => cache.put(event.request, clonedResponse));
                            return freshResponse;
                        });
                })
        );
        return;
    }
    
    // ============================================
    // 4. STATIC ASSETS (CSS, JS) - Cache First
    // ============================================
    if (url.pathname.match(/\.(css|js|woff|woff2|ttf|eot)$/)) {
        event.respondWith(
            caches.match(event.request)
                .then(response => {
                    return response || fetch(event.request)
                        .then(freshResponse => {
                            const clonedResponse = freshResponse.clone();
                            caches.open(STATIC_CACHE)
                                .then(cache => cache.put(event.request, clonedResponse));
                            return freshResponse;
                        });
                })
        );
        return;
    }
    
    // ============================================
    // 5. HTML PAGES - Network First
    // ============================================
    if (url.pathname.endsWith('/') || url.pathname.match(/\.(html|htm)$/)) {
        event.respondWith(
            fetch(event.request)
                .catch(() => {
                    return caches.match('/');
                })
        );
        return;
    }
    
    // ============================================
    // 6. DEFAULT - Cache First
    // ============================================
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request)
                    .then(freshResponse => {
                        const clonedResponse = freshResponse.clone();
                        caches.open(DYNAMIC_CACHE)
                            .then(cache => cache.put(event.request, clonedResponse));
                        return freshResponse;
                    });
            })
    );
});

// ============================================
// MESSAGE HANDLER - Untuk kontrol dari client
// ============================================
self.addEventListener('message', function(event) {
    if (event.data === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    
    if (event.data === 'CLEAR_CACHE') {
        caches.keys().then(keys => {
            keys.forEach(key => {
                caches.delete(key);
            });
        });
        event.ports[0].postMessage('Cache cleared');
    }
    
    if (event.data === 'RELOAD_PAGE') {
        self.clients.matchAll().then(clients => {
            clients.forEach(client => {
                client.postMessage('RELOAD_PAGE');
            });
        });
    }
});