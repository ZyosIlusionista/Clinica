<?php

	class Farmacos extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AyudasSession::ValSessionGlobal();
		}
		
		/**
		 * Metodo Publico
		 * Index()
		 * 
		 * Muestra el Llistado de Farmacos.
		 */
		public function Index() {

			header("Location: ".NeuralRutasApp::RutaURL('Farmacos/ListarFarmacos'));
			exit();
		}

		/**
		 * Metodo Publico
		 * AgregarVisualizar($Error = false)
		 * 
		 * Genera el formulario para crear farmacos en el sistema.
		 * @param $Error: Muestra error en los pasos del sistema
		 * 
		 */
		public function AgregarVisualizar() {
			
			$Consulta = $this->Modelo->BuscarCategoria();
			$Unidades = $this->Modelo->BuscarUnidades();
			$Validacion = new NeuralJQueryValidacionFormulario;
			$Validacion->Requerido('Codigo', 'Ingrese el Código');
			$Validacion->Numero('Codigo', 'Solo dígitos');
			$Validacion->Requerido('Nombre_Comercial', 'Ingrese El Nombre Comercial');
			$Validacion->Requerido('Nombre_Generico', 'Ingresa El Nombre Genérico');
			$Validacion->Requerido('Categoria', 'Selecciona La Categoría');
			$Validacion->Requerido('Precio_Venta', 'Ingrese el Precio de Venta');
			$Validacion->Numero('Precio_Venta', 'Solo digitos');
			$Validacion->CantMaxCaracteres('Precio_Venta', '10', 'Sobrepasas la cantidad de digitos');
			$Validacion->Requerido('Unidad', 'Ingrese la Unidad');
			$Validacion->Numero('Unidad', 'Solo digitos');
			$Validacion->CantMaxCaracteres('Unidad', '15', 'Sobrepasas la cantidad de digitos');
			$Validacion->Requerido('Tipo_Unidad', 'Seleccione Tipo de Unidad');
			$Script[] = $Validacion->MostrarValidacion('Form');
			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
		    $Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript(array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Scripts', NeuralScriptAdministrador::OrganizarScript(false, $Script));
			$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
			$Plantilla->ParametrosEtiquetas('Unidades', $Unidades);
			echo $Plantilla->MostrarPlantilla('Farmacos/NuevoFarmacos.html', 'CLINICA');
			unset($Consulta, $Unidades, $Validacion, $Script, $Plantilla);
		}
		
		/**
		 * Metodo Publico
		 * GuardarNuevoFarmaco()
		 * 
		 * Genera el proceso General de Guardar datos de farmacos.
		 */
		public function GuardarNuevoFarmacos() {
			
			if(isset($_POST) == true AND isset($_POST['Validacion']) == true) {
				if(NeuralEncriptacion::DesencriptarDatos($_POST['Validacion'], 'CLINICA') == date("Y-m-d")) {
					$DatosPost = AyudasPost::ConvertirTextoUcwordsOmitido(AyudasPost::FormatoEspacio(AyudasPost::LimpiarInyeccionSQL($_POST)), array('Categoria', 'Codigo', 'Precio_Venta', 'Unidad', 'Tipo_Unidad', 'Validacion'));
					$Verifica = $this->Modelo->BuscaCodigo($DatosPost['Codigo'], $DatosPost['Categoria']);
					if($Verifica['Cantidad'] >= 1){
						header("Location: ". NeuralRutasApp::RutaURL('Farmacos/AgregarVisualizar/'. AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos('USUARIOEXISTE', 'CLINICA'))));
						exit();
					}
					else{
						$Existe = $this->Modelo->BuscaFarmacoExiste($DatosPost['Codigo'], $DatosPost['Nombre_Comercial'], $DatosPost['Nombre_Generico'], $DatosPost['Categoria'], $DatosPost['Precio_Venta'],  $DatosPost['Unidad'],  $DatosPost['Tipo_Unidad']);
						if ($Existe['Cantidad'] >= 1) {
							header("Location: ".NeuralRutasApp::RutaURL('Farmacos/AgregarVisualizar/'.AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos('USUARIOEXISTE', 'CLINICA'))));
							unset($this, $DatosPost, $Verifica, $Existe);
							exit();
						}
						else {
							$this->Modelo->GuardarNuevosFarmaco(AyudasArray::Eliminar($DatosPost, array('Validacion')));;
							unset($this, $DatosPost, $Existe, $Verifica);
							header("Location: ".NeuralRutasApp::RutaURL('Farmacos'));
							exit();
						}
					}
				}
				else {
				header("Location: ".NeuralRutasApp::RutaURL('Farmacos'));
				exit();
				}
			}
		}

		/**
		 * Metodo Publico
		 * ListarFarmacos()
		 * 
		 * Mostrar el listado de Farmacos
		 */
		public function ListarFarmacos() {

			$Consulta = $this->Modelo->ListadoFarmacos();
			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Listado', $Consulta);
			$Plantilla->ParametrosEtiquetas('Categorias', $this->Modelo->BuscarCategoria());
			$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
				return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
			});
			echo $Plantilla->MostrarPlantilla('Farmacos/Listado.html', 'CLINICA');
		}
		
		/**
		 * Metodo Publico
		 * MostrarFarmacos($Id = false)
		 * 
		 * Genera la visualizacion de los datos del farmaco
		 * @param $Id: id del usuario a visualizar
		 */
		public function MostrarFarmacos($Id = false) {
			
			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {
				$Consulta = $this->Modelo->BuscarFarmacos(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				echo $Plantilla->MostrarPlantilla('Farmacos/Visualizar.html', 'CLINICA'); 
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Farmacos/ListarFarmacos'));
				exit();
			}
		}
		
		/**
		 * Metodo Publico
		 * ModificarFarmacos($Id = false)
		 * 
		 * Genera la visualizacion de los datos del farmaco
		 * @param $Id: id del farmaco
		 */	
		public function ModificarFarmacos($Id = false) {

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {

				$Validacion = new NeuralJQueryValidacionFormulario;
				$Validacion->Requerido('Codigo', 'Ingrese El Codigo ');
				$Validacion->Numero('Codigo', 'Solo digitos');
				$Validacion->Requerido('Nombre_Comercial','Ingrese El Nombre Comecial');
				$Validacion->Requerido('Nombre_Generico', 'Ingrese El Nombre Generico');
				$Validacion->Requerido('Categoria', 'Selecciona La Categoria');
				$Validacion->Requerido('Precio_Venta', 'Ingrese el Precio de Venta');
				$Validacion->Numero('Precio_Venta', 'Solo digitos');
				$Validacion->CantMaxCaracteres('Precio_Venta', '10', 'Sobrepasas la cantidad de digitos');
				$Validacion->Requerido('Unidad', 'Ingrese la Unidad');
				$Validacion->Numero('Unidad', 'Solo digitos');
				$Validacion->CantMaxCaracteres('Unidad', '15', 'Sobrepasas la cantidad de digitos');
				$Validacion->Requerido('Tipo_Unidad', 'Seleccione Tipo de Unidad');
				$Script[] = $Validacion->MostrarValidacion('Form');				
				$Consulta = $this->Modelo->BuscarFarmacos(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				$Plantilla->ParametrosEtiquetas('Categorias', $this->Modelo->BuscarCategoria());
				$Plantilla->ParametrosEtiquetas('Unidades', $this->Modelo->BuscarUnidades());
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function($Parametro){
						return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
					});
				$Plantilla->ParametrosEtiquetas('Scripts', NeuralScriptAdministrador::OrganizarScript(false, $Script));
				$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
				echo $Plantilla->MostrarPlantilla('Farmacos/Modificar.html', 'CLINICA');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Farmacos/ListarFarmacos'));
				exit();
			}
		}	
		
		/**
		 * Metodo Publico
		 * GuardarModificacionFarmacos()
		 * 
		 * Guarda las modificaciones de los datos de la modifcacion de farmacos
		 */	
		public function GuardarModificacionFarmacos() {
			if(isset($_POST) == true AND isset($_POST['Validacion']) == true) {
				if(is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['Id']), 'CLINICA')) == true AND (NeuralEncriptacion::DesencriptarDatos($_POST['Validacion'], 'CLINICA') == date("Y-m-d")) == true) {
					unset($_POST['Validacion']);
					$Condicion = NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['Id']), 'CLINICA');
					$Matriz = array('Id', 'Status');
					unset($_POST['Id']);
					$DatosPost = AyudasPost::ConvertirTextoUcwordsOmitido(AyudasPost::FormatoEspacio(AyudasPost::LimpiarInyeccionSQL($_POST)), array('Categoria', 'Codigo', 'Precio_Venta', 'Unidad', 'Tipo_Unidad'));
					$this->Modelo->ActualizarRegistro($DatosPost, array('Id' => $Condicion), $Matriz);
					header("Location: ".NeuralRutasApp::RutaURL('Farmacos/ListarFarmacos'));
					unset($Condicion, $Matriz, $DatosPost, $this);
					exit();
				}
				else {
					header("Location: ".NeuralRutasApp::RutaURL('Farmacos/ListarFarmacos'));
					exit();
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Farmacos/ListarFarmacos'));
				exit();
			}
		}

		/**
		 * Metodo Publico
		 * EliminarFarmacos($Id = false)
		 * 
		 * Genera la visualizacion de los datos del farmaco
		 * @param $Id: id del farmaco
		 */	
		public function EliminarFarmacos($Id = false) {

			if($Id == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA')) == true) {

				$Consulta = $this->Modelo->BuscarFarmacos(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($Id), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
				$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
				return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
				});
				echo $Plantilla->MostrarPlantilla('Farmacos/Eliminar.html', 'CLINICA');
				unset($Consulta, $Plantilla);
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Farmacos/ListarFarmacos'));
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
				$Matriz = array('Id', 'Codigo', 'Nombre_Comercial', 'Nombre_Generico', 'Categoria', 'Codigo', 'Precio_Venta', 'Unidad', 'Tipo_Unidad');
				$this->Modelo->ActualizarEstado(array( 'Status' => $Update), array('Id' => $Condicion), $Matriz);
				header("Location: ".NeuralRutasApp::RutaURL('Farmacos/ListarFarmacos'));
				exit();
			}
			else {
				header("Location: ".NeuralRutasApp::RutaURL('Farmacos/ListarFarmacos'));
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

		/**
		 * Metodo Publico
		 * TablaFarmacos()
		 *
		 * Muestra la lista de farmacos por categoria
		 */
		public function TablaFarmacos() {

			if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) == true AND mb_strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' AND $_SERVER['HTTP_REFERER'] != $_SERVER['HTTP_HOST'] AND $_POST['valor'] == true AND is_numeric(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['valor']), 'CLINICA')) == true AND ctype_digit(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['valor']), 'CLINICA')) == true) { 

				$Consulta = $this->Modelo->ListadoFarmacosCategoria(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['valor']), 'CLINICA'));
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Listado', $Consulta);
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
					return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
				});
				echo $Plantilla->MostrarPlantilla('Farmacos/Tabla.html');
			}
			elseif ($_SERVER['HTTP_REFERER'] != $_SERVER['HTTP_HOST'] AND $_POST['valor'] == true AND NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['valor']), 'CLINICA') == 'Todo' AND is_string(NeuralEncriptacion::DesencriptarDatos(AyudasConversorHexAscii::HEX_ASCII($_POST['valor']), 'CLINICA')) == true ) {

				$Consulta = $this->Modelo->ListadoFarmacos();
				$Plantilla = new NeuralPlantillasTwig;
				$Plantilla->ParametrosEtiquetas('Listado', $Consulta);
				$Plantilla->AgregarFuncionAnonima('Encriptacion', function ($Parametro) {
					return AyudasConversorHexAscii::ASCII_HEX(NeuralEncriptacion::EncriptarDatos($Parametro, 'CLINICA'));
				});
				echo $Plantilla->MostrarPlantilla('Farmacos/Tabla.html');
			}
		}

}