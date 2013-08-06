<?php

	class Index extends Controlador {
		
		function __Construct() {
			parent::__Construct();
		}
		
		/**
		 * Metodo Publico
		 * Index()
		 * 
		 * Muestra el formulario de Inicio de Sesion
		 */
		public function Index() {
			
			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
			echo $Plantilla->MostrarPlantilla('Login/Index.html', 'CLINICA');
		}
		
		/**
		 * Metodo Publico
		 * Autenticacion();
		 * 
		 * Genera el Proceso de Autenticacion de la Aplicacion
		 */
		public function Autenticacion() {
			
			if(isset($_POST) == true AND isset($_POST['submit']) == true) {
				if(NeuralEncriptacion::DesencriptarDatos($_POST['submit'], 'CLINICA') == date("Y-m-d")) {
					if(AyudasPost::DatosVacios($_POST) == false) {
						$DatosPost = AyudasPost::FormatoEspacio(AyudasPost::LimpiarInyeccionSQL($_POST));
						$Consulta = $this->Modelo->ConsultarUsuario($DatosPost['username'], sha1($DatosPost['password']));
						if($Consulta['Cantidad'] == 1) {
							$ConsultaPermisos = $this->Modelo->ConsultarPermisos($Consulta[0]['Perfil']);
							if($ConsultaPermisos['Cantidad'] == 1) {
								AyudasSession::RegistrarSession($Consulta[0], $ConsultaPermisos[0]);
								header("Location: ".NeuralRutasApp::RutaURLBase('Central'));
								exit();
							}
							else {
								header("Location: ".NeuralRutasApp::RutaURL('LogOut'));
								exit();
							}
						}
						else {
							// -- Generar Vista Usuario y/o contraseña Incorrecto
							header("Location: ".NeuralRutasApp::RutaURLBase('Error/SinAutorizacion'));
							exit();
						}
					}
					else {
						//-- Generar Vista de Datos vacios
						header("Location: ".NeuralRutasApp::RutaURLBase('Error/SinAutorizacion'));
						exit();
					}
				}
				else {
					header("Location: ".NeuralRutasApp::RutaURL('Index'));
					exit();
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Index'));
				exit();
			}
		}
	}