<?php
	
	class Unidades_Modelo extends AyudasSQLConsultas {
		
		function __Construct() {
			parent::__Construct();
		}
		
		/**
		 * Metodo Publico
		 * BuscaUnidadesExiste($Descripcion = false)
		 * 
		 * Genera la consulta para establecer si la descripcion existe en la tabla correspondiente
		 * @param $Descripcion: Descripcion a validar
		 */
		public function BuscaUnidadesExiste($Descripcion = false) {
			
			if($Descripcion == true){
				$ConsultaSQL = new NeuralBDConsultas;
				$ConsultaSQL->CrearConsulta('tbl_clinica_info_unidades');
				$ConsultaSQL->AgregarColumnas('Descripcion');
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
		 * Guarda los Datos Generales de una unidad
		 * @param $Array: array asociativo con los datos a guardar
		 * */
		public function GuardarNuevosDatosGenerales($Array = false) {

			if($Array == true AND is_array($Array) == true) {
				return self::GuardarDatos($Array, 'tbl_clinica_info_unidades', array('Id','Status'), 'CLINICA');
			}
		}
		
		/**
		 * Metodo Publico
		 * ListadoUnidades()
		 * 
		 * Genera el listado de unidades de la tabla correspondientes
		 */
		public function ListadoUnidades() {

			$Consulta = new NeuralBDConsultas;
			$Consulta->CrearConsulta('tbl_clinica_info_unidades');
			$Consulta->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_unidades'));
			$Consulta->AgregarCondicion("Status != 'ELIMINADO'");
			$Consulta->PrepararQuery();
			return $Consulta->ExecuteConsulta('CLINICA');
		}
		
		/**
		 * Metodo Publico
		 * BuscarUnidades($Id)
		 * 
		 * Genera la busqueda de unidad segun ID
		 * @param $Id: Id a consultar
		 * @return array de datos
		 */
		public function BuscarUnidades($Id = false){
			
			if($Id == true){
				$ConsultaSQL = new NeuralBDConsultas;
				$ConsultaSQL->CrearConsulta('tbl_clinica_info_unidades');
				$ConsultaSQL->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_unidades', array('Status')));
				$ConsultaSQL->AgregarCondicion("Id='".$Id."'");
				$ConsultaSQL->PrepararQuery();
				return $ConsultaSQL->ExecuteConsulta('CLINICA');
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
				return self::ActualizarDatos($Array, $Condicion, 'tbl_clinica_info_unidades', $Omitidos, 'CLINICA');
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
				return self::ActualizarDatos($Array, $Condicion, 'tbl_clinica_info_unidades', $Omitidos, 'CLINICA');
			}
		}

		/**
		 * Metodo Publico
		 * Excel()
		 * 
		 * Exportar base de datos a excel
		 */
		public function Excel(){
			
			return self::DescargarDatosTablaExcel('Unidades_'.strtotime(date("Y-m-d H:i:s")), 'tbl_clinica_info_unidades');
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