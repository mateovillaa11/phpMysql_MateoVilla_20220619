<?php

	require_once("modelos/generico.php");

	class envios extends generico{

		protected $id;
		
		protected $CI_clientes;
		
		protected $destinatario;

		protected $id_departamentos;

        protected $calle;

        protected $puerta;

		protected $fechaRecibido;

        protected $id_estado;


		
		public function traerIdEnvio(){
			return $this->id;
		}

		public function traerCiCliente(){
			return $this->CI_cliente;
		}

		public function traerDestinatario(){
			return $this->destinatario;
		}

		public function traerId_departamentos(){
			return $this->id_departamentos;
		}

        public function traerCalle(){
			return $this->calle;
		}
        public function traerPuerta(){
			return $this->puerta;
		}
        public function traerFechaRecibido(){
			return $this->FechaRecibido;
		}
        public function traerIdEstado(){
			return $this->id_estado;
		}
		

		public function constructor($arrayDatos = array()) {

			$this->id				= $this->extarerDatos($arrayDatos,'id');

			$this->CI_cliente		= $this->extarerDatos($arrayDatos,'CI_cliente');

			$this->destinatario		= $this->extarerDatos($arrayDatos,'destinatario');

			$this->id_departamentos	= $this->extarerDatos($arrayDatos,'id_departamentos');

            $this->calle			= $this->extarerDatos($arrayDatos,'calle');

            $this->puerta			= $this->extarerDatos($arrayDatos,'puerta');

            $this->FechaRecibido	= $this->extarerDatos($arrayDatos,'FechaRecibido');

            $this->id_estado		= $this->extarerDatos($arrayDatos,'id_estado');

		}

		public function cargarEnvios($id){

			$sql= "SELECT * FROM envios WHERE id=:id";

			$arrayDatos = array();
			$arrayDatos['id'] = $id;
			$respuesta= $this->cargarDatos($sql,$arrayDatos);
			
			foreach($respuesta as $envios ){

				$this->id		        = $envios['id'];

				$this->CI_cliente	    = $envios['CI_cliente'];

				$this->destinatario 	= $envios['destinatario'];

				$this->id_departamentos	= $envios['id_departamentos'];

                $this->calle            = $envios['calle'];

                $this->puerta           = $envios['puerta'];

                $this->FechaRecibido    = $envios['FechaRecibido'];

                $this->id_estado        = $envios['id_estado'];
			}

		}

		public function listarEnvios($filtros= array()){

			$sql= "SELECT 	envios.id,
							envios.CI_cliente 'CI_cliente',
							envios.destinatario,
							departamentos.nombre'departamentos',
							envios.calle ,
							envios.puerta,
							envios.FechaRecibido,
							estado.nombre 'EstadoPaquete'
					FROM envios 
							inner join departamentos on departamentos.id  = envios.id_departamentos
							inner join cliente on cliente.CI  = envios.CI_cliente 
							inner join estado on estado.id = envios.id_estado 
							where envios.estado = 1"; 
			

			if(isset($filtros['busqueda']) && $filtros['busqueda'] != ""){
			$sql .= " AND (envios.destinatario LIKE ('%".$filtros['busqueda']."%')";
			$sql .= " OR estado.nombre LIKE ('%".$filtros['busqueda']."%')";
			$sql .= " OR  departamentos.nombre LIKE ('%".$filtros['busqueda']."%'))";
			}

			if(isset ($filtros['totalRegistros']) && $filtros['totalRegistros']>0){

				$origen = ($filtros['pagina'] -1) * $filtros['totalRegistros'];

				$sql .= " LIMIT ".$origen.",".$filtros["totalRegistros"];
			}

            $arraySql = array();
            $retorno = $this->cargarDatos($sql,$arraySql);
			return $retorno;
		}

		public function totalEnvios($filtros= array()){

			$sql= "SELECT COUNT(id) AS total FROM envios
					WHERE estado = 1"; 

			if(isset($filtros['busqueda']) && $filtros['busqueda'] != ""){
				$sql .= " AND (ci_cliente LIKE ('%".$filtros['busqueda']."%')";
				$sql .= " OR destinatario LIKE ('%".$filtros['busqueda']."%'))";
			}

            $arraySql = array();
			$retorno = 0;

            $respuesta = $this->cargarDatos($sql,$arraySql);
			foreach($respuesta as $total){
				$retorno = $total['total'];
			}

			return $retorno;
		}

		public function ingresarEnvios() {

			$sqlInsert = "INSERT envios SET
							CI_cliente	        = :CI_cliente,
							destinatario 	    = :destinatario,
							id_departamentos 	= :id_departamentos,
                            calle               =:calle,
                            puerta              =:puerta,
                            FechaRecibido       =:FechaRecibido,
                            id_estado           =:id_estado,
							estado 		        = 1";


			$arraySql = array(
							"CI_cliente" 	    => $this->CI_cliente,
							"destinatario"  	=> $this->destinatario,
							"id_departamentos" 	=> $this->id_departamentos,
                            "calle" 	        => $this->calle,
                            "puerta" 	        => $this->puerta,
                            "FechaRecibido" 	=> $this->FechaRecibido,
                            "id_estado" 	    => $this->id_estado
						);

            $retorno = $this->imputarCambio($sqlInsert,$arraySql);

			return $retorno;

		}

		public function editarEnvios() {

			$sqlInsert = "UPDATE envios SET
							CI_cliente 		 = :CI_cliente,
							destinatario 	 = :destinatario,
							id_departamentos = :id_departamentos,
                            calle  			 = :calle,
                            puerta   	 	 = :puerta,
							FechaRecibido    = :FechaRecibido,
							id_estado    	 = :id_estado
							WHERE id		 = :id";

			$arraySql = array(
							"id" 	    		=> $this->id,
							"CI_cliente" 	    => $this->CI_cliente,
							"destinatario"  	=> $this->destinatario,
							"id_departamentos" 	=> $this->id_departamentos,
							"calle" 	        => $this->calle,
							"puerta" 	        => $this->puerta,
							"FechaRecibido" 	=> $this->FechaRecibido,
							"id_estado" 	    => $this->id_estado,
			);
            $retorno = $this->imputarCambio($sqlInsert,$arraySql);
			return $retorno;

		}

		public function borrarEnvios() {

            $sqlInsert = "UPDATE envios SET estado = 0 WHERE id =:id";
             $arraySql = array(
                                "id"=> $this->id,
                            );
                
            $retorno = $this->imputarCambio($sqlInsert,$arraySql);
            return $retorno;
    
        }


	}
	

?>