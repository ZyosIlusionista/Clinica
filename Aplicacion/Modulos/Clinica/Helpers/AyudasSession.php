<?php
	
	class AyudasSession {
		
		private static $_LlaveUsuario = 'G0vEFMEd99DBcjJrk6dtasl4w1Y=';
		private static $_LlavePermiso = 'pmMq6ciSmrAsJC69yJSCqrAqvRk=';
		private static $_TIEMPO_LOGIN = 3600;
		const APP_APP = 'APP';
		const APP_ARRAY = 'ARRAY';

		/**
		 * Metodo Publico
		 * MostrarDatosSession()
		 * 
		 * entrega los datos de sesion para ver visualizados
		 * 
		 * */
		public static function MostrarDatosSession() {
			if(isset($_SESSION['UOAUTH']) == true AND isset($_SESSION['POAUTH']) == true) {
				$Data['Permisos'] = self::JsonDecoder(self::Decodificar($_SESSION['POAUTH'], self::APP_APP));
				$Data['Usuario'] = self::Decodificar($_SESSION['Usuario'], self::APP_ARRAY);
				$Data['Nombre'] = self::Decodificar($_SESSION['Nombre'], self::APP_ARRAY);
				$Data['Correo'] = self::Decodificar($_SESSION['Correo'], self::APP_ARRAY);
				$Data['Ciudad'] = self::Decodificar($_SESSION['Ciudad'], self::APP_ARRAY);
				$Data['Perfil'] = self::Decodificar($_SESSION['Perfil'], self::APP_ARRAY);
				return $Data;
			}
			else {
				self::RedireccionLogOut();
				exit();
			}
		}

		// -- INICIO DE LAS FUNCIONES DE VALIDACION
	
		public static function ValSessionGlobal() {
			NeuralSesiones::Inicializacion();
			if(isset($_SESSION['UOAUTH']) == true AND isset($_SESSION['POAUTH']) == true) {
				if(self::ValLlaveUsuario($_SESSION['UOAUTH']) == true AND self::ValLlavePermiso($_SESSION['POAUTH']) == true) {
					// -- Validamos el Acceso al Modulo
					if(self::ValModuloPermisos($_SESSION['POAUTH']) == false) {
						header("Location: ".NeuralRutasApp::RutaURLBase('Error/SinPermisos'));
						exit();
					}
				}
				else {
					self::RedireccionLogOut();
					exit();
				}
			}
			else {
				self::RedireccionLogOut();
				exit();
			}
		}
		
		/**
		 * Metodo Privado
		 * ValModuloPermisos($Cadena = false)
		 * 
		 * Genera la validacion si es permitido el acceso
		 * @param $Cadena: cadena de session donde se validara la matriz de permisos
		 * 
		 */
		private static function ValModuloPermisos($Cadena = false) {
			if($Cadena == true) {
				$ModRewrite = SysNeuralNucleo::LeerURLModReWrite();
				$Modulo = $ModRewrite[0];
				$Matriz = self::JsonDecoder(self::Decodificar($Cadena, self::APP_APP));
				$MatrizBase = self::JsonDecoder(self::Decodificar($Matriz['Matriz'], self::APP_ARRAY));
				if(array_key_exists($Modulo, $MatrizBase) == true) {
					$MatrizModulo = self::JsonDecoder($MatrizBase[$Modulo]);
					if($MatrizModulo['Activo'] == true) {
						return true;
					}
					else {
						return false;
					}
				}
				else {
					return false;
				}
				
			}
		}
		
		/**
		 * Metodo Privado
		 * ValLlavePermiso($Cadena = false)
		 * 
		 * Valida si los datos de la session son correctos
		 * @param $Cadena: cadena de datos donde se encuentran los datos de la session
		 */
		private static function ValLlavePermiso($Cadena = false) {
			if($Cadena == true) {
				$Matriz = self::JsonDecoder(self::Decodificar($Cadena, self::APP_APP));
				return (self::Decodificar($Matriz['OAUTH'], self::APP_APP) == self::RegistrarLlaveValidacion(self::$_LlavePermiso, date("Y-m-d"))) ? true : false;
			}
		}
		
		/**
		 * Metodo Privado
		 * ValLlaveUsuario($Cadena = false)
		 * 
		 * Valida si los datos de la session son correctos
		 * @param $Cadena: cadena de datos donde se encuentran los datos de la session
		 */
		private static function ValLlaveUsuario($Cadena = false) {
			if($Cadena == true) {
				$Matriz = self::JsonDecoder(self::Decodificar($Cadena, self::APP_APP));
				$Validacion[] = (self::Decodificar($Matriz['OAUTH'], self::APP_APP) == self::RegistrarLlaveValidacion(self::$_LlaveUsuario, date("Y-m-d"))) ? '0' : '1';
				$Validacion[] = (self::Decodificar($Matriz['Fecha'], self::APP_ARRAY)>= strtotime(date("Y-m-d H:i:s"))) ? '0' : '1';
				return (array_sum($Validacion)>=1) ? false : true;
			}
		}
		
		/**
		 * Metodo Privado
		 * RedireccionLogOut()
		 * 
		 * Genera la redireccion al logout
		 */
		private static function RedireccionLogOut() {
			header("Location: ".NeuralRutasApp::RutaURLBase('Clinica/LogOut'));
			exit();
		}

		// -- INICIO DE FUNCIONES DE REGISTRO A LA APLICACION
		
		/**
		 * Metodo Publico
		 * RegistrarSession($DatosUsuario = false, $PermisoUsuario = false)
		 * 
		 * Genera el Registro de la session del usuario
		 * @param $DatosUsuario: array con los datos del usuario
		 * @param $PermisoUsuario: array con los permisos del usuario
		 */
		public static function RegistrarSession($DatosUsuario = false, $PermisoUsuario = false) {
			if($DatosUsuario == true AND is_array($DatosUsuario) == true AND $PermisoUsuario == true AND is_array($PermisoUsuario) == true) {
				NeuralSesiones::Inicializacion();
				NeuralSesiones::AgregarLlave('UOAUTH', self::RegistrarUOAUTH($DatosUsuario));
				NeuralSesiones::AgregarLlave('POAUTH', self::RegistrarPOAUTH($PermisoUsuario));
				NeuralSesiones::AgregarLlave('Usuario', self::Codificar($DatosUsuario['Usuario'], self::APP_ARRAY));
				NeuralSesiones::AgregarLlave('Nombre', self::Codificar($DatosUsuario['Nombre_Primero'].' '.$DatosUsuario['Apellido_Primero'], self::APP_ARRAY));
				NeuralSesiones::AgregarLlave('Correo', self::Codificar($DatosUsuario['Correo'], self::APP_ARRAY));
				NeuralSesiones::AgregarLlave('Ciudad', self::Codificar($DatosUsuario['Ciudad'], self::APP_ARRAY));
				NeuralSesiones::AgregarLlave('Perfil', self::Codificar($DatosUsuario['Tipo_Perfil'], self::APP_ARRAY));
			}
		}
		
		/**
		 * Metodo Privado
		 * RegistrarPOAUTH($Array = false)
		 * 
		 * Genera el array de datos de la session
		 * @param $Array: array de datos a registrar
		 */
		private static function RegistrarPOAUTH($Array = false) {
			if($Array == true AND is_array($Array) == true) {
				$Schema['OAUTH'] = self::Codificar(self::RegistrarLlaveValidacion(self::$_LlavePermiso, date("Y-m-d")), self::APP_APP);
				$Schema['Permiso'] = self::Codificar($Array['Nombre'], self::APP_ARRAY);
				$Schema['Matriz'] = self::Codificar(self::JsonEncoder($Array), self::APP_ARRAY);
				return self::Codificar(self::JsonEncoder($Schema), self::APP_APP);
			}
		}
		
		/**
		 * Metodo Privado
		 * RegistrarOAUTHUsuario($Array = false)
		 * 
		 * Genera el array de datos de la session
		 * @param $Array: array de datos a registrar
		 */
		private static function RegistrarUOAUTH($Array = false) {
			if($Array == true AND is_array($Array) == true) {
				$Schema['OAUTH'] = self::Codificar(self::RegistrarLlaveValidacion(self::$_LlaveUsuario, date("Y-m-d")), self::APP_APP);
				$Tiempo = strtotime(date("Y-m-d H:i:s"))+self::$_TIEMPO_LOGIN;
				$Schema['Fecha'] = self::Codificar($Tiempo, self::APP_ARRAY);
				$Schema['Usuario'] = self::Codificar($Array['Usuario'], self::APP_APP);
				$Schema['Actualizar'] = self::Codificar($Array['Actualizacion'], self::APP_ARRAY);
				return self::Codificar(self::JsonEncoder($Schema), self::APP_APP);
			}
		}

		// -- INICIO DE AYUDAS DE LA APLICACION		

		/**
		 * Metodo Privado
		 * RegistrarLlaveValidacion($Llave = false, $Pin = false)
		 * 
		 * Genera las Llaves de Validacion correspondientes
		 * @param $Llave: Llave inicial de validacion
		 * @param $Pin: Pin adicional de validacion
		 */
		private static function RegistrarLlaveValidacion($Llave = false, $Pin = false) {
			if($Llave == true AND $Pin == true) {
				return $Llave.'_'.$Pin.'_'.date("Y-m-d");
			}
		}
		
		/**
		 * Metodo Privado
		 * Codificar($Cadena, $Tipo = false)
		 * 
		 * Genera la Codificacion de los datos correspondientes
		 * @param $Cadena: Cadena de datos
		 * @param $Tipo: puede ser APP codificacion aplicacion o ARRAY generando la codificacion
		 */
		private static function Codificar($Cadena = false, $Tipo = false) {
			if($Cadena == true AND $Tipo == true) {
				return ($Tipo == self::APP_APP) ? NeuralEncriptacion::EncriptarDatos($Cadena, 'CLINICA') : ($Tipo == self::APP_ARRAY) ? NeuralEncriptacion::EncriptarDatos($Cadena, array(date("Y-m-d"), 'CLINICA')) : false;
			}
		}
		
		/**
		 * Metodo Privado
		 * Decodificar($Cadena, $Tipo = false)
		 * 
		 * Genera la Decodificacion de los datos correspondientes
		 * @param $Cadena: Cadena de datos
		 * @param $Tipo: puede ser APP codificacion aplicacion o ARRAY generando la codificacion
		 */
		private static function Decodificar($Cadena = false, $Tipo = false) {
			if($Cadena == true AND $Tipo == true) {
				return ($Tipo == self::APP_APP) ? NeuralEncriptacion::DesencriptarDatos($Cadena, 'CLINICA') : ($Tipo == self::APP_ARRAY) ? NeuralEncriptacion::DesencriptarDatos($Cadena, array(date("Y-m-d"), 'CLINICA')) : false;
			}
		}
		
		/**
		 * Metodo Privado
		 * JsonEncoder($Array = false)
		 * 
		 * Convierte un array en formato JSON
		 * @param $Array: array a convertir
		 */
		private static function JsonEncoder($Array = false) {
			if($Array == true AND is_array($Array) == true) {
				return json_encode($Array);
			}
		}
		
		/**
		 * Metodo Privado
		 * JsonDecoder($Cadena = false)
		 * 
		 * Convierte una cadena JSON en array
		 * @param $Cadena: cadena a convertir
		 */
		private static function JsonDecoder($Cadena = false) {
			if($Cadena == true) {
				return json_decode($Cadena, true);
			}
		}
	}