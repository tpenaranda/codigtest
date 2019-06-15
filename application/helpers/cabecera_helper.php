<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('cargarCabecera')){
    // id de pedido es el petr_id de la tabla trj_pedido_trabajo se obtiene con el bpm_id de esa tabla
    function cargarCabecera($id_OT = null, $id_SS = null, $id_EQ = null){
			// equipo
			if($id_EQ != null){
				
				//get main CodeIgniter object
				$ci =& get_instance();			
				//load databse library
				$ci->load->database();			
				//get data from database		
				$ci->db->select('equipos.descripcion AS descripcionEquipo,
												equipos.fecha_ingreso,
												equipos.marca,
												equipos.codigo,
												equipos.ubicacion,
												equipos.estado');	
				$ci->db->from('equipos');			
				$ci->db->where('equipos.id_equipo', $id_EQ);
				$query = $ci->db->get();			
				if($query->num_rows() > 0){
						$result = $query->row_array();
				}
			}
			// Solic Servicios			
			if($id_SS != null){
				$ci->db->select('solicitud_reparacion.id_solicitud,
												solicitud_reparacion.solicitante,
												solicitud_reparacion.causa,
												solicitud_reparacion.f_solicitado,
												equipos.codigo AS equipo,
												sector.descripcion AS descripcionSector,
												grupo.descripcion AS descripcionGrupo');	
				$ci->db->from('solicitud_reparacion');
				$ci->db->join('equipos', 'solicitud_reparacion.id_equipo = equipos.id_equipo');
				$ci->db->join('sector', 'equipos.id_sector = sector.id_sector');
				$ci->db->join('grupo', 'equipos.id_grupo = grupo.id_grupo');			
				$ci->db->where('solicitud_reparacion.id_solicitud', $id_SS);
				$querySS = $ci->db->get();			
				if($querySS->num_rows() > 0){
						$resultSS = $querySS->row_array();
				}
			}	
			// OT
			if($id_OT != null){
				$ci->db->select('tareas.descripcion AS tareaDescrip,
												orden_trabajo.descripcion AS otDescrip,
												orden_trabajo.fecha,
												orden_trabajo.id_orden,
												orden_trabajo.duracion,
												orden_trabajo.estado');	
				$ci->db->from('orden_trabajo');		
				$ci->db->join('tareas', 'tareas.id_tarea = orden_trabajo.id_tarea','left');			
				$ci->db->where('orden_trabajo.id_orden', $id_OT);
				$queryOT = $ci->db->get();			
				if($queryOT->num_rows() > 0){
						$resultOT = $queryOT->row_array();
				}
			}		

			// Info Equipo 
			echo '        
						<div id="collapseDivCli" class="box box-default collapsed-box box-solid">
							<div class="box-header with-border">
								<h3 id="tituloInfo" class="box-title">Equipo: '.$result['descripcionEquipo'].' / Mas Detalles</h3>
								<div class="box-tools pull-right">
								<button id="infoCliente" type="button" class="btn btn-box-tool" data-widget="collapse" onclick="mostrarCliente()">
										<i class="fa fa-plus"></i>
								</button>
							</div>
									<!-- /.box-tools -->
							</div>
								<!-- /.box-header -->
								<div class="box-body">
										
									<div class="col-xs-12 col-sm-4">
										<div class="form-group">
												<label style="margin-top: 7px;">Marca: </label>
												<input type="text" id="marca" class="form-control" value="'.$result['marca'].'" disabled/>
										</div>						
									</div>
									<div class="col-xs-12 col-sm-4">
										<div class="form-group">
												<label style="margin-top: 7px;">Código: </label>
												<input type="text" id="codigo" class="form-control" value="'.$result['codigo'].'" disabled/>
										</div>
									</div>
									<div class="col-xs-12 col-sm-4">
										<label style="margin-top: 7px;">Ubicación: </label>
										<input type="text" id="domicilio" class="form-control"  value="'.$result['ubicacion'].'" disabled/>
									</div>
									<div class="clearfix"></div>
									<div class="col-xs-12 col-sm-4">
										<label style="margin-top: 7px;">Descripción: </label>
										<input type="text" id="domicilio" class="form-control"  value="'.$result['descripcionEquipo'].'" disabled/>
									</div>									
									<div class="col-xs-12 col-sm-4">
										<label style="margin-top: 7px;">Fecha Ingreso: </label>
										<input type="text" id="domicilio" class="form-control"  value="'.$result['fecha_ingreso'].'" disabled/>
									</div>
									<div class="col-xs-12 col-sm-4">
										<label style="margin-top: 7px;">Estado: </label>
										<input type="text" id="domicilio" class="form-control"  value="'.$result['estado'].'" disabled/>
									</div>

									

								</div>
								<!-- /.box-body -->
						</div>
					
						<!-- /.box-body -->
				</div>
			<!-- /.box-body -->';

			// Solicitud Servicios
			if($id_SS != null){
				echo '        
				<div id="collapseDivCli" class="box box-default collapsed-box box-solid">
					<div class="box-header with-border">
						<h3 id="tituloInfo" class="box-title">Solicitud de Servicios Nº '.$resultSS['id_solicitud'].' / Mas Detalles</h3>
						<div class="box-tools pull-right">
						<button id="infoCliente" type="button" class="btn btn-box-tool" data-widget="collapse" onclick="mostrarCliente()">
								<i class="fa fa-plus"></i>
						</button>
					</div>
							<!-- /.box-tools -->
					</div>
						<!-- /.box-header -->
						<div class="box-body">
								
							<div class="col-xs-12 col-sm-4">
								<div class="form-group">
										<label style="margin-top: 7px;">Solicitante: </label>
										<input type="text" id="marca" class="form-control" value="'.$resultSS['solicitante'].'" disabled/>
								</div>						
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group">
										<label style="margin-top: 7px;">Fecha: </label>
										<input type="text" id="marca" class="form-control" value="'.$resultSS['f_solicitado'].'" disabled/>
								</div>						
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group">
										<label style="margin-top: 7px;">Causa: </label>
										<input type="text" id="marca" class="form-control" value="'.$resultSS['causa'].'" disabled/>
								</div>						
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group">
										<label style="margin-top: 7px;">Equipo: </label>
										<input type="text" id="codigo" class="form-control" value="'.$resultSS['equipo'].'" disabled/>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<label style="margin-top: 7px;">Grupo: </label>
								<input type="text" id="domicilio" class="form-control"  value="'.$resultSS['descripcionGrupo'].'" disabled/>
							</div>
							<div class="col-xs-12 col-sm-4">
								<label style="margin-top: 7px;">Sector: </label>
								<input type="text" id="domicilio" class="form-control"  value="'.$resultSS['descripcionSector'].'" disabled/>
							</div>
							
							

							

						</div>
						<!-- /.box-body -->
				</div>
			
				<!-- /.box-body -->
				</div>
				<!-- /.box-body -->';
			}

			// Orden Trabajo
			if($id_OT != null){
				echo '        
				<div id="collapseDivCli" class="box box-default collapsed-box box-solid">
					<div class="box-header with-border">
						<h3 id="tituloInfo" class="box-title">Orden de Trabajo: '.$resultOT['id_orden'].' / Mas Detalles</h3>
						<div class="box-tools pull-right">
						<button id="infoCliente" type="button" class="btn btn-box-tool" data-widget="collapse" onclick="mostrarCliente()">
								<i class="fa fa-plus"></i>
						</button>
					</div>
							<!-- /.box-tools -->
					</div>
						<!-- /.box-header -->
						<div class="box-body">

							<div class="col-xs-12 col-sm-4">
								<div class="form-group">
										<label style="margin-top: 7px;">Nº Orden Trabajo: </label>
										<input type="text" id="marca" class="form-control" value="'.$resultOT['id_orden'].'" disabled/>
								</div>						
							</div>
								
							<div class="col-xs-12 col-sm-4">
								<div class="form-group">
										<label style="margin-top: 7px;">Descripción: </label>
										<input type="text" id="marca" class="form-control" value="'.$resultOT['otDescrip'].'" disabled/>
								</div>						
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group">
										<label style="margin-top: 7px;">Fecha: </label>
										<input type="text" id="codigo" class="form-control" value="'.$resultOT['fecha'].'" disabled/>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<label style="margin-top: 7px;">Duración: </label>
								<input type="text" id="domicilio" class="form-control"  value="'.$resultOT['duracion'].'" disabled/>
							</div>
							
							<div class="col-xs-12 col-sm-4">
								<label style="margin-top: 7px;">Tarea: </label>
								<input type="text" id="domicilio" class="form-control"  value="'.$resultOT['tareaDescrip'].'" disabled/>
							</div>
							
							<div class="col-xs-12 col-sm-4">
								<label style="margin-top: 7px;">Estado: </label>
								<input type="text" id="domicilio" class="form-control"  value="'.$resultOT['estado'].'" disabled/>
							</div>
							
							

							

						</div>
						<!-- /.box-body -->
				</div>
			
					<!-- /.box-body -->
				</div>
				<!-- /.box-body -->';
			}				
} } 

?>