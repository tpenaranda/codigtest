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
      <table id="areas-table" class="table table-striped table-bordered table-hover table-responsive-xs">
        <thead>
          <tr>
            <th scope="col" class="text-center">Acciones</th>
            <th scope="col" class="text-center">ID</th>
            <th scope="col">Descripción</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <div class="col"></div>
  </div>
  <div class="row">
    <div class="col"></div>
    <div class="col-12 col-md-6 py-2 px-5">
      <button class="btn btn-block btn-primary" data-toggle="modal" data-target="#modalAgregar" id="btnAdd" title="Nueva">Agregar</button>
    </div>
    <div class="col"></div>
  </div>
  <hr>
  <div class="row">
    <div class="col"></div>
    <div class="col-12 col-md-6 pt-2 px-5 text-center">
      <p class="font-weight-normal my-1" style="font-size: 1.6rem;">Cola POST</p>
    </div>
    <div class="col"></div>
  </div>

  <div class="row">
    <div class="col"></div>
    <div class="col-12 col-md-6 py-2 px-5">
      <button class="btn btn-block btn-primary" onclick="enviarCola()">Enviar</button>
      <button class="btn btn-block btn-primary" onclick="armarTablaCola()">Refrescar</button>
    </div>
    <div class="col"></div>
  </div>

  <div class="row">
    <div class="col"></div>
    <div class="col-12 col-md-6 py-2">
      <table id="queue-table" class="table table-responsive-xs">
        <thead>
          <tr>
            <th scope="col" class="text-center">ID</th>
            <th scope="col" class="text-center">URL</th>
            <th scope="col" class="text-center">Payload</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
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
      <div class="modal-body" id="cuerpoModalEditar">
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

  if (!indexedDB) {
      alert("Este browser no soporta IndexedDB, necesita otro para poder utilizar la aplicación.");
  }

  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/sw.js').catch(err => {
        console.log(`Service Worker registration failed: ${err}`);
      });

      // Hack, no clue wtf I'm doing here.
      if (!navigator.serviceWorker.controller) {
        // console.log('Reloading app due to missing ServiceWorker controller.')
        // location.reload()
      }
    });
  }

  armarTablaAreas()
  armarTablaCola()

  function enviarCola() {
    navigator.serviceWorker.getRegistration().then((registration) => registration.sync.register('sendPostData'))
  }

  function armarTablaAreas() {
    $('#areas-table > tbody').addClass('text-center').html('<tr><td colspan="3"><span class="spinner-border text-primary"></span></td></tr>');

    $.ajax({
      type: 'GET',
      dataType : "json",
      url: '/index.php/area/Listado_areas',
      success: function(results) {
        $('tbody').removeClass('text-center').html('');
        $.each(results, function(index, value) {
          $('#areas-table > tbody:last-child').append(`
              <tr>
                <td class="text-center">
                  <i class="fas fa-pencil-alt text-primary" style="cursor: pointer; margin-left: 15px;" title="Editar"></i>
                  <i class="fas fa-times-circle text-danger" title="Eliminar" style="cursor: pointer; margin-left: 15px;" ></i>
                </td>
                <td class="text-center">${value.id_area}</td>
                <td class="text-capitalize">${value.descripcion}</td>
              </tr>`);
        });
        makeDataTable();
      },
      error: function(result){
        $('tbody').html('<tr><td colspan="3">Error cargando áreas!</td></tr>');
      }
    });
  }

  function armarTablaCola() {
    indexedDB.open("codigtest-ajax").onsuccess = function (event) {
      if (event.target.result.objectStoreNames.contains('ajax_requests')) {
        event.target.result.transaction('ajax_requests').objectStore('ajax_requests').getAll().onsuccess = function (event) {
          $('#queue-table > tbody').html('')
          $.each(event.target.result.reverse(), function(index, value) {
            $('#queue-table > tbody:last-child').append(`
                <tr>
                  <td class="text-center">${value.id}</td>
                  <td>${value.url}</td>
                  <td>${JSON.stringify(value.payload)}</td>
                </tr>`);
          });
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

    var postData = { "descripcion": descripcion,  "id_empresa": id_empresa };

    navigator.serviceWorker.controller.postMessage({ post_data: postData })

    $.ajax({
      type: 'POST',
      data: postData,
      dataType: 'json',
      url: '/index.php/area/Guardar_area'
    }).done((result) => {
      armarTablaAreas()
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

    $.ajax({
      type: 'POST',
      data: { "id_area" : id_,  "descripcion": descripcion,  "id_empresa": id_empresa },
      dataType: 'json',
      url: '/index.php/area/Modificar_area',
    }).done((result) => {
      armarTablaAreas()
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
      armarTablaAreas()
    }).fail((result) => {
      armarTablaCola()
    }).always(() => {
      $('#modalEliminar').modal('hide')
    });
  }

  function makeDataTable() {
    $('#areas-table').DataTable().destroy()

    $('.fa-pencil-alt').click(function () {
        id_ = $(this).parents('tr').find('td').eq(1).html();

        $('#descripcionEA').val($(this).parents('tr').find('td').eq(2).html());

        $('#modalEditar').modal('show');
    });

    $('.fa-times-circle').click(function () {
      id_ = $(this).parents('tr').find('td').eq(1).html();

      $('#modalEliminar').modal('show');
    });

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
        }
    });
  }
</script>
