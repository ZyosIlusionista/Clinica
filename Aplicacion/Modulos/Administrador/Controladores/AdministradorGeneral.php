<?php

	class AdministradorGeneral extends Controlador {
		

		function __Construct() {
			parent::__Construct();
			AyudasSession::ValSessionGlobal();
		}
		
		/**
		 * Metodo Publico
		 * Index()
		 * 
		 * Muestra el Llistado de Administradores Generales
		 */
		public function Index() {

			header("Location: ".NeuralRutasApp::RutaURL('AdministradorGeneral/ListarAdministradoresGenerales'));
			exit();
		}

		/**
		 * Metodo Publico
		 * AgregarVisualizar($Error = false)
		 * 
		 * Genera el formulario para crear usuarios en el sistema
		 * @param $Error: Muestra error en los pasos del sistema
		 * 
		 */
		public function AgregarVisualizar($Error = false){

			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript(array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Error', ($Error == true) ? (NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Error)) == 'USUARIOEXISTE') ? 'USUARIOEXISTE' : '' : '');
			echo $Plantilla->MostrarPlantilla('AdministradorGeneral/Alta.html', 'CLINICA');
		}

		/**
		 * Metodo Publico
		 * GuardarNuevoAdministradorGeneral()
		 * 
		 * Genera el proceso General de Guardar datos del usuario
		 */
		public function GuardarNuevoAdministradorGeneral(){

			if(isset($_POST) == true AND isset($_POST['Validacion']) == true) {
				if(NeuralEncriptacion::DesencriptarDatos($_POST['Validacion'], 'CLINICA') == date("Y-m-d")) {
					$DatosPost = AyudasPost::FormatoEspacio(AyudasPost::LimpiarInyeccionSQL($_POST));
					if($DatosPost['Password'] == $DatosPost['RePassword']) {
						$DatosPost['Password'] = sha1($DatosPost['Password']);
						unset($DatosPost['RePassword'], $DatosPost['Validacion']);
						$Existe = $this->Modelo->BuscaUsuarioExiste($DatosPost['Usuario']);
						if ($Existe['Cantidad'] >= 1) {
							header("Location: ".NeuralRutasApp::RutaURL('AdministradorGeneral/AgregarVisualizar/'.AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos('USUARIOEXISTE', 'CLINICA'))));
							exit();
						}
						else {
							$Matriz = array('Nombre_Primero', 'Nombre_Segundo', 'Apellido_Primero', 'Apellido_Segundo', 'Correo', 'Telefono_Base', 'Telefono_Movil');
							$this->Modelo->GuardarNuevosDatosAutentificacion(AyudasArray::Eliminar($DatosPost, $Matriz));
							$this->Modelo->GuardarNuevosDatosGenerales(AyudasArray::Eliminar($DatosPost, array('Usuario', 'Password')));
							unset($this, $DatosPost, $Existe, $Matriz, $_POST);
							header("Location: ".NeuralRutasApp::RutaURL('AdministradorGeneral'));
							exit();
						}
					}
					else {
						header("Location: ".NeuralRutasApp::RutaURL('AdministradorGeneral/AgregarVisualizar/'.AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos('PASSWORDSNOCOINCIDEN', 'CLINICA'))));
						exit();
					}
				}
				else {
					header("Location: ".NeuralRutasApp::RutaURL('AdministradorGeneral'));
					exit();
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('AdministradorGeneral'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * ListarAdministradoresGenerales()
		 * 
		 * Mostrar el listado de administrador generales
		 */
		public function ListarAdministradoresGenerales(){

			$Consulta = $this->Modelo->ListadoAdministradoresGenerales();
			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Listado', $Consulta);
			$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
				return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
			});
			echo $Plantilla->MostrarPlantilla('AdministradorGeneral/Listado.html', 'CLINICA');
		}

		/**
		 * Metodo Publico
		 * MostrarAdministradorGeneral($Id = false)
		 * 
		 * Genera la visualizacion de los datos del usuario
		 * @param $Id: id del usuario a visualizar
		 */
		public function MostrarAdministradorGeneral($Id = false){

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {

				$Consulta = $this->Modelo->BuscarAdministradorGeneral(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				echo $Plantilla->MostrarPlantilla('AdministradorGeneral/Visualizar.html', 'CLINICA');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('AdministradorGeneral/ListarAdministradoresGenerales'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * EliminarAdministradorGeneral($Id = false)
		 * 
		 * Genera la visualizacion de los datos del usuario
		 * @param $Id: id del usuario
		 */	
		public function EliminarAdministradorGeneral($Id = false){

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {

				$Consulta = $this->Modelo->BuscarAdministradorGeneral(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
				return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
				});
				echo $Plantilla->MostrarPlantilla('AdministradorGeneral/Eliminar.html', 'CLINICA');
				unset($Consulta, $Plantilla);
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('AdministradorGeneral/ListarAdministradoresGenerales'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * ModificarAdministradorGeneral($Id = false)
		 * 
		 * Genera la visualizacion de los datos del usuario
		 * @param $Id: id del usuario
		 */	
		public function ModificarAdministradorGeneral($Id = false){

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {

				$Validacion = new NeuralJQueryValidacionFormulario;
				$Validacion->Requerido('Nombre_Primero', 'Ingrese El Nombre Primero');
				$Script[] = $Validacion->MostrarValidacion('Form');

				$Consulta = $this->Modelo->BuscarAdministradorGeneral(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function($Parametro){
						return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
					});
				$Plantilla->ParametrosEtiquetas('Scripts', NeuralScriptAdministrador::OrganizarScript(false, $Script));
				$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
				echo $Plantilla->MostrarPlantilla('AdministradorGeneral/Modificar.html', 'CLINICA');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('AdministradorGeneral/ListarAdministradoresGenerales'));
				exit();
			}
		}	
		
		/**
		 * Metodo Publico
		 * GuardarModificacion()
		 * 
		 * Guarda las modificaciones de los datos del registro
		 */	
		public function GuardarModificacion(){
			if(isset($_POST) == true AND isset($_POST['Validacion']) == true) {
				if(is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['Id']), 'CLINICA')) == true AND (NeuralEncriptacion::DesencriptarDatos($_POST['Validacion'], 'CLINICA') == date("Y-m-d")) == true) {
					unset($_POST['Validacion']);
					$Condicion = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['Id']), 'CLINICA');
					$Matriz = array('Id', 'Usuario', 'Ciudad', 'Direccion_Contacto', 'Sucursales', 'Tipo_Perfil', 'Cedula_Profesional', 'Especialidad', 'Titulo', 'Actualizacion');
					unset($_POST['Id']);
					$this->Modelo->ActualizarRegistro($_POST, array('Id' => $Condicion), $Matriz);
					header("Location: ".NeuralRutasApp::RutaURL('AdministradorGeneral/ListarAdministradoresGenerales'));
					exit();
				}
				else {
					header("Location: ".NeuralRutasApp::RutaURL('AdministradorGeneral/ListarAdministradoresGenerales'));
					exit();
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('AdministradorGeneral/ListarAdministradoresGenerales'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * ActualizaEstado($Id = false, $Estado = false)
		 * 
		 * Genera la visualizacion de los datos del usuario
		 * @param $Id: id del usuario de la tabla tbl_sistema_usuarios
		 * @param $Estado: Status del usuario ('ACTIVO', 'INACTIVO', 'SUSPENDIDO', 'ELIMINADO')
		 */
		public function ActualizaEstado($Id = false, $Estado = false){

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true AND $Estado == true AND AyudasArray::VerificaEstado(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Estado), 'CLINICA')) == true ) {
				$Update = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Estado), 'CLINICA');
				$Condicion = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA');
				$Matriz = array('Id', 'Usuario', 'Password', 'Perfil');
				$this->Modelo->ActualizarEstado( array( 'Estado' => $Update), array('Id' => $Condicion), $Matriz);
				header("Location: ".NeuralRutasApp::RutaURL('AdministradorGeneral/ListarAdministradoresGenerales'));
				exit();
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('AdministradorGeneral/ListarAdministradoresGenerales'));
				exit();
			}
		}
		
	}