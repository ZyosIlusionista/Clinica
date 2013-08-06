<?php

	class CodigosOncologicos extends Controlador {
		
		function __Construct() {
			parent::__Construct();
		}
		
		/**
		 * Metodo Publico
		 * Index()
		 * 
		 * Muestra el Llistado de Codigos Oncologicos
		 */
		public function Index() {

			header("Location: ".NeuralRutasApp::RutaURL('CodigosOncologicos/ListarCodigosOncologicos'));
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
			$Validacion->Requerido('Clave_Oncologico', 'Ingrese La Clave Oncologica');
			$Validacion->Requerido('Codigo_Oncologico_1', 'Ingrese El Primer Codigo Oncologico');
			$Validacion->Requerido('Codigo_Oncologico_2', 'Ingrese El Segundo Codigo Oncologico');
			$Validacion->Requerido('Descripcion', 'Ingrese La Descripcion');
			$Script[] = $Validacion->MostrarValidacion('Form');
			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript(array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Scripts', NeuralScriptAdministrador::OrganizarScript(false, $Script));
			echo $Plantilla->MostrarPlantilla('CodigosOncologicos/NuevoCodigosOncologicos.html', 'CLINICA');
		}
		
		/**
		 * Metodo Publico
		 * GuardarNuevoCodigosOncologicos()
		 * 
		 * Genera el proceso General de Guardar datos de los codigos oncologicos
		 */
		public function GuardarNuevoCodigosOncologicos() {

			if(isset($_POST) == true AND isset($_POST['Validacion']) == true) {
				if(NeuralEncriptacion::DesencriptarDatos($_POST['Validacion'], 'CLINICA') == date("Y-m-d")) {
					$DatosPost = AyudasPost::FormatoEspacio(AyudasPost::LimpiarInyeccionSQL($_POST));
					$Checar = $this->Modelo->BuscaOncologico($DatosPost['Clave_Oncologico']);
					if ($Checar['Cantidad'] >= 1) {
							header("Location: ".NeuralRutasApp::RutaURL('CodigosOncologicos/AgregarVisualizar/'.AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos('USUARIOEXISTE', 'CLINICA'))));
							exit();
						}
						else {
					
							$Existe = $this->Modelo->BuscaCodigoExiste($DatosPost['Clave_Oncologico'], $DatosPost['Codigo_Oncologico_1'], $DatosPost['Codigo_Oncologico_2'], $DatosPost['Descripcion']);
							if ($Existe['Cantidad'] >= 1) {
								header("Location: ".NeuralRutasApp::RutaURL('CodigosOncologicos/AgregarVisualizar/'.AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos('USUARIOEXISTE', 'CLINICA'))));
								exit();
							}
							else {
								$this->Modelo->GuardarNuevosDatosGenerales(AyudasArray::Eliminar($DatosPost, array('Validacion')));;
								unset($this, $DatosPost, $Existe, $Checar);
								header("Location: ".NeuralRutasApp::RutaURL('CodigosOncologicos'));
								exit();
							}
						}
					}
				}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('CodigosOncologicos'));
				exit();
			}
		}
		
		/**
		 * Metodo Publico
		 * ListarCodigosOncologicos()
		 * 
		 * Mostrar el listado de Codigos Oncologicos
		 */
		public function ListarCodigosOncologicos() {

			$Consulta = $this->Modelo->ListadoCodigosOncologicos();
			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Listado', $Consulta);
			$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
				return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
			});
			echo $Plantilla->MostrarPlantilla('CodigosOncologicos/Listado.html', 'CLINICA');
		}
		
		/**
		 * Metodo Publico
		 * MostrarCodigosOncologicos($Id = false)
		 * 
		 * Genera la visualizacion de los datos del codigo oncologico
		 * @param $Id: id de la codigos oncologicos a visualizar
		 */
		public function MostrarCodigosOncologicos($Id = false) {
			
			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {
				
				$Consulta = $this->Modelo->BuscarCodigosOncologicos(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				echo $Plantilla->MostrarPlantilla('CodigosOncologicos/Visualizar.html', 'CLINICA');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('CodigosOncologicos/ListarCodigosOncologicos'));
				exit();
			}
		}
		
		/**
		 * Metodo Publico
		 * ModificarCodigosOncologicos($Id = false)
		 * 
		 * Genera la visualizacion de los datos de codigos oncologicos
		 * @param $Id: id de la codigos oncologicos
		 */	
		public function ModificarCodigosOncologicos($Id = false) {

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {

				$Validacion = new NeuralJQueryValidacionFormulario;
				$Validacion->Requerido('Clave_Oncologico', 'Ingrese La Clave Oncologica');
				$Validacion->Requerido('Codigo_Oncologico_1', 'Ingrese El Primer Codigo Oncologico');
				$Validacion->Requerido('Codigo_Oncologico_2', 'Ingrese El Segundo Codigo Oncologico');
				$Validacion->Requerido('Descripcion', 'Ingrese La Descripcion');
				$Script[] = $Validacion->MostrarValidacion('Form');
				$Consulta = $this->Modelo->BuscarCodigosOncologicos(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function($Parametro){
						return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
					});
				$Plantilla->ParametrosEtiquetas('Scripts', NeuralScriptAdministrador::OrganizarScript(false, $Script));
				$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
				echo $Plantilla->MostrarPlantilla('CodigosOncologicos/Modificar.html', 'CLINICA');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('CodigosOncologicos/ListarCodigosOncologicos'));
				exit();
			}
		}	
		
		/**
		 * Metodo Publico
		 * GuardarModificacionCodigosOncologicos()
		 * 
		 * Guarda las modificaciones de los datos de la modifcacion de codigos oncologicos
		 */	
		public function GuardarModificacionCodigosOncologicos() {
			if(isset($_POST) == true AND isset($_POST['Validacion']) == true) {
				if(is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['Id']), 'CLINICA')) == true AND (NeuralEncriptacion::DesencriptarDatos($_POST['Validacion'], 'CLINICA') == date("Y-m-d")) == true) {
					unset($_POST['Validacion']);
					$Condicion = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['Id']), 'CLINICA');
					$Matriz = array('Id','Status');
					unset($_POST['Id']);
					$this->Modelo->ActualizarRegistro($_POST, array('Id' => $Condicion), $Matriz);
					header("Location: ".NeuralRutasApp::RutaURL('CodigosOncologicos/ListarCodigosOncologicos'));
					exit();
				}
				else {
					header("Location: ".NeuralRutasApp::RutaURL('CodigosOncologicos/ListarCodigosOncologicos'));
					exit();
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('CodigosOncologicos/ListarCodigosOncologicos'));
				exit();
			}
		}
		
		/**
		 * Metodo Publico
		 * EliminarCodigosOncologicos($Id = false)
		 * 
		 * Genera la visualizacion de los datos del Codigos Oncologicos
		 * @param $Id: id del codigos oncologico
		 */	
		public function EliminarCodigosOncologicos($Id = false) {

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {

				$Consulta = $this->Modelo->BuscarCodigosOncologicos(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
				return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
				});
				echo $Plantilla->MostrarPlantilla('CodigosOncologicos/Eliminar.html', 'CLINICA');
				unset($Consulta, $Plantilla);
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('CodigosOncologicos/ListarCodigosOncologicos'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * ActualizaEstado($Id = false, $Estado = false)
		 * 
		 * Genera la visualizacion de los datos del codigo oncologico
		 * @param $Id: id del codigo oncologico de la tabla tbl_clinica_info_codigooncologico
		 * @param $Estado: Status del codigo oncologico ('ELIMINADO')
		 */
		public function ActualizaEstado($Id = false, $Estado = false) {
			
			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true AND $Estado == true AND AyudasArray::VerificaEstado(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Estado), 'CLINICA')) == true ) {
				$Update = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Estado), 'CLINICA');
				$Condicion = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA');
				$Matriz = array('Id', 'Clave_Oncologico', 'Codigo_Oncologico_1', 'Codigo_Oncologico_2', 'Descripcion');
				$this->Modelo->ActualizarEstado(array( 'Status' => $Update), array('Id' => $Condicion), $Matriz);
				header("Location: ".NeuralRutasApp::RutaURL('CodigosOncologicos/ListarCodigosOncologicos'));
				exit();
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('CodigosOncologicos/ListarCodigosOncologicos'));
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