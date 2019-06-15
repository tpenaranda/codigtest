<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class area extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Areas');
	}

	public function index()					
	{
		$data['list'] = $this->Areas->Listado_areas();
		//$data['permission'] = $permission;
		$this->load->view('header');
		$this->load->view('area/view_', $data);
	}

	public function Obtener_area(){

		$id=$_POST['id_area'];
		$result = $this->Areas->Obtener_areas($id);
		echo json_encode($result);
	}

	public function Guardar_area(){

	    $descripcion=$this->input->post('descripcion');	   
	    // $data = array(
			// 			    'descripcion' => $descripcion,
			// 				'id_empresa' => $id_empresa,
			// 				'estado' => "AC"
			// );
			$data = array(
				'descripcion' => $descripcion,			
				'estado' => "AC"
			);
	    $sql = $this->Areas->Guardar_areas($data);
	    echo json_encode($sql);
	   
  	}
	//   	public function Modificar_area(){

  // 		$id=$this->input->post('id_area');
	//     $descripcion=$this->input->post('descripcion');
	//     $id_empresa=$this->input->post('id_empresa');
	//     $data = array(
	//     	    		   	'id_area' => $id,
	// 					    'descripcion' => $descripcion,
	// 				   );
	//     $sql = $this->Areas->Modificar_areas($data);
	//     echo json_encode($sql);

	//   }
	  
	// public function Eliminar_area(){
	
	// 	$id=$_POST['id_area'];	
	// 	$result = $this->Areas->Eliminar_areas($id);
	// 	echo json_encode($result);
		
	// }
}	

?>