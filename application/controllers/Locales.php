<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require( APPPATH.'/libraries/REST_Controller.php');

/*
* Es importante requerir el REST_Controller
* Es importante extender del REST_Controller
*/

class Locales extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model('Local_model');
    }

    // ==================================
    // Paginar clientes mediante helper
    // METHOD: GET
    // ==================================
    public function paginar_get(){

        $this->load->helper('paginacion');

        $pagina     = $cliente_id = $this->uri->segment(3); // parametro #3
        $por_pagina = $cliente_id = $this->uri->segment(4); // parametro #4

        $campos = array('id','nombre','telefono1'); // campos de la tabla

        $respuesta = paginar_todo( 'clientes', $pagina, $por_pagina, $campos ); // helper
        $this->response( $respuesta );  // imprime el resultado de lo que se obtuvo
    }

    // ==================================
    // Registrar un cliente nuevo en la db
    // METHOD: PUT
    // ==================================
    public function local_put(){
		$data = $this->put();   // guarda los campos posteados
		
		$respuesta = $this->Local_model->get_disponibilidad($data);
		
		if ($respuesta) {
			$mensaje = array(
				'error' => FALSE,
				'mensaje' => 'Este local ya esta rentado en la fecha seleccionada'				
			);
			$this->response($mensaje);
		}
		else{
			$insertado = $this->Local_model->put_local($data);
			if ($insertado) {
				$mensaje = array(
					'error' => TRUE,
					'mensaje' => 'Renta registrada correctamente'	,		
				);
				$this->response($mensaje);
			}	
		}
	}
	// public function disponibilidad_get(){
	// 	// $data = $this->put(); 
	// 	$id = $this->uri->segment(3);
	// 	$fecha = $this->uri->segment(4);
		
	// 	$data = array(
	// 		'id_local' => $id,
	// 		'fecha_renta' => $fecha
	// 	);			
	// 	// $this->response($data);
		
	// 	$respuesta = $this->Local_model->get_disponibilidad($data);
	// 	$this->response($respuesta);
	// }
	public function disponibilidad_get()
	{
		$fecha = $this->uri->segment(3);

		$respuesta = $this->Local_model->get_locales_disponibes($fecha);
		$this->response($respuesta->result());

    }

    public function local_get(){

		$locales = $this->Local_model->get_locales();
		$respuesta = array(
				'error' => FALSE,
				'mensaje' => 'Locales encontrados',
				'locales' => $locales
			);
		$this->response( $respuesta );		
    }
}
