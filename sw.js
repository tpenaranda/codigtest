importScripts('https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js');

var messageData;

workbox.precaching.precacheAndRoute([
    { url: '/index.php', revision: 'abc123' },
]);

self.addEventListener('fetch', function(event) {
  if (event.request.clone().method === 'POST') {
    event.respondWith(
      fetch(event.request.clone()).catch(function (error) {
        storePostRequest(event.request.clone().url, messageData);
        return new Response();
      })
    );
  } else {
    event.respondWith(
      fetch(event.request).then(function(response) {
        let responseClone = response.clone();
        caches.open('codigtest-cache').then(function (cache) {
          cache.put(event.request, responseClone);
        });

        return response;
      }).catch(function() {
        return caches.match(event.request).then(function(response) {
          if (response) {
            console.log('GET Reply from Cache for URL:', event.request.clone().url);
            return response;
          }

          return new Response('', {
            headers: { 'Content-Type': 'text/html' }
          });
        });

      })
    );
  }
});

self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open('codigtest-cache').then(function(cache) {
      return cache.addAll([
        '/index.php/area/Listado_areas',

        'static/css/bootstrap.min.css',
        'static/css/jquery.dataTables.min.css',
        'static/css/buttons.dataTables.min.css',

        'static/js/jquery-3.3.1.min.js',
        'static/js/popper.min.js',
        'static/js/bootstrap.min.js',
        'static/js/jquery.dataTables.min.js',
        'static/js/dataTables.buttons.min.js',
        'static/js/buttons.print.min.js',
        'static/js/all.js',

        'static/images/sort_asc.png',
        'static/images/sort_desc.png',
        'static/images/sort_both.png',

        'favicon.ico'
      ]);
    })
  );
});


self.addEventListener('message', function (event) {
  if (event.data === 'processQueue') {
    event.waitUntil(processQueue())
  } else {
    messageData = event.data
  }
})

function processQueue() {
  indexedDB.open('codigtest-ajax').onsuccess = function (event) {
    var savedRequests = []
    event.target.result.transaction('ajax_requests', 'readonly').objectStore('ajax_requests').openCursor().onsuccess = async function (openCursorEvent) {
      var cursor = openCursorEvent.target.result

      if (cursor) {
        savedRequests.push(cursor.value)
        cursor.continue()
      } else {
        for (let savedRequest of savedRequests) {
          var requestUrl = savedRequest.url
          var payload = Object.keys(savedRequest.payload).map(key => key + '=' + savedRequest.payload[key]).join('&');
          var method = savedRequest.method
          var headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded',
          }

          fetch(requestUrl, {
            headers: headers,
            method: method,
            body: payload
          }).then(function (response) {
            event.target.result.transaction('ajax_requests', 'readwrite').objectStore('ajax_requests', 'readwrite').delete(savedRequest.id);
          }).catch(function (error) {
            console.error('Send to Server failed:', error);
            throw error;
          })
        }
      }
    }
  }
}

function storePostRequest(url, payload) {
  indexedDB.open('codigtest-ajax').onsuccess = function (event) {
    event.target.result.transaction('ajax_requests', 'readwrite').objectStore('ajax_requests').add({
      url: url,
      payload: payload,
      method: 'POST'
    }).onsuccess = function (event) {
      console.log('POST Request added to IndexedDB');
    };
  }
}
