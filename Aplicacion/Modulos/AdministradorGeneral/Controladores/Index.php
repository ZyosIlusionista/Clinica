<?php

	class Index extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AyudasSession::ValSessionGlobal();
		}
		
		public function Index() {
			
			echo 'Este es el Index de la Aplicaci�n';
			header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales'));
		}
	}