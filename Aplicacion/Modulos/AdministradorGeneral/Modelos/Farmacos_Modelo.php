<?php
	
	class Farmacos_Modelo extends AyudasSQLConsultas {
		
		function __Construct() {
			parent::__Construct();
		}
		
		/**
		 * Metodo Publico
		 * BuscaCategoria()
		 * 
		 * Genera la consulta para establecer si la lista de categoria
		 * 
		 */
		public function BuscarCategoria() {

			$Consulta = new NeuralBDConsultas;
			$Consulta->CrearConsulta('tbl_clinica_info_categorias');
			$Consulta->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_categorias'));
			$Consulta->AgregarCondicion("Status != 'ELIMINADO'");
			$Consulta->PrepararQuery();
			return $Consulta->ExecuteConsulta('CLINICA');
		}

		/**
		* Metodo Publico
		* BuscaUnidades()
		*
		* Genera la consulta para establecer si la lista de unidades
		*/
		public function BuscarUnidades(){
			$Consulta = new NeuralBDConsultas;
			$Consulta->CrearConsulta('tbl_clinica_info_unidades');
			$Consulta->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_unidades'));
			$Consulta->AgregarCondicion("Status != 'ELIMINADO'");
			$Consulta->PrepararQuery();
			return $Consulta->ExecuteConsulta('CLINICA');
		}
		
		/**
		 * Metodo Publico
		 * BuscaCodigo($Codigo = false  $Categoria = false)
		 * 
		 * Genera la consulta para establecer si los datos  entrantes existe en la base
		 * @param $Codig: Codigo a validar
		 * @param $Nombre_Comercial: Nombre_Comercial a validar
		 * @param $Nombre_Generico: Nombbre_Generico a validar
		 * @param $Categoria: Categoria a validar
		 */
		public function BuscaCodigo($Codigo = false, $Categoria = false) {
			
			if($Codigo == true AND $Categoria == true){
				$ConsultaSQL = new NeuralBDConsultas;
				$ConsultaSQL->CrearConsulta('tbl_clinica_info_farmacos');
				$ConsultaSQL->AgregarColumnas('Codigo, Categoria');
				$ConsultaSQL->AgregarCondicion("Codigo = '$Codigo'");
				$ConsultaSQL->AgregarCondicion("Categoria = '$Categoria'");
				$ConsultaSQL->PrepararCantidadDatos('Cantidad');
				$ConsultaSQL->PrepararQuery();
				return $ConsultaSQL->ExecuteConsulta('CLINICA');
			}
		}

		/**
		* Metodo Publico
		* BuscaFarmacoExiste($Codigo = false $Nombre_Comercial = false, $Nombre_Generico = false, $Categoria = false)
		*
		* Genera la consulta para establecer si los datos  entrantes existe en la base
		* @param $Codig: Codigo a validar
		* @param $Nombre_Comercial: Nombre_Comercial a validar
		* @param $Nombre_Generico: Nombbre_Generico a validar
		* @param $Categoria: Categoria a validar
		*/
		public function BuscaFarmacoExiste($Codigo = false, $Nombre_Comercial = false, $Nombre_Generico = false, $Categoria = false, $Precio_Venta = false, $Unidad = false, $Tipo_Unidad = false){
			if($Codigo == true AND $Nombre_Comercial == true AND $Nombre_Generico ==  true AND $Categoria == true AND $Precio_Venta == true AND $Unidad == true AND $Tipo_Unidad == true){
				$ConsultaSQL = new NeuralBDConsultas;
				$ConsultaSQL->CrearConsulta('tbl_clinica_info_farmacos');
				$ConsultaSQL->AgregarColumnas('Codigo, Nombre_Comercial, Nombre_Generico, Categoria');
				$ConsultaSQL->AgregarCondicion("Codigo = '$Codigo'");
				$ConsultaSQL->AgregarCondicion("Nombre_Comercial = '$Nombre_Comercial'");
				$ConsultaSQL->AgregarCondicion("Nombre_Generico = '$Nombre_Generico'");
				$ConsultaSQL->AgregarCondicion("Categoria = '$Categoria'");
				$ConsultaSQL->AgregarCondicion("Precio_Venta = '$Precio_Venta'");
				$ConsultaSQL->AgregarCondicion("Unidad = '$Unidad'");
				$ConsultaSQL->AgregarCondicion("Tipo_Unidad = '$Tipo_Unidad'");
				$ConsultaSQL->PrepararCantidadDatos('Cantidad');
				$ConsultaSQL->PrepararQuery();
				return $ConsultaSQL->ExecuteConsulta('CLINICA');
			}
		}
		
		/**
		 * Metodo Publico
		 * GuardarNuevosFarmaco($Array = false);
		 * 
		 * Guarda un farmaco
		 * @param $Array: array asociativo con los datos a guardar
		 * */
		public function GuardarNuevosFarmaco($Array = false) {

			if($Array == true AND is_array($Array) == true) {
				return self::GuardarDatos($Array, 'tbl_clinica_info_farmacos', array('Id','Status'), 'CLINICA');
			}
		}
		
		/**
		 * Metodo Publico
		 * ListadoFarmacos()
		 * 
		 * Genera el listado de farmacos de la tabla correspondientes
		 */
		public function ListadoFarmacos() {

			$Consulta = new NeuralBDConsultas;
			$Consulta->CrearConsulta('tbl_clinica_info_categorias');
			$Consulta->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_categorias', array('Status')));
			$Consulta->CrearConsulta('tbl_clinica_info_unidades');
			$Consulta->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_unidades', true, array('Descripcion' => 'Descripcion_Unidad')));
			$Consulta->CrearConsulta('tbl_clinica_info_farmacos');
			$Consulta->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_farmacos', true, array('Id' => 'Id_Farmacos')));
			$Consulta->AgregarCondicion("tbl_clinica_info_categorias.Id = tbl_clinica_info_farmacos.Categoria");
			$Consulta->AgregarCondicion("tbl_clinica_info_unidades.Id = tbl_clinica_info_farmacos.Tipo_Unidad");
			$Consulta->AgregarCondicion("tbl_clinica_info_farmacos.Status != 'ELIMINADO'");
			$Consulta->AgregarOrdenar('tbl_clinica_info_farmacos.Categoria', 'ASC');
			$Consulta->PrepararQuery();
			return $Consulta->ExecuteConsulta('CLINICA');
		}
		
		/**
		 * Metodo Publico
		 * ListadoFarmacosCategoria()
		 * 
		 * Genera el listado de farmacos por categoria
		 */
		public function ListadoFarmacosCategoria($Categoria = false) {

			if ($Categoria == true){
				$Consulta = new NeuralBDConsultas;
				$Consulta->CrearConsulta('tbl_clinica_info_categorias');
				$Consulta->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_categorias', array('Status')));
				$Consulta->CrearConsulta('tbl_clinica_info_unidades');
				$Consulta->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_unidades', true, array('Descripcion' => 'Descripcion_Unidad')));
				$Consulta->CrearConsulta('tbl_clinica_info_farmacos');
				$Consulta->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_farmacos', true, array('Id' => 'Id_Farmacos')));
				$Consulta->AgregarCondicion("tbl_clinica_info_farmacos.Categoria = '$Categoria'");
				$Consulta->AgregarCondicion("tbl_clinica_info_categorias.Id = tbl_clinica_info_farmacos.Categoria");
				$Consulta->AgregarCondicion("tbl_clinica_info_unidades.Id = tbl_clinica_info_farmacos.Tipo_Unidad");
				$Consulta->AgregarCondicion("tbl_clinica_info_farmacos.Status != 'ELIMINADO'");
				$Consulta->PrepararQuery();
				return $Consulta->ExecuteConsulta('CLINICA');
			}
		}

		/**
		 * Metodo Publico
		 * BuscarFarmacos($Id)
		 * 
		 * Genera la busqueda de farmaco segun ID
		 * @param $Id: Id a consultar
		 * @return array de datos
		 */
		public function BuscarFarmacos($Id = false) {
			
			if($Id == true)	{
				$ConsultaSQL = new NeuralBDConsultas;
				$ConsultaSQL->CrearConsulta('tbl_clinica_info_farmacos');
				$ConsultaSQL->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_farmacos', true, array('Id' => 'Id_Farmacos')));
				$ConsultaSQL->CrearConsulta('tbl_clinica_info_categorias');
				$ConsultaSQL->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_categorias', array('Status'), array('Id' => 'Id_Categoria')));
				$ConsultaSQL->CrearConsulta('tbl_clinica_info_unidades');
				$ConsultaSQL->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_unidades', true, array('Descripcion' => 'Descripcion_Unidad', 'Id' => 'Id_Unidades')));
				$ConsultaSQL->AgregarCondicion("tbl_clinica_info_categorias.Id = tbl_clinica_info_farmacos.Categoria");
				$ConsultaSQL->AgregarCondicion("tbl_clinica_info_unidades.Id = tbl_clinica_info_farmacos.Tipo_Unidad");
				$ConsultaSQL->AgregarCondicion("tbl_clinica_info_farmacos.Id='".$Id."'");
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
		public function ActualizarRegistro($Array = false, $Condicion = false, $Omitidos = false) {

			if(is_array($Array) == true AND is_array($Condicion) == true AND is_array($Omitidos) == true ) {
				return self::ActualizarDatos($Array, $Condicion, 'tbl_clinica_info_farmacos', $Omitidos, 'CLINICA');
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
		public function ActualizarEstado($Array = false, $Condicion = false, $Omitidos = false) {

			if(is_array($Array) == true AND is_array($Condicion) == true AND is_array($Omitidos) == true ) {
				return self::ActualizarDatos($Array, $Condicion, 'tbl_clinica_info_farmacos', $Omitidos, 'CLINICA');
			}
		}

		/**
		 * Metodo Publico
		 * Excel()
		 * 
		 * Exportar base de datos a excel
		 */
		public function Excel(){
			
			return self::DescargarDatosTablaExcel('Farmacos_'.strtotime(date("Y-m-d H:i:s")), 'tbl_clinica_info_farmacos');
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
