// Basic service worker
//just to test 
self.addEventListener('install', event => {
  console.log('Service Worker installed');
});

self.addEventListener('fetch', event => {
  const req = event.request;
  if (req.method !== 'GET') {
    return;
  }
  event.respondWith(
    fetch(req).catch((err) => {
      console.error('SW fetch failed:', err);
      return new Response('', { status: 502, statusText: 'Bad Gateway' });
    })
  );
});
