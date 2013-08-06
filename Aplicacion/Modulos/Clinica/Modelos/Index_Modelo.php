<?php
	
	class Index_Modelo extends Modelo {
		
		function __Construct() {
			parent::__Construct();
		}
		
		/**
		 * Metodo Publico
		 * ConsultarUsuario($Usuario = false, $Password = false)
		 * 
		 * Consulta los datos del usuario
		 * retorna un array asociativo con los datos correspondientes
		 */
		public function ConsultarUsuario($Usuario = false, $Password = false) {
			
			if($Usuario == true AND $Password == true) {
				$Consulta = new NeuralBDConsultas;
				$Consulta->CrearConsulta('tbl_sistema_usuarios');
				$Consulta->AgregarColumnas(self::ListarColumnasTabla('tbl_sistema_usuarios', array('Id', 'Password')));
				$Consulta->CrearConsulta('tbl_sistema_info_usuarios');
				$Consulta->AgregarColumnas(self::ListarColumnasTabla('tbl_sistema_info_usuarios', array('Id')));
				$Consulta->AgregarCondicion("tbl_sistema_usuarios.Usuario = '$Usuario'");
				$Consulta->AgregarCondicion("tbl_sistema_usuarios.Password = '$Password'");
				$Consulta->AgregarCondicion("tbl_sistema_usuarios.Estado = 'ACTIVO'");
				$Consulta->AgregarCondicion("tbl_sistema_usuarios.Id = tbl_sistema_info_usuarios.Id");
				$Consulta->PrepararCantidadDatos('Cantidad');
				$Consulta->PrepararQuery();
				return $Consulta->ExecuteConsulta('CLINICA');
			}
		}
		
		/**
		 * Metodo Publico
		 * ConsultarPermisos($Permisos = false)
		 * 
		 * Genera la consulta de los datos correspondientes
		 */
		public function ConsultarPermisos($Permisos = false) {
			if($Permisos == true AND is_numeric($Permisos) == true) {
				$Consulta = new NeuralBDConsultas;
				$Consulta->CrearConsulta('tbl_sistema_usuario_perfil');
				$Consulta->AgregarColumnas(self::ListarColumnasTabla('tbl_sistema_usuario_perfil', array('Id', 'Estado')));
				$Consulta->AgregarCondicion("Id = '$Permisos'");
				$Consulta->PrepararCantidadDatos('Cantidad');
				$Consulta->PrepararQuery();
				return $Consulta->ExecuteConsulta('CLINICA');
			}
		}
		
		/**
		 * Metodo Privado
		 * ListarColumnas($Tabla = false, $Omitidos = false)
		 * 
		 * @param $Alias: es un array asociativo
		 * @example array('Columna' => 'Alias')
		 */
		private function ListarColumnasTabla($Tabla = false, $Omitidos = false, $Alias = false) {
			
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