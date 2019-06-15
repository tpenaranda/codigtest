<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class areas extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function Listado_areas()
	{

		$this->db->where('estado', 'AC')->order_by('id_area', 'desc');

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

	function Guardar_areas($data)
	{
		return $this->db->insert("area", $data);
	}

	function Modificar_areas($data)
	{
		return $this->db->update('area', $data, ['id_area' => $data['id_area']]);
	}

	function Eliminar_areas($data)
	{
		$this->db->set('estado', 'AN')->where('id_area', $data);

		return $this->db->update('area');
    }
}
