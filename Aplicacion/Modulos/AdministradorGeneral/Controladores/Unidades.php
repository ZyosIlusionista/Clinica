<?php

	class Unidades extends Controlador {
		
		function __Construct() {
			parent::__Construct();
		}
		/**
		 * Metodo Publico
		 * Index()
		 * 
		 * Muestra el Llistado de Unidades
		 */
		public function Index() {

			header("Location: ".NeuralRutasApp::RutaURL('Unidades/ListarUnidades'));
			exit();
		}

		/**
		 * Metodo Publico
		 * AgregarVisualizar()
		 * 
		 * Genera el formulario para crear unidades en el sistema
		 */
		public function AgregarVisualizar() {
				
			$Validacion = new NeuralJQueryValidacionFormulario;
			$Validacion->Requerido('Descripcion', 'Ingrese La Descripción');
			$Script[] = $Validacion->MostrarValidacion('Form');
			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript(array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Scripts', NeuralScriptAdministrador::OrganizarScript(false, $Script));
			echo $Plantilla->MostrarPlantilla('Unidades/NuevaUnidades.html', 'CLINICA');	
		}

		/**
		 * Metodo Publico
		 * GuardarNuevaUnidades()
		 * 
		 * Genera el proceso General de Guardar datos de las unidades
		 */
		public function GuardarNuevaUnidades() {

			if(isset($_POST) == true AND isset($_POST['Validacion']) == true) {
				if(NeuralEncriptacion::DesencriptarDatos($_POST['Validacion'], 'CLINICA') == date("Y-m-d")) {
					$DatosPost = AyudasPost::FormatoEspacio(AyudasPost::LimpiarInyeccionSQL($_POST));
					$Existe = $this->Modelo->BuscaUnidadesExiste($DatosPost['Descripcion']);
					if ($Existe['Cantidad'] >= 1) {
							header("Location: ".NeuralRutasApp::RutaURL('Unidades/AgregarVisualizar/'.AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos('USUARIOEXISTE', 'CLINICA'))));
							exit();
					}
					else {
						$this->Modelo->GuardarNuevosDatosGenerales(AyudasArray::Eliminar($DatosPost, array('Validacion')));;
						unset($this, $DatosPost);
						header("Location: ".NeuralRutasApp::RutaURL('Unidades'));
						exit();
					}
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Unidades'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * ListarUnidades()
		 * 
		 * Mostrar el listado de Unidades
		 */
		public function ListarUnidades() {

			$Consulta = $this->Modelo->ListadoUnidades();
			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Listado', $Consulta);
			$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
				return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
			});
			echo $Plantilla->MostrarPlantilla('Unidades/Listado.html', 'CLINICA');
		}

		/**
		 * Metodo Publico
		 * MostrarUnidades($Id = false)
		 * 
		 * Genera la visualizacion de los datos de la unidad
		 * @param $Id: id de la unidad a visualizar
		 */
		public function MostrarUnidades($Id = false) {
			
			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {
				
				$Consulta = $this->Modelo->BuscarUnidades(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				echo $Plantilla->MostrarPlantilla('Unidades/Visualizar.html', 'CLINICA');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Unidades/ListarUnidades'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * ModificarUnidades($Id = false)
		 * 
		 * Genera la visualizacion de los datos de la unidad
		 * @param $Id: id de la unidad
		 */	
		public function ModificarUnidades($Id = false) {

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {

				$Validacion = new NeuralJQueryValidacionFormulario;
				$Validacion->Requerido('Descripcion', 'Ingrese La Descripción ');
				$Script[] = $Validacion->MostrarValidacion('Form');
				$Consulta = $this->Modelo->BuscarUnidades(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function($Parametro){
						return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
					});
				$Plantilla->ParametrosEtiquetas('Scripts', NeuralScriptAdministrador::OrganizarScript(false, $Script));
				$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
				echo $Plantilla->MostrarPlantilla('Unidades/Modificar.html', 'CLINICA');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Unidades/ListarUnidades'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * GuardarModificacionUnidades()
		 * 
		 * Guarda las modificaciones de los datos de unidades
		 */	
		public function GuardarModificacionUnidades() {

			if(isset($_POST) == true AND isset($_POST['Validacion']) == true) {
				if(is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['Id']), 'CLINICA')) == true AND (NeuralEncriptacion::DesencriptarDatos($_POST['Validacion'], 'CLINICA') == date("Y-m-d")) == true) {
					unset($_POST['Validacion']);
					$Condicion = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['Id']), 'CLINICA');
					$Matriz = array('Id','Status');
					unset($_POST['Id']);
					$this->Modelo->ActualizarRegistro($_POST, array('Id' => $Condicion), $Matriz);
					header("Location: ".NeuralRutasApp::RutaURL('Unidades/ListarUnidades'));
					exit();
				}
				else {
					header("Location: ".NeuralRutasApp::RutaURL('Unidades/ListarUnidades'));
					exit();
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Unidades/ListarUnidades'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * EliminarUnidades($Id = false)
		 * 
		 * Genera la visualizacion de los datos de la unidad
		 * @param $Id: id de la unidad
		 */	
		public function EliminarUnidades($Id = false) {

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {

				$Consulta = $this->Modelo->BuscarUnidades(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
				return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
				});
				echo $Plantilla->MostrarPlantilla('Unidades/Eliminar.html', 'CLINICA');
				unset($Consulta, $Plantilla);
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Unidades/ListarUnidades'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * ActualizaEstado($Id = false, $Estado = false)
		 * 
		 * Genera la visualizacion de los datos del usuario
		 * @param $Id: id de la unidad de la tabla tbl_clinica_info_unidades
		 * @param $Estado: Status de la unidad ('ELIMINADO')
		 */
		public function ActualizaEstado($Id = false, $Estado = false) {
			
			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true AND $Estado == true AND AyudasArray::VerificaEstado(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Estado), 'CLINICA')) == true ) {
				$Update = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Estado), 'CLINICA');
				$Condicion = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA');
				$Matriz = array('Id', 'Descripcion');
				$this->Modelo->ActualizarEstado(array( 'Status' => $Update), array('Id' => $Condicion), $Matriz);
				header("Location: ".NeuralRutasApp::RutaURL('Unidades/ListarUnidades'));
				exit();
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Unidades/ListarUnidades'));
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