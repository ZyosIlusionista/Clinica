<?php
	
	class AyudasSQLConsultas extends Modelo {
		
		function __Construct() {
			parent::__Construct();
		}
		
		/**
		 * Metodo Privado
		 * ListarColumnasTabla($Tabla = false, $Omitidos = array() ,$Tipo = 'LISTA')
		 * 
		 * Lista las columnas de una tabla segun la necesidad
		 * @param $Tabla: Nombre de la Tabla a Listar las Columnas
		 * @param $Omitidos: array incremental con el nombre de las columnas a omitir
		 * @param $Tipo: el tipo de datos que requerimos
		 * @return LISTA: Lista ordenada separa por comas
		 * @return ARRAY: devuelve un array asociativo con el nombre de las columnas
		 * */
		public function ListarColumnasTabla($Tabla = false, $Omitidos = array() ,$Tipo = 'LISTA', $Aplicacion = 'CLINICA') {
			
			if($Tabla == true AND is_array($Omitidos) == true AND $Tipo == true AND $Aplicacion == true) {
				$Consulta = new NeuralBDConsultas;
				$Datos = $Consulta->ExecuteQueryManual($Aplicacion, "DESCRIBE $Tabla");
				$Cantidad = count($Datos);
				$Matriz = array_flip($Omitidos);
				for ($i=0; $i<$Cantidad; $i++) {
					if(array_key_exists($Datos[$i]['Field'], $Matriz) == false) {
						$Lista[] = $Datos[$i]['Field'];
					}
				}
				unset($Tabla, $Omitidos, $Consulta, $Datos, $Cantidad, $Matriz, $Aplicacion);
				if($Tipo == 'LISTA') {
					return implode(', ', $Lista);
				}
				else {
					return array_flip($Lista);
				}
			}
		}
		
		/**
		 * Metodo Publico
		 * GuardarDatos($Array = false, $Tabla = false, $Omitidos = false, $Aplicacion = 'CLINICA');
		 * 
		 * Guarda Datos en la tabla seleccionada
		 * @param $Array: array asociativo con los datos a guardar
		 * @param $Tabla: donde se guardaran los datos
		 * @param $Omitidos: array incremental con las columnas omitidas donde no se validara
		 * @example array('id', 'nombre', 'apellidos')
		 * @param $Aplicacion: Aplicacion que se utilizara para conexion a BD
		 * */
		public function GuardarDatos($Array = false, $Tabla = false, $Omitidos = false, $Aplicacion = 'CLINICA') {
			
			if($Array == true AND is_array($Array) == true AND $Tabla == true AND is_array($Omitidos) == true AND $Aplicacion == true) {
				$Matriz = self::ListarColumnasTabla($Tabla, $Omitidos, 'ARRAY');
				if(count($Array) == count($Matriz)) {
					$SQL = new NeuralBDGab;
					$SQL->SeleccionarDestino($Aplicacion, $Tabla);
					foreach ($Array AS $Columna => $Valor) {
						if(array_key_exists($Columna, $Matriz) == true) {
							$SQL->AgregarSentencia($Columna, $Valor);
						}
					}
					$SQL->InsertarDatos();
					unset($Aplicacion, $Array, $Omitidos, $Tabla, $Columna, $Matriz, $SQL, $Valor);
				}
				else {
					return 'Las Columnas No Coinciden Con la Cantidad de Datos Recibidos';
				}	
			}
		}
		
		/**
		 * Metodo Publico
		 * ActualizarDatos($Array = false, $Condiciones = false, $Tabla = false, $Omitidos = false, $Aplicacion = 'CLINICA');
		 * 
		 * Guarda Datos en la tabla seleccionada
		 * @param $Array: array asociativo con los datos a actualizar
		 * @param $Condiciones: array asociativo con las condiciones a cumplir
		 * @example array('id' => '2', 'estado' => 'ACTIVO')
		 * @param $Tabla: donde se guardaran los datos
		 * @param $Omitidos: array incremental con las columnas omitidas donde no se validara
		 * @example array('id', 'nombre', 'apellidos')
		 * @param $Aplicacion: Aplicacion que se utilizara para conexion a BD
		 * */
		public function ActualizarDatos($Array = false, $Condiciones = false, $Tabla = false, $Omitidos = false, $Aplicacion = 'CLINICA') {
			
			if($Array == true AND is_array($Array) == true AND is_array($Condiciones) == true AND $Tabla == true AND is_array($Omitidos) == true AND $Aplicacion == true) {
				$Matriz = self::ListarColumnasTabla($Tabla, $Omitidos, 'ARRAY');
				if(count($Array) == count($Matriz)) {
					$SQL = new NeuralBDGab;
					$SQL->SeleccionarDestino($Aplicacion, $Tabla);
					foreach ($Array AS $Columna => $Valor) {
						if(array_key_exists($Columna, $Matriz) == true) {
							$SQL->AgregarSentencia($Columna, $Valor);
						}
					}
					foreach ($Condiciones AS $CColumna => $CValor) {
						$SQL->AgregarCondicion($CColumna, $CValor);
					}
					$SQL->ActualizarDatos();
					unset($Aplicacion, $Array, $Condiciones, $Omitidos, $Tabla, $CColumna, $CValor, $Matriz, $SQL, $Valor);
				}
				else {
					return 'Las Columnas No Coinciden Con la Cantidad de Datos Recibidos';
				}
			}
		}
		
		/**
		 * Metodo Publico
		 * EliminarDatos($Condiciones = false, $Tabla = false, $Aplicacion = 'CLINICA');
		 * 
		 * Elimina los datos seleccionados
		 * @param $Condiciones: array asociativo con las condiciones correspondientes
		 * @example array('id' => '4', 'Estado' => 'ACTIVO')
		 * @param $Tabla: tabla donde se realizara el procedimiento
		 * @param $Aplicacion: aplicacion donde se tomaran los datos de BD
		 * */
		public function EliminarDatos($Condiciones = false, $Tabla = false, $Aplicacion = 'CLINICA') {
			
			if(is_array($Condiciones) == true AND $Tabla == true AND $Aplicacion == true) {
				$SQL = new NeuralBDGab;
				$SQL->SeleccionarDestino($Aplicacion, $Tabla);
				foreach ($Condiciones AS $Columna => $Valor) {
					$SQL->AgregarCondicion($Columna, $Valor);
				}
				$SQL->EliminarDatos();
			}
		}
		
		/**
		 * Metodo Publico
		 * DescargarDatosTablaExcel($NombreArchivo = false, $Tabla = false, $Condiciones = array());
		 * 
		 * Descarga Todos los Datos de una Tabla en archivo excel
		 * Las columnas de la tabla en los comentarios de dicha columnas debe 
		 * estar el nombre respectivo
		 * @param $NombreArchivo: Nombre del archivo SIN EXTENSION
		 * @param $Tabla: Nombre de la tabla
		 * @param $Condiciones: array de condiciones para filtrar la consulta
		 * @example array("Id = '1'", "Valor = '1245'")
		 * */
		Public function DescargarDatosTablaExcel($NombreArchivo = false, $Tabla = false, $Condiciones = array()) {
			
			if($NombreArchivo == true AND $Tabla == true) {
				$Encabezados = self::ListarComentariosColumTabla($Tabla);
				$Consulta = new NeuralBDConsultas;
				$Consulta->CrearConsulta($Tabla);
				$Consulta->AgregarColumnas(self::ListarColumnasTabla($Tabla, array()));
				foreach ($Condiciones AS $Indice => $Condicion) {
					$Consulta->AgregarCondicion($Condicion);
				}
				//$Consulta->PrepararCantidadDatos('Cantidad');
				$Consulta->PrepararQuery();
				$Data = $Consulta->ExecuteConsulta('CLINICA');
				$Matriz = array_merge($Encabezados, $Data);
				$Excel = new NeuralExportarArchivoExcel;
				$Excel->MatrizDatos($Matriz);
				$Excel->DescargarCrearExcel($NombreArchivo);
			}
		}
		
		private function ListarComentariosColumTabla($Tabla = false) {
			
			if($Tabla == true) {
				$Consulta = new NeuralBDConsultas;
				$Datos = $Consulta->ExecuteQueryManual('CLINICA', "SELECT COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$Tabla'");
				$Cantidad = count($Datos);
				for ($i=0; $i<$Cantidad; $i++) {
					$Lista['0'][] = $Datos[$i]['COLUMN_COMMENT'];
				}
				unset($Tabla, $Cantidad, $Consulta, $Datos);
				return $Lista;
			}
		}
	}