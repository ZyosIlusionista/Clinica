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
		public static function VerificaEstado($Estado = false){

			if($Estado == true) {
				$Matriz = array_flip(array('ACTIVO', 'INACTIVO', 'SUSPENDIDO', 'ELIMINADO'));
				return (array_key_exists($Estado, $Matriz) == true) ? true : false;
			}
		}


	}