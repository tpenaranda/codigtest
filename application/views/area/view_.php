<div class="container-fluid">
  <div class="row">
    <div class="col"></div>
    <div class="col-12 col-md-6 pt-2 px-5 text-center">
      <p class="font-weight-normal my-1" style="font-size: 1.6rem;">Areas</p>
    </div>
    <div class="col"></div>
  </div>
  <div class="row">
    <div class="col"></div>
    <div class="col-12 col-md-6 py-2">
      <table id="areas-table" class="table table-striped table-bordered table-hover table-responsive-xs"></table>
    </div>
    <div class="col"></div>
  </div>
  <div class="row">
    <div class="col"></div>
    <div class="col-12 col-md-6 py-2 px-5">
      <button class="btn btn-block btn-primary" data-toggle="modal" data-target="#modalAgregar" title="Nueva">Agregar</button>
    </div>
    <div class="col"></div>
  </div>
  <hr>
  <span id="sw-not-active" style="display: none;">
    <div class="row">
      <div class="col-12 text-center text-danger">
        El servicio de contenido offline no se ha cargado!
        <button class="btn btn-outline-info btn-sm ml-3" onclick="location.reload()">Recargar</button>
      </div>
    </div>
    <hr>
  </span>
  <div class="row">
    <div class="col"></div>
    <div class="col-12 col-md-6 pt-2 px-5 text-center">
      <p class="font-weight-normal my-1" style="font-size: 1.6rem;" onclick="armarTablaCola()">Cola POST
        <i style="display: none;" class="fas fa-sync" id="armar-cola-icon"></i>
        <i style="display: none;" class="fas fa-sync fa-spin" id="armar-cola-icon-spin"></i>
      </p>
    </div>
    <div class="col"></div>
  </div>

  <div class="row">
    <div class="col"></div>
    <div class="col-12 col-md-6 py-2 px-5">
      <button class="btn btn-block btn-primary" onclick="procesarCola()">Enviar</button>
    </div>
    <div class="col"></div>
  </div>

  <div class="row">
    <div class="col"></div>
    <div class="col-12 col-md-6 py-2">
      <table id="queue-table" class="table table-responsive-xs"></table>
    </div>
    <div class="col"></div>
  </div>
</div>

<div class="modal" id="modalAgregar">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Agregar área</h4>
        <button type="button" class="modal-title close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="row">
          <div class="col">
            <div class="alert alert-danger alert-dismissable form-error" style="display: none">
             <i class="icon fa fa-ban"></i> Revise que todos los campos esten completos
           </div>
         </div>
       </div>
       <div class="row">
         <div class="col">
           <label style="margin-top: 7px;">Descripción<strong class="text-danger">*</strong>: </label>
         </div>
         <div class="col">
           <input type="text" class="form-control" id="descripcionGA">
         </div>
       </div>
      </div>
      <div class="modal-footer">
        <span class="save-button">
          <button class="btn btn-primary" type="button" onclick="guardarArea()">Guardar</button>
        </span>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="modalEditar">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Editar área</h4>
        <button type="button" class="modal-title close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="row">
          <div class="col">
            <div class="alert alert-danger alert-dismissable form-error" style="display: none">
             <i class="icon fa fa-ban"></i> Revise que todos los campos esten completos
           </div>
         </div>
       </div>
       <div class="row">
         <div class="col">
           <label style="margin-top: 7px;">Descripción<strong class="text-danger">*</strong>: </label>
         </div>
         <div class="col">
           <input type="text" class="form-control" id="descripcionEA">
         </div>
       </div>
      </div>
      <div class="modal-footer">
        <span class="save-button">
          <button class="btn btn-primary" type="button" onclick="editarArea()">Guardar</button>
        </span>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="modalEliminar">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Eliminar área</h4>
        <button type="button" class="modal-title close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <h5>¿Desea eliminar el registro?</h5>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary" onclick="eliminarArea()" >Eliminar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

      </div>
    </div>
  </div>
</div>

