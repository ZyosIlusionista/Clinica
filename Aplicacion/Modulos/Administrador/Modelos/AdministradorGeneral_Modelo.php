<?php
	
	class AdministradorGeneral_Modelo extends AyudasSQLConsultas {
		
		function __Construct() {
			parent::__Construct();
		}
		
		/**
		 * Metodo Publico
		 * GuardarNuevosDatosAutentificacion($Array = false)
		 * 
		 * Guarda los datos del usuario en la base
		 * @param $Array: array de los datos del usuario
		 */
		public function GuardarNuevosDatosAutentificacion($Array = false) {
			
			if($Array == true AND is_array($Array) == true) {
				$SQL = new NeuralBDGab;
				$SQL->SeleccionarDestino('CLINICA', 'tbl_sistema_usuarios');
				foreach ($Array AS $Columna => $Valor) {
					$SQL->AgregarSentencia($Columna, $Valor);
				}
				$SQL->AgregarSentencia('Perfil', '2');
				$SQL->AgregarSentencia('Estado', 'ACTIVO');
				$SQL->InsertarDatos();
			}
		}

		/**
		 * Metodo Publico
		 * GuardarNuevosDatosGenerales($Array = false);
		 * 
		 * Guarda los Datos Generales del nuevo administrados
		 * @param $Array: array asociativo con los datos a guardar
		 * */
		public function GuardarNuevosDatosGenerales($Array = false){

			if($Array == true AND is_array($Array) == true) {
				$SQL = new NeuralBDGab;
				$SQL->SeleccionarDestino('CLINICA', 'tbl_sistema_info_usuarios');
				foreach ($Array AS $Columna => $Valor) {
					$SQL->AgregarSentencia($Columna, $Valor);
				}
				$SQL->InsertarDatos();
			}
		}

		/**
		 * Metodo Publico
		 * BuscaUsuarioExiste($Usuario = false)
		 * 
		 * Genera la consulta para establecer si el usuario existe en la base
		 * @param $Usuario: Usuario a validar
		 */
		public function BuscaUsuarioExiste($Usuario = false){

			$ConsultaSQL = new NeuralBDConsultas;
			$ConsultaSQL->CrearConsulta('tbl_sistema_usuarios');
			$ConsultaSQL->AgregarColumnas('Usuario');
			$ConsultaSQL->AgregarCondicion("Usuario = '$Usuario'");
			$ConsultaSQL->PrepararCantidadDatos('Cantidad');
			$ConsultaSQL->PrepararQuery();
			return $ConsultaSQL->ExecuteConsulta('CLINICA');
		}

		/**
		 * Metodo Publico
		 * ListadoAdministradoresGenerales()
		 * 
		 * Genera el listado de usuarios de la tabla correspondientes
		 */
		public function ListadoAdministradoresGenerales(){
			
			$Consulta = new NeuralBDConsultas;
			$Consulta->CrearConsulta('tbl_sistema_usuarios');
			$Consulta->AgregarColumnas(self::ListarColumnas('tbl_sistema_usuarios'));
			$Consulta->CrearConsulta('tbl_sistema_info_usuarios');
			$Consulta->AgregarColumnas(self::ListarColumnas('tbl_sistema_info_usuarios', array('Id')));
			$Consulta->AgregarCondicion("tbl_sistema_usuarios.Perfil = '2'");
			$Consulta->AgregarCondicion("tbl_sistema_usuarios.Id = tbl_sistema_info_usuarios.Id");
			$Consulta->PrepararQuery();
			return $Consulta->ExecuteConsulta('CLINICA');
		}

		/**
		 * Metodo Publico
		 * BuscarAdministradorGeneral($Id)
		 * 
		 * Genera la busqueda de usuario segun ID
		 * @param $Id: Id a consultar
		 * @return array de datos
		 */
		public function BuscarAdministradorGeneral($Id){

			$ConsultaSQL = new NeuralBDConsultas;
			$ConsultaSQL->CrearConsulta('tbl_sistema_info_usuarios');
			$ConsultaSQL->AgregarColumnas(self::ListarColumnas('tbl_sistema_info_usuarios', array('Ciudad', 'Direccion_Contacto', 'Sucursales', 'Tipo_Perfil', 'Cedula_Profesional', 'Especialidad', 'Titulo')));
			$ConsultaSQL->CrearConsulta('tbl_sistema_usuarios');
			$ConsultaSQL->AgregarColumnas(self::ListarColumnas('tbl_sistema_usuarios', true, array('Id' => 'Id_Usuario')));
			$ConsultaSQL->AgregarCondicion("tbl_sistema_usuarios.Id = tbl_sistema_info_usuarios.Id");
			$ConsultaSQL->AgregarCondicion("tbl_sistema_info_usuarios.Id = '$Id'");
			$ConsultaSQL->PrepararQuery();
			return $ConsultaSQL->ExecuteConsulta('CLINICA');
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
				return self::ActualizarDatos($Array, $Condicion, 'tbl_sistema_usuarios', $Omitidos, 'CLINICA');
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
				return self::ActualizarDatos($Array, $Condicion, 'tbl_sistema_info_usuarios', $Omitidos, 'CLINICA');
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