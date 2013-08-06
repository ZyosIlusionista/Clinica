<?php

	class Sucursales extends Controlador{
		
		function __Construct() {
		parent::__Construct();
		}
		
		/**
		 * Metodo Publico
		 * Index()
		 * 
		 * Muestra el Llistado de Sucursales
		 */
		public function Index() {

			header("Location: ".NeuralRutasApp::RutaURL('Sucursales/ListarSucursales'));
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
		public function AgregarVisualizar(){
				
				$Validacion = new NeuralJQueryValidacionFormulario;
				$Validacion->Requerido('Nombre', 'Ingrese El Nombre ');
				$Validacion->Requerido('Estado', 'Seleccione el Estado');
				$Validacion->EMail('Correo', 'Correo no valido');
				$Validacion->Numero('Telefono_Base', 'Solo Digitos');
				$Validacion->Numero('Telefono_Alternativo', 'Solo Digitos');
				$Script[] = $Validacion->MostrarValidacion('Form');

				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
			    $Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript(array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			
				$Plantilla->ParametrosEtiquetas('Scripts', NeuralScriptAdministrador::OrganizarScript(false, $Script));
				echo $Plantilla->MostrarPlantilla('Sucursales/NuevaSucursales.html', 'CLINICA');	
		
		}
		
		
		/**
		 * Metodo Publico
		 * GuardarNuevoSucursales()
		 * 
		 * Genera el proceso General de Guardar datos del usuario
		 */
		public function GuardarNuevoSucursales(){

			if(isset($_POST) == true AND isset($_POST['Validacion']) == true) {
				if(NeuralEncriptacion::DesencriptarDatos($_POST['Validacion'], 'CLINICA') == date("Y-m-d")) {
					$DatosPost = AyudasPost::FormatoEspacio(AyudasPost::LimpiarInyeccionSQL($_POST));
					$this->Modelo->GuardarNuevosDatosGenerales(AyudasArray::Eliminar($DatosPost, array('Validacion')));;
					unset($this, $DatosPost);
					header("Location: ".NeuralRutasApp::RutaURL('Sucursales'));
					exit();
				}
			}
				else {
					header("Location: ".NeuralRutasApp::RutaURL('Sucursales'));
					exit();
				}
		}
		/**
		 * Metodo Publico
		 * ListarSucursales()
		 * 
		 * Mostrar el listado de Sucursales
		 */
		public function ListarSucursales(){

			$Consulta = $this->Modelo->ListadoSucursales();
			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Listado', $Consulta);
			$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
				return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
			});
			echo $Plantilla->MostrarPlantilla('Sucursales/Listado.html', 'CLINICA');
		}
		
		
		/**
		 * Metodo Publico
		 * MostrarSucursales($Id = false)
		 * 
		 * Genera la visualizacion de los datos del usuario
		 * @param $Id: id del usuario a visualizar
		 */
		public function MostrarSucursales($Id = false){
			
			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {
				
				$Consulta = $this->Modelo->BuscarSucursales(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				echo $Plantilla->MostrarPlantilla('Sucursales/Visualizar.html', 'CLINICA');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Sucursales/ListarSucursales'));
				exit();
			}
		}
		
		
		/**
		 * Metodo Publico
		 * ModificarSucursales($Id = false)
		 * 
		 * Genera la visualizacion de los datos del usuario
		 * @param $Id: id del usuario
		 */	
		public function ModificarSucursales($Id = false){

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {

				$Validacion = new NeuralJQueryValidacionFormulario;
				$Validacion->Requerido('Nombre', 'Ingrese El Nombre ');
				$Validacion->Requerido('Estado', 'Seleccione el Estado');
				$Validacion->EMail('Correo', 'Correo no valido');
				$Validacion->Numero('Telefono_Base', 'Solo Digitos');
				$Validacion->Numero('Telefono_Alternativo', 'Solo Digitos');
				$Script[] = $Validacion->MostrarValidacion('Form');

				$Consulta = $this->Modelo->BuscarSucursales(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function($Parametro){
						return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
					});
				$Plantilla->ParametrosEtiquetas('Scripts', NeuralScriptAdministrador::OrganizarScript(false, $Script));
				$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
				echo $Plantilla->MostrarPlantilla('Sucursales/Modificar.html', 'CLINICA');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Sucursales/ListarSucursales'));
				exit();
			}
		}	
		
		/**
		 * Metodo Publico
		 * GuardarModificacionSucursales()
		 * 
		 * Guarda las modificaciones de los datos del registro
		 */	
		public function GuardarModificacionSucursales(){

			if(isset($_POST) == true AND isset($_POST['Validacion']) == true) {
				if(is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['Id']), 'CLINICA')) == true AND (NeuralEncriptacion::DesencriptarDatos($_POST['Validacion'], 'CLINICA') == date("Y-m-d")) == true) {
					unset($_POST['Validacion']);
					$Condicion = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['Id']), 'CLINICA');
					$Matriz = array('Id','Status');
					unset($_POST['Id']);
					$this->Modelo->ActualizarRegistro($_POST, array('Id' => $Condicion), $Matriz);
					header("Location: ".NeuralRutasApp::RutaURL('Sucursales/ListarSucursales'));
					exit();
				}
				else {
					header("Location: ".NeuralRutasApp::RutaURL('Sucursales/Sucursales'));
					exit();
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Sucursales/ListarSucursales'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * EliminarSucursales($Id = false)
		 * 
		 * Genera la visualizacion de los datos del usuario
		 * @param $Id: id del usuario
		 */	
		public function EliminarSucursales($Id = false){

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {

				$Consulta = $this->Modelo->BuscarSucursales(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
				return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
				});
				echo $Plantilla->MostrarPlantilla('Sucursales/Eliminar.html', 'CLINICA');
				unset($Consulta, $Plantilla);
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Sucursales/ListarSucursales'));
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
			
			Ayudas::print_r($_POST);
			
			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true AND $Estado == true AND AyudasArray::VerificaEstado(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Estado), 'CLINICA')) == true ) {
				$Update = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Estado), 'CLINICA');
				$Condicion = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA');
				$Matriz = array('Id', 'Nombre', 'Estado', 'Poblacion', 'Responsable', 'Correo',  'Telefono_Base', 'Telefono_Alternativo', 'Direccion');
				$this->Modelo->ActualizarEstado(array( 'Status' => $Update), array('Id' => $Condicion), $Matriz);
				header("Location: ".NeuralRutasApp::RutaURL('Sucursales/ListarSucursales'));
				exit();
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Sucursales/ListarSucursales'));
				exit();
			}
		}

	}
