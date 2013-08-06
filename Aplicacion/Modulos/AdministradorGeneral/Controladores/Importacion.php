<?php

	class Importacion extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AyudasSession::ValSessionGlobal();
		}
		
		/**
		 * Metodo Publico
		 * Index()
		 * 
		 * Muestra la pantalla para importacion de datos
		 */
		public function Index() {

			header("Location: ".NeuralRutasApp::RutaURL('Importacion/AgregarImportacion'));
			exit();
		}

		/**
		 * Metodo Publico
		 * AgregarImportacion()
		 * 
		 * Genera el formulario para crear la importacion de datos en el sistema
		 */
		public function AgregarImportacion() {
		
			$Consulta = $this->Modelo->BuscarCategoria();
			$Validacion = new NeuralJQueryValidacionFormulario;
			$Validacion->Requerido('Categoria', 'Seleccione la categoría');
			$Validacion->Requerido('Importacion', 'Ingrese los datos a importar');
			$Script[] = $Validacion->MostrarValidacion('Form');
			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript(array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Scripts', NeuralScriptAdministrador::OrganizarScript(false, $Script));
			$Plantilla->ParametrosEtiquetas('Consulta', $Consulta);
			echo $Plantilla->MostrarPlantilla('Importacion/NuevaImportacion.html', 'CLINICA');
			unset($Consulta, $Validacion, $Plantilla);
		}
		
		/**
		 * Metodo Publico
		 * ValidarImportacion()
		 * 
		 * Visualiza en una lista los datos a subir a la tabla correspondiente 
		 */
		public function ValidarImportacion() {

			$Plantilla = new NeuralPlantillasTwig;
			$Plantilla->ParametrosEtiquetas('Styles', NeuralScriptAdministrador::OrganizarScript( array('CSS' => array('STYLE', 'TABLET', 'PRETTIFY')), false, 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Archivo', NeuralEncriptacion::EncriptarDatos(gzcompress($_POST['Importacion']), 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Validacion', NeuralEncriptacion::EncriptarDatos(date("Y-m-d"), 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Categoria', NeuralEncriptacion::EncriptarDatos($_POST['Categoria'], 'CLINICA'));
			$Plantilla->ParametrosEtiquetas('Listado', AyudasArray::FormatoMultiregistros(AyudasCopyPasteExcelArray::ConvertirExcelArrayColumnas($_POST['Importacion'], array('Codigo', 'Nombre_Comercial', 'Nombre_Generico', 'Precio_Venta', 'Unidad', 'Tipo_Unidad')), array('Codigo', 'Precio_Venta', 'Unidad', 'Tipo_Unidad')));
			echo $Plantilla->MostrarPlantilla('Importacion/Validar.html', 'CLINICA');
			unset($Plantilla);
		}
		
		/**
		 * Metodo Publico
		 * GuardarImportacion()
		 * 
		 * Guarda los datos de excel referente a farmacos
		 */
		public function GuardarImportacion(){
			
			if(isset($_POST) == true AND isset($_POST['Validacion']) == true){
				if(NeuralEncriptacion::DesencriptarDatos($_POST['Validacion'], 'CLINICA') == date("Y-m-d")) {
					$Array = AyudasArray::FormatoMultiregistros(AyudasCopyPasteExcelArray::ConvertirExcelArrayColumnas(gzuncompress(NeuralEncriptacion::DesencriptarDatos($_POST['Archivo'], 'CLINICA')), array('Codigo', 'Nombre_Comercial', 'Nombre_Generico', 'Precio_Venta', 'Unidad', 'Tipo_Unidad')), array('Codigo', 'Precio_Venta', 'Unidad', 'Tipo_Unidad'));
					$Id = NeuralEncriptacion::DesencriptarDatos($_POST['Categoria'], 'CLINICA'); 
					$this->Modelo->GuardarDatosExcel($Array, $Id); 
					header("Location: ".NeuralRutasApp::RutaURL('Farmacos/ListarFarmacos'));
					unset($Array, $Id, $this);
					exit();
				}
			}
		}

	}
