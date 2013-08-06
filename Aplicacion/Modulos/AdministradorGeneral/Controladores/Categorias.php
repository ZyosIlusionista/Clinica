<?php

	class Categorias extends Controlador {
		

		function __Construct() {
			parent::__Construct();
		}
		
		/**
		 * Metodo Publico
		 * Index()
		 * 
		 * Muestra el Llistado de Categorias
		 */
		public function Index() {

			header("Location: ".NeuralRutasApp::RutaURL('Categorias/ListarCategorias'));
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
		public function AgregarVisualizar() {
				
				$Validacion = new NeuralJQueryValidacionFormulario;
				$Validacion->Requerido('Descripcion', 'Ingrese La Descripcion');
				$Script[] = $Validacion->MostrarValidacion('Form');
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
			    $Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript(array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Scripts', NeuralScriptAdministrador::OrganizarScript(false, $Script));
				echo $Plantilla->MostrarPlantilla('Categorias/NuevaCategorias.html', 'CLINICA');	
		}
		
		/**
		 * Metodo Publico
		 * GuardarNuevoCategorias()
		 * 
		 * Genera el proceso General de Guardar datos de la categorias
		 */
		public function GuardarNuevoCategorias() {

			if(isset($_POST) == true AND isset($_POST['Validacion']) == true) {
				if(NeuralEncriptacion::DesencriptarDatos($_POST['Validacion'], 'CLINICA') == date("Y-m-d")) {
					$DatosPost = AyudasPost::FormatoEspacio(AyudasPost::LimpiarInyeccionSQL($_POST));
					$Existe = $this->Modelo->BuscaCategoriaExiste($DatosPost['Descripcion']);
					if ($Existe['Cantidad'] >= 1) {
							header("Location: ".NeuralRutasApp::RutaURL('Categorias/AgregarVisualizar/'.AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos('USUARIOEXISTE', 'CLINICA'))));
							exit();
						}
						else {
						
					$this->Modelo->GuardarNuevosDatosGenerales(AyudasArray::Eliminar($DatosPost, array('Validacion')));;
					unset($this, $DatosPost);
					header("Location: ".NeuralRutasApp::RutaURL('Categorias'));
					exit();
						}
				}
			}
				else {
					header("Location: ".NeuralRutasApp::RutaURL('Categorias'));
					exit();
				}
		}

		/**
		 * Metodo Publico
		 * ListarCategorias()
		 * 
		 * Mostrar el listado de Categorias
		 */
		public function ListarCategorias(){

			$Consulta = $this->Modelo->ListadoCategorias();
			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Listado', $Consulta);
			$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
				return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
			});
			echo $Plantilla->MostrarPlantilla('Categorias/Listado.html', 'CLINICA');
		}
		
		/**
		 * Metodo Publico
		 * MostrarCategorias($Id = false)
		 * 
		 * Genera la visualizacion de los datos del usuario
		 * @param $Id: id de la categoria a visualizar
		 */
		public function MostrarCategorias($Id = false){
			
			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {
				
				$Consulta = $this->Modelo->BuscarCategorias(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				echo $Plantilla->MostrarPlantilla('Categorias/Visualizar.html', 'CLINICA');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Categorias/ListarCategorias'));
				exit();
			}
		}
			
		/**
		 * Metodo Publico
		 * ModificarCategorias($Id = false)
		 * 
		 * Genera la visualizacion de los datos de la categoria
		 * @param $Id: id de la categoria
		 */	
		public function ModificarCategorias($Id = false){

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {

				$Validacion = new NeuralJQueryValidacionFormulario;
				$Validacion->Requerido('Descripcion', 'Ingrese La Categoria ');
				$Script[] = $Validacion->MostrarValidacion('Form');
				$Consulta = $this->Modelo->BuscarCategorias(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function($Parametro){
						return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
					});
				$Plantilla->ParametrosEtiquetas('Scripts', NeuralScriptAdministrador::OrganizarScript(false, $Script));
				$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
				echo $Plantilla->MostrarPlantilla('Categorias/Modificar.html', 'CLINICA');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Categorias/ListarCategorias'));
				exit();
			}
		}	
		
		/**
		 * Metodo Publico
		 * GuardarModificacionCategorias()
		 * 
		 * Guarda las modificaciones de categorias
		 */	
		public function GuardarModificacionCategorias(){
			if(isset($_POST) == true AND isset($_POST['Validacion']) == true) {
				if(is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['Id']), 'CLINICA')) == true AND (NeuralEncriptacion::DesencriptarDatos($_POST['Validacion'], 'CLINICA') == date("Y-m-d")) == true) {
					unset($_POST['Validacion']);
					$Condicion = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['Id']), 'CLINICA');
					$Matriz = array('Id','Status');
					unset($_POST['Id']);
					$this->Modelo->ActualizarRegistro($_POST, array('Id' => $Condicion), $Matriz);
					header("Location: ".NeuralRutasApp::RutaURL('Categorias/ListarCategorias'));
					exit();
				}
				else {
					header("Location: ".NeuralRutasApp::RutaURL('Categorias/ListarCategorias'));
					exit();
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Categorias/ListarCategorias'));
				exit();
			}
		}
		
		/**
		 * Metodo Publico
		 * EliminarCategorias($Id = false)
		 * 
		 * Genera la visualizacion de los datos del usuario
		 * @param $Id: id de la categoria
		 */	
		public function EliminarCategorias($Id = false){

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {

				$Consulta = $this->Modelo->BuscarCategorias(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
				return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
				});
				echo $Plantilla->MostrarPlantilla('Categorias/Eliminar.html', 'CLINICA');
				unset($Consulta, $Plantilla);
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Categorias/ListarCategorias'));
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
				$Matriz = array('Id', 'Descripcion');
				$this->Modelo->ActualizarEstado(array( 'Status' => $Update), array('Id' => $Condicion), $Matriz);
				header("Location: ".NeuralRutasApp::RutaURL('Categorias/ListarCategorias'));
				exit();
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Categorias/ListarCategorias'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * ExportacionExcel()
		 *
		 * Exportacion de los datos a excel
		 */
		Public function ExportacionExcel() {

			$this->Modelo->Excel();
		}

	}