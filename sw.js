importScripts('https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js');

var appDB;
var postData;
const IDB_VERSION = 1;
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

self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open('codigtest-cache').then(function(cache) {
      return cache.addAll([
        'index.php',
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
    event.respondWith(fetch(event.request.clone()).catch(function (error) {
      console.log('POST Saved to IndexedDB:', event.request.clone().url);
      return storePostRequest(event.request.clone().url, postData);
    }));
  } else {
    event.respondWith(
      caches.match(event.request).then(function(response) {
        if (response) {
          console.log('GET Reply from Cache for URL:', event.request.clone().url);
          return response;
        }

        console.log('GET Reply from Network for URL:', event.request.clone().url);
        return fetch(event.request.clone());
        }
      )
    );
  }
});

self.addEventListener('sync', function (event) {
  if (event.tag === 'sendPostData') {
    event.waitUntil(sendPostToServer())
  }
});

function sendPostToServer() {
  var savedRequests = []
  var req = getObjectStore(STORE_NAME).openCursor()

  req.onsuccess = async function (event) {
    var cursor = event.target.result

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
         if (response.status < 400) {
          getObjectStore(STORE_NAME, 'readwrite').delete(savedRequest.id);
         }
       }).catch(function (error) {
         console.error('Send to Server failed:', error);
         throw error;
       })
      }
    }
  }
}

function getObjectStore(storeName, mode) {
  return appDB.transaction(storeName, mode).objectStore(storeName);
}

function storePostRequest(url, payload) {
  var request = getObjectStore(STORE_NAME, 'readwrite').add({
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
