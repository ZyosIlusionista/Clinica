<?php

	class SinPermisos extends Controlador {
		
		function __Construct() {
			parent::__Construct();
		}
		
		/**
		 * Metodo Publico 
		 * Index
		 * 
		 * Mustra la Informacion que se encuentra sin Permisos
		 */
		public function Index() {
			
			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			echo $Plantilla->MostrarPlantilla('Errores/SinPermisos/SinPermiso.html', 'CLINICA');
		}
		
	}