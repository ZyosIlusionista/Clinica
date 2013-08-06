<?php

	class Index extends Controlador {
		
		function __Construct() {
			parent::__Construct();
		}
		
		public function Index() {
			
			header("Location: ".NeuralRutasApp::RutaURL('AdministradorGeneral'));
			exit();
		}
	}