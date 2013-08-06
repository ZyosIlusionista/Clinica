<?php
	
	class CodigosOncologicos_Modelo extends AyudasSQLConsultas {
		
		function __Construct() {
			parent::__Construct();
		}
		
		/**
		 * Metodo Publico
		 * BuscaOncologico($Clave_Oncologica = false)
		 * 
		 * Genera la consulta para establecer si la informacion  existe en la base
		 * @param $Descripcion: Clave_Oncologica a validar
		 */
		public function BuscaOncologico($Clave_Oncologico = false){
			if($Clave_Oncologico == true){
				$ConsultaSQL = new NeuralBDConsultas;
				$ConsultaSQL->CrearConsulta('tbl_clinica_info_codigo_oncologicos');
				$ConsultaSQL->AgregarColumnas('Clave_Oncologico');
				$ConsultaSQL->AgregarCondicion("Clave_Oncologico = '$Clave_Oncologico'");
				$ConsultaSQL->PrepararCantidadDatos('Cantidad');
				$ConsultaSQL->PrepararQuery();
				return $ConsultaSQL->ExecuteConsulta('CLINICA');
			}
		}
		
		
		/**
		 * Metodo Publico
		 * BuscaCodigoExiste($Clave_Oncologico = false, $Codigo_Oncologico_1 = false, $Codigo_Oncologico_2 = false, $Descripcion = false)
		 * 
		 * Genera la consulta para establecer si la informacion  existe en la base
		 * @param $Clave_Oncologico: Clave_Oncologico a validar
		 * @param $Codigo_Oncologico_1: Codigo_Oncologico_1 a validar
		 * @param $Codigo_Oncologico_2: Codigo_Oncologico_2 a validar
		 * @param $Descipcion: Descripcion a validar
		 */
		public function BuscaCodigoExiste($Clave_Oncologico = false, $Codigo_Oncologico_1 = false, $Codigo_Oncologico_2 = false, $Descripcion = false){
			if($Clave_Oncologico == true and $Codigo_Oncologico_1 == true AND $Codigo_Oncologico_2 == true AND $Descripcion == true){
				$ConsultaSQL = new NeuralBDConsultas;
				$ConsultaSQL->CrearConsulta('tbl_clinica_info_codigo_oncologicos');
				$ConsultaSQL->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_codigo_oncologicos', array('Id', 'Status')));
				$ConsultaSQL->AgregarCondicion("Clave_Oncologico = '$Clave_Oncologico'");
				$ConsultaSQL->AgregarCondicion("Codigo_Oncologico_1 = '$Codigo_Oncologico_1'");
				$ConsultaSQL->AgregarCondicion("Codigo_Oncologico_2 = '$Codigo_Oncologico_2'");
				$ConsultaSQL->AgregarCondicion("Descripcion = '$Descripcion'");
				$ConsultaSQL->PrepararCantidadDatos('Cantidad');
				$ConsultaSQL->PrepararQuery();
				return $ConsultaSQL->ExecuteConsulta('CLINICA');
			}
		}
		
		/**
		 * Metodo Publico
		 * GuardarNuevosDatosGenerales($Array = false);
		 * 
		 * Guarda los Datos Generales de una categoria
		 * @param $Array: array asociativo con los datos a guardar
		 * */
		public function GuardarNuevosDatosGenerales($Array = false){

			if($Array == true AND is_array($Array) == true) {
				return self::GuardarDatos($Array, 'tbl_clinica_info_codigo_oncologicos', array('Id','Status'), 'CLINICA');
			}
		}
		
		/**
		 * Metodo Publico
		 * ListadoCodigosOncologicos()
		 * 
		 * Genera el listado de codigos oncologicos de la tabla correspondientes
		 */
		public function ListadoCodigosOncologicos(){

			$Consulta = new NeuralBDConsultas;
			$Consulta->CrearConsulta('tbl_clinica_info_codigo_oncologicos');
			$Consulta->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_codigo_oncologicos'));
			$Consulta->AgregarCondicion("Status != 'ELIMINADO'");
			$Consulta->PrepararQuery();
			return $Consulta->ExecuteConsulta('CLINICA');
		}

		/**
		 * Metodo Publico
		 * BuscarCodigosOncologicos($Id)
		 * 
		 * Genera la busqueda de codigos oncologicos segun ID
		 * @param $Id: Id a consultar
		 * @return array de datos
		 */
		public function BuscarCodigosOncologicos($Id = false){
			
			if($Id == true){
				$ConsultaSQL = new NeuralBDConsultas;
				$ConsultaSQL->CrearConsulta('tbl_clinica_info_codigo_oncologicos');
				$ConsultaSQL->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_codigo_oncologicos', array('Status')));
				$ConsultaSQL->AgregarCondicion("Id='".$Id."'");
				$ConsultaSQL->PrepararQuery();
				return $ConsultaSQL->ExecuteConsulta('CLINICA');
			}
		
		}
		
		/**
		 * Metodo Publica
		 * ActualizarEstado($Array = false, $Condicion = false, $Omitidos = false)
		 * 
		 * @param $Array: Arreglo asociativo de datos actualizar
		 * @example array('Columna' => 'Dato')
		 * @param $Condicion: Arreglo asociativo de la condicion
		 * @example array('Id' => '1')
		 * @param $Omitidos: Arreglo secuencial de elementos omitidos de la tabla
		 */
		public function ActualizarEstado($Array = false, $Condicion = false, $Omitidos = false){

			if(is_array($Array) == true AND is_array($Condicion) == true AND is_array($Omitidos) == true ) {
				return self::ActualizarDatos($Array, $Condicion, 'tbl_clinica_info_codigo_oncologicos', $Omitidos, 'CLINICA');
			}
		}

		/**
		 * Metodo Publica
		 * ActualizarRegistro($Array = false, $Condicion = false, $Omitidos = false)
		 * 
		 * @param $Array: Arreglo asociativo de datos actualizar
		 * @example array('Columna' => 'Dato')
		 * @param $Condicion: Arreglo asociativo de la condicion
		 * @example array('Id' => '1')
		 * @param $Omitidos: Arreglo secuencial de elementos omitidos de la tabla
		 */
		public function ActualizarRegistro($Array = false, $Condicion = false, $Omitidos = false){

			if(is_array($Array) == true AND is_array($Condicion) == true AND is_array($Omitidos) == true ) {
				return self::ActualizarDatos($Array, $Condicion, 'tbl_clinica_info_codigo_oncologicos', $Omitidos, 'CLINICA');
			}
		}
		
		/**
		 * Metodo Publico
		 * Excel()
		 * 
		 * Exportar base de datos a excel
		 */
		public function Excel(){
			
			return self::DescargarDatosTablaExcel('CodigosOncologicos_'.strtotime(date("Y-m-d H:i:s")), 'tbl_clinica_info_codigo_oncologicos');
		}
		
		/**
		 * Metodo Privado
		 * ListarColumnas($Tabla = false, $Omitidos = false)
		 * 
		 * @param $Alias: es un array asociativo
		 * @example array('Columna' => 'Alias')
		 */
		private function ListarColumnas($Tabla = false, $Omitidos = false, $Alias = false) {
			
			if($Tabla == true) {
				$Consulta = new NeuralBDConsultas;
				$Lista = $Consulta->ExecuteQueryManual('CLINICA', "DESCRIBE $Tabla");
				$Cantidad = count($Lista);
				$Matriz = (is_array($Omitidos) == true) ? array_flip($Omitidos) : array();
				$AliasBase = (is_array($Alias) == true) ? $Alias : array();
				for ($i=0; $i<$Cantidad; $i++) {
					if(array_key_exists($Lista[$i]['Field'], $Matriz) == false) {
						if(array_key_exists($Lista[$i]['Field'], $AliasBase) == true) {
							$Columna[] = $Tabla.'.'.$Lista[$i]['Field'].' AS '.$AliasBase[$Lista[$i]['Field']];
						}
						else {
							$Columna[] = $Tabla.'.'.$Lista[$i]['Field'];
						}
					}
				}
				return implode(', ', $Columna);
			}
		}

	}