<script>
  var id_;
  var revision = '<?= md5(time()) ?>';
  var requestsQueue = [];
  var isServiceWorkerControllerActive = false;

  if (!indexedDB) {
      alert("Este browser no soporta IndexedDB, necesita otro para poder utilizar la aplicación.");
  }

  indexedDB.open('codigtest-ajax').onupgradeneeded = function (event) {
    event.target.result.createObjectStore('ajax_requests', {
      autoIncrement:  true,
      keyPath: 'id'
    });
  };

  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/sw.js').catch(err => {
        console.log(`Service Worker registration failed: ${err}`);
        $('#sw-not-active').show()
      });
      if (!navigator.serviceWorker.controller) {
        $('#sw-not-active').show()
      }
    });
  }

  $('#areas-table').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": true,
    "language": {
          "lengthMenu": "Ver _MENU_ filas por página",
          "zeroRecords": "No hay registros",
          "info": "Mostrando pagina _PAGE_ de _PAGES_",
          "infoEmpty": "No hay registros disponibles",
          "infoFiltered": "(filtrando de un total de _MAX_ registros)",
          "sSearch": "Buscar:  ",
          "oPaginate": {
              "sNext": "Sig.",
              "sPrevious": "Ant."
            }
    },
    ajax: {
        url: '/index.php/area/Listado_areas',
        dataSrc: 'data',
        cache: true
    },
    "columns": [{
      "data":"id_area",
      "title": "ID"
    },{
      "data": "descripcion",
      "title": "Descripción",
    },{
      "data": null,
      "title": "Acciones",
      "render": function (data, type, full) {
        return `
          <i class="fas fa-pencil-alt text-primary" style="cursor: pointer; margin-left: 15px;" title="Editar" onclick="abrirModalEditar('${data.id_area}', '${data.descripcion}')"></i>
          <i class="fas fa-times-circle text-danger" title="Eliminar" style="cursor: pointer; margin-left: 15px;" onclick="abrirModalEliminar(${data.id_area})"></i>`
      }
    }]
  }).order(0, 'desc').draw();

  armarTablaCola()

  function procesarCola() {
    if (navigator.serviceWorker.controller) {
      navigator.serviceWorker.controller.postMessage('processQueue')
    }
  }

  function armarTablaCola() {
    $('#armar-cola-icon-spin').show()
    $('#armar-cola-icon').hide()

    setTimeout(() => {
      $('#armar-cola-icon-spin').hide()
      $('#armar-cola-icon').show()
    }, 750)

    indexedDB.open("codigtest-ajax").onsuccess = function (event) {
      if (event.target.result.objectStoreNames.contains('ajax_requests')) {
        event.target.result.transaction('ajax_requests', 'readonly').objectStore('ajax_requests').getAll().onsuccess = function (event) {
          $('#queue-table').DataTable({
            destroy: true,
            ordering: true,
            searching: false,
            paging: false,
            info: false,
            data: event.target.result,
            "columns": [{
              "data":"id",
              "title": "ID"
            },{
              "data": "url",
              "title": "URL",
            }
            ,{
              "data": "payload",
              "title": "Payload",
              "render": function (data, type, full) {
                return JSON.stringify(data)
              }
            }]
          }).order(0, 'desc').draw();
        };
      }
    };
  }

  function guardarArea() {
    var descripcion=$('#descripcionGA').val();
    var id_empresa=$('#id_empresa').val();

    if (descripcion == '' || id_empresa == '') {
      return $('.form-error').fadeIn('slow');
    }

    $('.form-error').fadeOut('slow');

    $('.save-button').html('<span class="spinner-border text-primary" aria-hidden="true"></span>').addClass('disabled');

    var postData = { descripcion: descripcion,  id_empresa: id_empresa };

    if (navigator.serviceWorker.controller) {
      navigator.serviceWorker.controller.postMessage(postData)
    }

    $.ajax({
      type: 'POST',
      data: postData,
      dataType: 'json',
      url: '/index.php/area/Guardar_area'
    }).done((result) => {
      $('#areas-table').DataTable().ajax.reload()
    }).fail((result) => {
      armarTablaCola()
    }).always(() => {
      $('#modalAgregar').modal('hide')
      $('.save-button').html('<button class="btn btn-primary" type="button" onclick="guardarArea()">Guardar</button>').removeClass('disabled');
    });
  }

  function editarArea() {
    var descripcion=$('#descripcionEA').val();
    var id_empresa=$('#id_empresa').val();

    if (descripcion == '' || id_empresa == '') {
      return $('.form-error').fadeIn('slow');
    }

    $('#errorE').fadeOut('slow');

    $('.save-button').html('<span class="spinner-border text-primary" aria-hidden="true"></span>').addClass('disabled');

    var postData = { id_area : id_,  descripcion: descripcion,  id_empresa: id_empresa };

    if (navigator.serviceWorker.controller) {
      navigator.serviceWorker.controller.postMessage(postData)
    }

    $.ajax({
      type: 'POST',
      data: postData,
      dataType: 'json',
      url: '/index.php/area/Modificar_area',
    }).done((result) => {
      $('#areas-table').DataTable().ajax.reload()
    }).fail((result) => {
      armarTablaCola()
    }).always(() => {
      $('#modalEditar').modal('hide')
      $('.save-button').html('<button class="btn btn-primary" type="button" onclick="editarArea()">Guardar</button>').removeClass('disabled');
    });
  }

  function eliminarArea() {
    $.ajax({
      type: 'POST',
      data: { id_area: id_ },
      dataType: 'json',
      url: '/index.php/area/Eliminar_area',
    }).done((result) => {
      $('#areas-table').DataTable().ajax.reload()
    }).fail((result) => {
      armarTablaCola()
    }).always(() => {
      $('#modalEliminar').modal('hide')
    });
  }

  function abrirModalEditar(id_area, descripcion) {
    id_ = id_area

    $('#descripcionEA').val(descripcion);

    return $('#modalEditar').modal('show');
  };

  function abrirModalEliminar(id_area) {
    id_ = id_area

    return $('#modalEliminar').modal('show');
  };
</script>
