<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class areas extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function Listado_areas()
	{
		
		$this->db->where('estado', 'AC');
		
		$query = $this->db->get('area');

		if ($query->num_rows()!=0)
		{
			return $query->result_array();	
		}
	}

	function Obtener_areas($id){

    $this->db->where('id_area', $id);
    $query=$this->db->get('area');
   
    if ($query->num_rows()!=0)
        {   
            return $query->result_array();  
        }
	}

	function Guardar_areas($data){

		// $userdata = $this->session->userdata('user_data');
		// $empId = $userdata[0]['id_empresa']; 
		// $data['id_empresa'] = $empId;

		$query = $this->db->insert("area",$data);
		return $query;

	}

	// function Modificar_areas($data){

	// 	$userdata = $this->session->userdata('user_data');
	// 	$empId = $userdata[0]['id_empresa']; 
	// 	$data['id_empresa'] = $empId;

	// 	$query =$this->db->update('area', $data, array('id_area' => $data['id_area']));
	// 	return $query;
	// }

	// function Eliminar_areas($data){

	// 	$this->db->set('estado', 'AN');
	// 	$this->db->where('id_area', $data);
	// 	$query=$this->db->update('area');
	// 	return $query;
    	
    // }
}	

?>