<?php

	class Index extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AyudasSession::ValSessionGlobal();
		}
		
		public function Index() {
			
			echo 'Este es el Index de la Aplicación';
			header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales'));
		}
	}