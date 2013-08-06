<?php

	class AyudasCopyPasteExcelArray {
		
		/**
		 * Metodo Publico
		 * ConvertirExcelArrayColumnas($Texto = false, $Columnas = false)
		 * 
		 * Convierte el texto copiado desde excel a un input en un array
		 * Solo aplica para datos en celdas que sean de una sola linea
		 * @param $Texto: texto a formatear
		 * @param $Columnas: array de columnas que se mostraran
		 * @example array('Columna', 'Columna', 'Columna', 'Columna')
		 */
		public static function ConvertirExcelArrayColumnas($Texto = false, $Columnas = false) {
			if($Texto == true AND $Columnas == true AND is_array($Columnas) == true) {
				return self::ConvertirArrayColumnasNombre(self::ConvertirArrayFilas($Texto), $Columnas);
			}
		}
		
		/**
		 * Metodo Publico
		 * ConvertirExcelArray($Texto = false)
		 * 
		 * Convierte el texto copiado desde excel a un input en un array
		 * Solo aplica para datos en celdas que sean de una sola linea
		 * @param $Texto: texto a formatear
		 */
		public static function ConvertirExcelArray($Texto = false) {
			if($Texto == true) {
				return self::ConvertirArrayColumnas(self::ConvertirArrayFilas($Texto));
			}
		}
		
		private static function ConvertirArrayColumnasNombre($Array = false, $Columnas = false) {
			if($Array == true AND is_array($Array) == true AND $Columnas == true AND is_array($Columnas) == true) {
				$Cantidad = count($Array);
				for ($i=0; $i<$Cantidad; $i++) {
					$Datos = explode("\t", trim($Array[$i]));
					foreach ($Datos AS $Columna => $Valor) {
						if(array_key_exists($Columna, $Columnas) == true) {
							$NuevaColumna[trim($Columnas[$Columna])] = trim($Valor);
						}
					}
					$Lista[] = $NuevaColumna;
				}
				unset($Array, $Cantidad, $Columnas, $Columna, $NuevaColumna, $Valor, $i, $Datos);
				return $Lista;
			}
		}
		
		private static function ConvertirArrayColumnas($Array = false) {
			if($Array == true AND is_array($Array) == true) {
				$Cantidad = count($Array);
				function EliminarEspacios($Parametro = false) { return trim($Parametro); }
				for ($i=0; $i<$Cantidad; $i++) {
					$Datos = explode("\t", trim($Array[$i]));
					foreach ($Datos AS $Columna => $Valor) {
						$NuevaColumna[$Columna] = trim($Valor);
					}
					$Lista[] = $NuevaColumna;
				}
				unset($Array, $Cantidad, $Columna, $Datos, $NuevaColumna, $Valor, $Columna);
				return $Lista;
			}
		}
		
		private static function ConvertirArrayFilas($Texto = false) {
			if($Texto == true) {
				return explode("\n", trim($Texto));
			}
		}
		
	}