<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Local_model extends CI_Model {

    // Campos de la tabla clientes
    public $id;
    public $nombre;
    public $correo;
    public $zip;
    public $activo;
    public $telefono1;
    public $telefono2;
    public $pais;
    public $direccion;

    public function get_locales(){

		$query = $this->db->get('locales');
		$locales = $query->result();
        return $locales;
	}
	public function get_disponibilidad($data)
	{
		$this->db->where('id_local',$data['id_local']);
		if ($data['fecha_renta']) {			
			$this->db->where('fecha_renta',$data['fecha_renta']);
		}
		$query = $this->db->get('renta_locales');
		$disponibilidad = $query->result();
        return $disponibilidad;
	}
	
// SELECT * FROM locales 
// WHERE locales.id NOT IN(select id_local FROM renta_locales WHERE fecha_renta = '2019-11-20'); 
    public function get_locales_disponibes($data)
	{
		$query = " SELECT * FROM locales WHERE locales.id NOT IN(select id_local FROM renta_locales WHERE fecha_renta = '".$data."')";
		$disponibilidad = $this->db->query($query);
		return $disponibilidad;
	}
	public function put_local($data)
	{
		$this->db->insert('renta_locales',$data);
		$respuesta = $this->db->insert_id();
		return $respuesta;
    }

    public function set_datos( $data_cruda ){

        foreach( $data_cruda as $nombre_campo => $valor_campo ){
            if( property_exists( 'Cliente_model', $nombre_campo  ) ){
                $this->$nombre_campo = $valor_campo;
            }
        }

        if( $this->activo == NULL){
            $this->activo = 1;
        }

        $this->nombre = strtoupper( $this->nombre );
        return $this;
    }

}
