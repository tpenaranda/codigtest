importScripts('https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js');

var appDB;
var postData;
const IDB_VERSION = 2;
const STORE_NAME = 'ajax_requests';

openDatabase();

function openDatabase () {
  var indexedDBOpenRequest = indexedDB.open('codigtest-ajax', IDB_VERSION);

  indexedDBOpenRequest.onerror = function (error) {
    console.error('IndexedDB error:', error);
  };

  indexedDBOpenRequest.onupgradeneeded = function () {
    this.result.createObjectStore(STORE_NAME, {
        autoIncrement:  true,
        keyPath: 'id'
    });
  };

  indexedDBOpenRequest.onsuccess = function () {
    appDB = this.result;
  }
}

self.addEventListener('message', function (event) {
  if (event.data.hasOwnProperty('post_data')) {
    postData = event.data.post_data
  }
})

workbox.precaching.precacheAndRoute([
    { url: '/index.php', revision: 'abc123' },
]);

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

self.addEventListener('fetch', function(event) {
  if (event.request.clone().method === 'POST') {
    event.respondWith(
      fetch(event.request.clone()).catch(function (error) {
        console.log('POST Saved to IndexedDB:', event.request.clone().url);
        return storePostRequest(event.request.clone().url, postData);
      })
    );
  } else {
    event.respondWith(
      fetch(event.request).then(function(response) {
          return response;
      }).catch(function() {
        caches.match(event.request).then(function(response) {
          if (response) {
            console.log('GET Reply from Cache for URL:', event.request.clone().url);
            return response;
          }
        });
      })
    );
  }
});

function storePostRequest(url, payload) {
  var request = appDB.transaction(STORE_NAME, 'readwrite').objectStore(STORE_NAME).add({
    url: url,
    payload: payload,
    method: 'POST'
  });

  request.onsuccess = function (event) {
    console.log('POST Request added to IndexedDB');
  }

  request.onerror = function (error) {
    console.log('Error when adding POST Request to IndexedDB');
  }
}
