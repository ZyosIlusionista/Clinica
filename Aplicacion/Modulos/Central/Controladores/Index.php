<?php

	class Index extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AyudasSession::ValSessionGlobal();
		}
		
		public function Index() {
			
			Ayudas::print_r($_SESSION);
			Ayudas::print_r(AyudasSession::MostrarDatosSession());
			
			echo '<a href="'.NeuralRutasApp::RutaURLBase('Administrador').'">Administrador Root</a> | ';
			echo '<a href="'.NeuralRutasApp::RutaURLBase('AdministradorGeneral').'">Administrador General</a> | ';
			echo '<a href="'.NeuralRutasApp::RutaURLBase('Clinica/LogOut').'">LogOut</a>';
		}
	}