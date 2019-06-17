<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class area extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('Areas');
	}

	public function index()
	{
		$this->load->view('header');
		$this->load->view('area/view_');
	}

    public function Listado_areas()
    {
        $output = json_encode([
            'data' => $this->Areas->Listado_areas(), // array_slice($this->Areas->Listado_areas(), $this->input->get('iDisplayStart'), $this->input->get('iDisplayLength')),
        ]);

        return $this->output->set_content_type('application/json')->set_status_header(200)->set_output($output);
    }

	public function Obtener_area()
	{
		$output = json_encode($this->Areas->Obtener_areas($_POST['id_area']));

		return $this->output->set_content_type('application/json')->set_status_header(200)->set_output($output);
	}

	public function Guardar_area()
	{
	    $result = $this->Areas->Guardar_areas([
			'descripcion' => $this->input->post('descripcion'),
			'estado' => "AC",
		]);

		$output = json_encode(['success' => $result]);

		return $this->output->set_content_type('application/json')->set_status_header(201)->set_output($output);
  	}

   	public function Modificar_area()
   	{
     	$result = $this->Areas->Modificar_areas([
     		'id_area' => $this->input->post('id_area'),
     		'descripcion' => $this->input->post('descripcion')
     	]);

		$output = json_encode(['success' => $result]);

     	return $this->output->set_content_type('application/json')->set_status_header(201)->set_output($output);
   }

	public function Eliminar_area()
	{
		$output = json_encode(['success' => $this->Areas->Eliminar_areas($_POST['id_area'])]);

		return $this->output->set_content_type('application/json')->set_status_header(200)->set_output($output);
	}
}
