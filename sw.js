/* Villa Andie — service worker.
   Cache-first for static assets, network-first for pages with an
   offline fallback to the cached homepage. Admin and form endpoints
   are never intercepted. */
const VERSION = 'villa-andie-v1';
const OFFLINE_URL = './';

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(VERSION).then((cache) => cache.addAll([OFFLINE_URL])).then(() => self.skipWaiting()),
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys()
      .then((keys) => Promise.all(keys.filter((k) => k !== VERSION).map((k) => caches.delete(k))))
      .then(() => self.clients.claim()),
  );
});

self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  if (request.method !== 'GET') return;
  if (url.origin !== self.location.origin) return;
  if (url.pathname.includes('/admin') || url.pathname.endsWith('/enquiry')) return;
  if (url.searchParams.has('preview')) return;

  // Static assets: cache-first (they carry cache-busting ?v= params)
  if (url.pathname.includes('/assets/') || url.pathname.endsWith('manifest.json')) {
    event.respondWith(
      caches.match(request).then((hit) => hit
        || fetch(request).then((response) => {
          const copy = response.clone();
          if (response.ok) caches.open(VERSION).then((cache) => cache.put(request, copy));
          return response;
        })),
    );
    return;
  }

  // Pages: network-first, offline fallback
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request)
        .then((response) => {
          const copy = response.clone();
          if (response.ok) caches.open(VERSION).then((cache) => cache.put(request, copy));
          return response;
        })
        .catch(() => caches.match(request).then((hit) => hit || caches.match(OFFLINE_URL))),
    );
  }
});
