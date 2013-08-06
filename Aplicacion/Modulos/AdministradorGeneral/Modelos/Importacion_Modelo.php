<?php
	
	class Importacion_Modelo extends AyudasSQLConsultas {
		
		function __Construct() {
			parent::__Construct();
			$this->Conexion = NeuralConexionBaseDatos::ObtenerConexionBase('CLINICA'); 
		}
		
		/**
		 * Metodo Publico
		 * BuscaCategoria()
		 * 
		 * Genera la consulta para establecer si la lista de categoria
		 */
		public function BuscarCategoria() {

			$Consulta = new NeuralBDConsultas;
			$Consulta->CrearConsulta('tbl_clinica_info_categorias');
			$Consulta->AgregarColumnas(self::ListarColumnas('tbl_clinica_info_categorias', array('Status')));
			$Consulta->AgregarCondicion("Status != 'ELIMINADO'");
			$Consulta->PrepararQuery();
			return $Consulta->ExecuteConsulta('CLINICA');
		}
		
		/**
		 * Metodo Publico
		 * BuscaGuardaDatosExcel($Array = false, $Id = false)
		 * 
		 * Guardar los datos traidos del Excel en la tabla de farmacos
		 * @param $Array: array asociativo con los datos a guardar
		 * @param $Id: Valida a la categoria que pertenece
		 */
		public function GuardarDatosExcel($Array = false, $Id = false) {
			
			if($Array == true AND is_array($Array) == true AND $Id == true AND is_numeric($Id) == true) {
				foreach($Array AS $Columna => $Valor) {
					$Conexion = $this->Conexion;
					$Consulta = $Conexion->prepare("SELECT Codigo FROM tbl_clinica_info_farmacos WHERE Codigo = '".$Valor['Codigo']."' AND Nombre_Comercial ='".$Valor['Nombre_Comercial']."' AND Categoria = '".$Id."' ");
					$Consulta->execute();
					if($Consulta->rowCount() == 0) {
						$ConsultaSQL = $Conexion->prepare("INSERT INTO tbl_clinica_info_farmacos (Codigo, Nombre_Comercial, Nombre_Generico, Categoria, Precio_Venta, Unidad, Tipo_Unidad) values ('".$Valor['Codigo']."', '".$Valor['Nombre_Comercial']."','".$Valor['Nombre_Generico']."', '".$Id."', '".$Valor['Precio_Venta']."', '".$Valor['Unidad']."', '".$Valor['Tipo_Unidad']."')");
						$ConsultaSQL->execute();		
	 				}
				}
			}
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
	