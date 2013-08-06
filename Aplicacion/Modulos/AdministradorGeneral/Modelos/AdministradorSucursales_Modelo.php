<?php
	
	class AdministradorSucursales_Modelo extends AyudasSQLConsultas {
		
		function __Construct() {
			parent::__Construct();
		}
		
		/**
		 * Metodo Publico
		 * GuardarNuevosDatosAutentificacion($Array = false)
		 *
		 * Guarda los datos del nuevo usuario
		 * @param $Array: Arreglo de datos
		 */
		public function GuardarNuevosDatosAutentificacion($Array = false) {
			
			/* Valida que exista el arreglo de datos */
			if($Array == true AND is_array($Array) == true) {
				
				/* Guarda la infomacion en la base de datos */
				return self::GuardarDatos($Array, 'tbl_sistema_usuarios', array('Id', 'Estado'), 'CLINICA');
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

			/* Valida que exista el arreglo de datos */
			if($Array == true AND is_array($Array) == true) {
			
				/* Guarda la infomacion en la base de datos */
				return self::GuardarDatos($Array, 'tbl_sistema_info_usuarios', array('Id', 'Ciudad', 'Direccion_Contacto', 'Sucursales', 'Tipo_Perfil', 'Cedula_Profesional', 'Especialidad', 'Titulo', 'Actualizacion', 'Aseguradora'), 'CLINICA');
			}
		}

		/**
		 * Metodo Publico
		 * BuscaUsuarioExiste($Usuario = false)
		 *
		 * Verifica si el usuario existe en la base de datos
		 * @param $Usuario: nombre del usuario
		 */
		public function BuscaUsuarioExiste($Usuario = false) {

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
		 * ListadoAdministradoresSucursales()
		 *
		 * Lista los administradores de sucursales
		 */
		public function ListadoAdministradoresSucursales() {

			$Consulta = new NeuralBDConsultas;
			$Consulta->CrearConsulta('tbl_sistema_usuarios');
			$Consulta->AgregarColumnas(self::ListarColumnas('tbl_sistema_usuarios', array('Password'), array('Id' => 'Id_Usuario')));
			$Consulta->CrearConsulta('tbl_sistema_info_usuarios');
			$Consulta->AgregarColumnas(self::ListarColumnas('tbl_sistema_info_usuarios', array('Direccion_Contacto', 'Ciudad', 'Sucursales', 'Tipo_Perfil', 'Cedula_Profesional', 'Especialidad', 'Titulo', 'Actualizacion', 'Aseguradora', 'Sucursal'), array('Id' => 'Id_Informacion')));
			$Consulta->CrearConsulta('tbl_sistema_usuario_perfil');
			$Consulta->AgregarColumnas(self::ListarColumnas('tbl_sistema_usuario_perfil', array('Estado'), array('Id' => 'Id_Perfil')));
			$Consulta->AgregarCondicion("tbl_sistema_usuarios.Usuario = tbl_sistema_info_usuarios.Usuario");
			$Consulta->AgregarCondicion("tbl_sistema_usuario_perfil.Nombre = 'Administrador Local'");
			$Consulta->AgregarCondicion("tbl_sistema_usuarios.Perfil = tbl_sistema_usuario_perfil.Id");
			$Consulta->AgregarCondicion("tbl_sistema_usuarios.Estado != 'ELIMINADO'");
			$Consulta->PrepararQuery();
			return $Consulta->ExecuteConsulta('CLINICA');
		}

		/**
		 * Metodo Publico
		 * BuscarAdministradorSucursales($Id)
		 *
		 * Busca los datos del administrador de sucursales
		 * @param $Id: Id del usuario
		 */
		public function BuscarAdministradorSucursales($Id) {

			$ConsultaSQL = new NeuralBDConsultas;
			$ConsultaSQL->CrearConsulta('tbl_sistema_info_usuarios');
			$ConsultaSQL->AgregarColumnas(self::ListarColumnas('tbl_sistema_info_usuarios', array('Ciudad', 'Direccion_Contacto', 'Sucursales', 'Tipo_Perfil', 'Cedula_Profesional', 'Especialidad', 'Titulo')));
			$ConsultaSQL->CrearConsulta('tbl_sistema_usuarios');
			$ConsultaSQL->AgregarColumnas(self::ListarColumnas('tbl_sistema_usuarios', array('Password', 'Perfil'), array('Id' => 'Id_Usuario')));
			$ConsultaSQL->AgregarCondicion("tbl_sistema_info_usuarios.Id = '$Id'");
			$ConsultaSQL->AgregarCondicion("tbl_sistema_info_usuarios.Usuario = tbl_sistema_usuarios.Usuario");
			$ConsultaSQL->PrepararQuery();
			return $ConsultaSQL->ExecuteConsulta('CLINICA');
		}

		/**
		 * Metodo Publico
		 * ListarSucursales($Estado = false)
		 * 
		 * Genera el listado de las sucursales correspondientes
		 * dependiendo del tipo de estado
		 */
		public function ListarSucursales($Estado = false) {
			if($Estado == true) {
				$Consulta = new NeuralBDConsultas;
				$Consulta->CrearConsulta('tbl_clinica_info_sucursales');
				$Consulta->AgregarColumnas('Id, Nombre');
				$Consulta->AgregarCondicion("Estado = '$Estado'");
				$Consulta->PrepararCantidadDatos('Cantidad');
				$Consulta->PrepararQuery();
				return $Consulta->ExecuteConsulta('CLINICA');
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
				return self::ActualizarDatos($Array, $Condicion, 'tbl_sistema_info_usuarios', $Omitidos, 'CLINICA');
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
				return self::ActualizarDatos($Array, $Condicion, 'tbl_sistema_usuarios', $Omitidos, 'CLINICA');
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