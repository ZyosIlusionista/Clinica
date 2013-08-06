<?php

	class AyudasArray {

		/**
		 * Metodo Publico
		 * Eliminar($Array = false, $Matriz = false);
		 * 
		 * Guarda Datos en la tabla seleccionada
		 * @param $Array: array asociativo con los datos
		 * @param $Matriz: array incrementa con los datos a eliminar
		 * 
		 * */
		public static function Eliminar($Array = false, $Matriz = false) {
				
			if ($Array == true AND is_array($Array) == true AND $Matriz == true AND is_array($Matriz) == true) {
				$Matriz = array_flip($Matriz);
				foreach($Array AS $Nombre => $Valor) {
					if(array_key_exists($Nombre, $Matriz) == true) {
						unset($Array[$Nombre]);
					}
				}
				return $Array;
			}
		}

		/**
		 * Metodo Publico
		 * VerificaEstado($Estado = false)
		 *
		 * Verificar el estado del usuario
		 * @param $Estado: Estado debe corresponder a los estados del usuario
		 */
		public static function VerificaEstado($Estado = false) {

			if($Estado == true) {
				$Matriz = array_flip(array('ACTIVO', 'INACTIVO', 'SUSPENDIDO', 'ELIMINADO'));
				return (array_key_exists($Estado, $Matriz) == true) ? true : false;
			}
		}

		/**
		 * Metodo Publico
		 * FormatoMultiregistros($MultiArray = false, $Omitidos = false)
		 *
		 * Toma un Array multidimensional y aplica funcion ucwords y omite los key ingresados
		 * @param $Array: array de datos formatear
		 * @param $Omitidos: array de campos a omitir
		 */
		public static function FormatoMultiregistros($MultiArray = false, $Omitidos = false) {

			if($MultiArray == true AND is_array($MultiArray) == true AND $Omitidos == true AND is_array($Omitidos) == true) {
				$Matriz = (is_array($Omitidos) == true) ? array_flip($Omitidos) : array();
				foreach ($MultiArray as $key => $Array) {
					foreach ($Array AS $Llave => $Valor) {
						$Lista[$key][trim($Llave)] = (array_key_exists($Llave, $Matriz) == true) ? trim($Valor) : trim(ucwords(strtolower($Valor)));
					}
				}
				return $Lista;
			}
		}

	}