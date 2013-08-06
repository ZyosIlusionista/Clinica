<?php

	class AdministradorSucursales extends Controlador {
		
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

			header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales/ListarAdministradoresSucursales'));
			exit();
		}

		/**
		 * Metodo Publico
		 * AgregarVisualizar($Error = false)
		 * 
		 * Genera el formulario para crear usuarios en el sistema
		 * @param $Error: Muestra error en los pasos del sistema
		 */
		public function AgregarVisualizar($Error = false) {
	
			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript(array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Error', ($Error == true) ? (NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Error)) == 'USUARIOEXISTE') ? 'USUARIOEXISTE' : '' : '');
			echo $Plantilla->MostrarPlantilla('AdministradorSucursales/NuevoAdministradorSucursales.html', 'CLINICA');
			unset($Plantilla);
		}

		/**
		 * Metodo Publico
		 * ProcesoSucursales()
		 * 
		 * Metodo para procedimientos de ajax para ubicar la Sucursal segun el estado
		 */
		public function ProcesoSucursales() {
			
			if(isset($_POST) == true AND $_SERVER['HTTP_REFERER'] != $_SERVER['HTTP_HOST'] ) {
				$DatosPost = AyudasPost::FormatoEspacio(AyudasPost::FormatoMayus(AyudasPost::LimpiarInyeccionSQL($_POST)));
				$Consulta = $this->Modelo->ListarSucursales($DatosPost['Estado']);
				if($Consulta['Cantidad']>=1) {
					$Lista[] = '<option value="">Escoja Una Opción</option>';
					for ($i=0; $i<$Consulta['Cantidad']; $i++) {
						$Lista[] = '<option value="'.$Consulta[$i]['Id'].'">'.$Consulta[$i]['Nombre'].'</option>';
					}
					echo implode("\n", $Lista);
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * GuardarNuevoAdministradorSucursales()
		 * 
		 * Genera el proceso General de Guardar datos del usuario
		 */
		public function GuardarNuevoAdministradorSucursales() {

			if(isset($_POST) == true AND isset($_POST['Validacion']) == true) {
				if(NeuralEncriptacion::DesencriptarDatos($_POST['Validacion'], 'CLINICA') == date("Y-m-d")) {

					$DatosPost = AyudasPost::ConvertirTextoUcwordsOmitido(AyudasPost::FormatoEspacio(AyudasPost::LimpiarInyeccionSQL($_POST)), array('Validacion', 'Sucursal', 'Password', 'RePassword', 'Perfil', 'Correo', 'Telefono_Base', 'Telefono_Movil') );
					$DatosPost['Password'] = sha1($DatosPost['Password']);
					unset($DatosPost['RePassword'], $DatosPost['Validacion']);
					$Existe = $this->Modelo->BuscaUsuarioExiste($DatosPost['Usuario']);
					
					if ( !( $Existe['Cantidad'] >= 1 ) ){

						$this->Modelo->GuardarNuevosDatosAutentificacion(AyudasArray::Eliminar($DatosPost, array('Estado', 'Sucursal', 'Nombre_Primero', 'Nombre_Segundo', 'Apellido_Primero', 'Apellido_Segundo', 'Correo', 'Telefono_Base', 'Telefono_Movil')));
						$this->Modelo->GuardarNuevosDatosGenerales(AyudasArray::Eliminar($DatosPost, array('Estado', 'Perfil', 'Password')));
						header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales'));
						exit();
					} else {
						
						header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales/AgregarVisualizar/'.AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos('USUARIOEXISTE', 'CLINICA'))));
						exit();
					}
				}
				else {

					header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales/AgregarVisualizar'));
					exit();
				}
				
			}
			else {

				header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales/AgregarVisualizar'));
				exit();
			}	

		}

		/**
		 * Metodo Publico
		 * ListarAdministradoresSucursales()
		 * 
		 * Mostrar el listado de administrador de sucursales
		 */
		public function ListarAdministradoresSucursales(){

			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			$Consulta = $this->Modelo->ListadoAdministradoresSucursales();
			$Plantilla->ParametrosEtiquetas('Listado', $Consulta);
			$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
				return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
			});
			echo $Plantilla->MostrarPlantilla('AdministradorSucursales/Listado.html', 'CLINICA');
		}

		/**
		 * Metodo Publico
		 * MostrarAdministradorSucursales($Id = false)
		 * 
		 * Genera la visualizacion de los datos del usuario
		 * @param $Id: id del usuario a visualizar
		 */
		public function MostrarAdministradorSucursales($Id = false) {

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {
				
				$Consulta = $this->Modelo->BuscarAdministradorSucursales(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				echo $Plantilla->MostrarPlantilla('AdministradorSucursales/Visualizar.html', 'CLINICA');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales/ListarAdministradoresSucursales'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * ModificarAdministradorSucursal($Id = false)
		 * 
		 * Genera la visualizacion de los datos del farmaco
		 * @param $Id: id del administrador
		 */	
		public function ModificarAdministradorSucursal($Id = false) {

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {

				$Validacion = new NeuralJQueryValidacionFormulario;
				$Validacion->Requerido('Nombre_Primero', 'Ingrese El Nombre Primero');
				$Script[] = $Validacion->MostrarValidacion('Form');				
				$Consulta = $this->Modelo->BuscarAdministradorSucursales(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function($Parametro){
						return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
					});
				$Plantilla->ParametrosEtiquetas('Scripts', NeuralScriptAdministrador::OrganizarScript(false, $Script));
				$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
				echo $Plantilla->MostrarPlantilla('AdministradorSucursales/Modificar.html', 'CLINICA');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales'));
				exit();
			}
		}	

		/**
		 * Metodo Publico
		 * GuardarModificacionAdministradorSucursal()
		 * 
		 * Guarda las modificaciones de los datos
		 */	
		public function GuardarModificacionAdministradorSucursal() {
			if(isset($_POST) == true AND isset($_POST['Validacion']) == true) {
				if(is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['Id']), 'CLINICA')) == true AND (NeuralEncriptacion::DesencriptarDatos($_POST['Validacion'], 'CLINICA') == date("Y-m-d")) == true) {
					unset($_POST['Validacion']);
					$Condicion = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['Id']), 'CLINICA');
					$Matriz = array('Id', 'Usuario', 'Ciudad', 'Direccion_Contacto', 'Sucursales', 'Tipo_Perfil', 'Cedula_Profesional', 'Especialidad', 'Titulo', 'Actualizacion', 'Aseguradora', 'Sucursal');
					unset($_POST['Id']);
					$this->Modelo->ActualizarRegistro(AyudasPost::ConvertirTextoUcwordsOmitido($_POST, array('Correo', 'Telefono_Base', 'Telefono_Movil')), array('Id' => $Condicion), $Matriz);
					header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales'));
					unset($Condicion, $Matriz, $this);
					exit();
				}
				else {
					header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales'));
					exit();
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * EliminarAdministradorSucursal($Id = false)
		 * 
		 * Elimina los datos de usuario sucursal
		 * @param $Id: id del usuario de la sucursal
		 */	
		public function EliminarAdministradorSucursal($Id = false) {

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {

				$Consulta = $this->Modelo->BuscarAdministradorSucursales(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
				return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
				});
				echo $Plantilla->MostrarPlantilla('AdministradorSucursales/Eliminar.html.twig', 'CLINICA');
				unset($Consulta, $Plantilla);
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales'));
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
		public function ActualizaEstado($Id = false, $Estado = false) {
		
			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true AND $Estado == true AND AyudasArray::VerificaEstado(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Estado), 'CLINICA')) == true ) {
				$Update = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Estado), 'CLINICA');
				$Condicion = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA');
				$Matriz = array('Id', 'Usuario', 'Password', 'Perfil');
				$this->Modelo->ActualizarEstado( array( 'Estado' => $Update), array('Id' => $Condicion), $Matriz);
				header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales'));
				exit();
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('AdministradorSucursales'));
				exit();
			}
		}

	}		
	