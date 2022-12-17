<?php
	$ruta 	= isset($_GET['r'])?$_GET['r']:"";
	$accion = isset($_GET['a'])?$_GET['a']:"";
	$IdEnvio= isset($_GET['CI'])?$_GET['CI']:"";
	$pagina =isset($_GET['pagina'])?$_GET['pagina']:"1";
	$busqueda =isset($_GET['busqueda'])?$_GET['busqueda']:"";

	require_once("./modelos/envios.php");

	$objEnvio = new envios();

		

		if(isset($_POST['action']) && isset($_POST['action']) == "ingresar"){

			$arrayDatos	= $_POST;

			$objEnvio->constructor($arrayDatos);

			$respuesta = $objEnvio->ingresarEnvios();

		}

		if($accion == "editar" && $IdEnvio != ""){

			$objEnvio->cargarEnvios($IdEnvio);

		}

		if(isset($_POST['action-guardar'])){

			$arrayDatos	= $_POST;

			$objEnvio->constructor($arrayDatos);

			$respuesta = $objEnvio->editarEnvios();


		}


		if($accion == "borrar" && $IdEnvio != ""){

			$objEnvio->cargarEnvios($IdEnvio);

		}

		if(isset($_POST['action-borrar'])){

			$arrayDatos	= $_POST;

			$objEnvio->constructor($arrayDatos);

			$respuesta = $objEnvio->borrarEnvios();

		}


	$filtros = array("totalRegistros"=>3, "busqueda" => $busqueda);

	$totalEnvios = $objEnvio->totalEnvios($filtros);

	$totalPaginas = ceil($totalEnvios / $filtros['totalRegistros']);

	if($pagina > $totalPaginas){

		$pagina = $totalPaginas;
	}
	if($pagina < 1){

		$pagina = 1;
	}

	


	$paginaSiguiente = $pagina + 1;
	if($paginaSiguiente > $totalPaginas){

		$paginaSiguiente = $totalPaginas;
	}

	$paginaAnterior = $pagina - 1 ;
	if($paginaAnterior < 1){

		$paginaAnterior = 1;

	}

	$filtros['pagina']= $pagina ;

	$listaEnvios = $objEnvio->listarEnvios($filtros);

?>


	<div>
		<h1 class="center"><a href="index.php?r=<?=$ruta?>" class="black-text">Envios</a></h1>

<?php

	if($accion == "editar" && $IdEnvio != ""){
?>
			<div class="card">
				<div class="card-content">
					<form action="index.php?r=<?=$ruta?>" method="POST" class="col s6">
						<div>
							<h4>Editar Envio</h4>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input id="CI" type="number" class="validate" name="CI" value="<?=$objEnvio->traerCiCliente()?>">
								<label for="CI">CI</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s6">
								<input id="nombre" type="text" class="validate" name="nombre" value="<?=$objEnvio->traerDestinatario()?>" >
								<label for="nombre">destinatario</label>
							</div>
							<div class="input-field col s6">
								<input id="Departamentos" type="text" class="validate" name="Departamentos" value="<?=$objEnvio->traerId_departamentos()?>">
								<label for="Departamentos">Departamentos</label>
							</div>
						</div>
						
						<div class="row">
							<div class="input-field col s12">
								<input id="calle" type="text" class="validate" name="calle" value="<?=$objEnvio->traerCalle()?>">
								<label for="calle">Calle</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input id="puerta" type="text" class="validate" name="puerta" value="<?=$objEnvio->traerPuerta()?>">
								<label for="puerta">Puerta</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input id="fecha" type="date" class="validate" name="fecha" value="<?=$objEnvio->traerFechaRecibido()?>">
								<label for="fecha">Fecha Recibido</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input id="estado" type="text" class="validate" name="estado" value="<?=$objEnvio->traerIdEstado()?>">
								<label for="estado">Estado Paquete</label>
							</div>
						</div>

						<div class="row">
							<input  type="hidden" name="id" value="<?=$objClientes->traerIdEnvio()?>">
							
							<button class="btn waves-effect waves-light right cyan" type="submit" name="action-guardar" value="editar">Guardar
								<i class="material-icons right">save</i>
							</button>
						</div>
					
						
					</form>	
				</div>
			</div>
<?php
	}
?>


<?php
	if($accion == "borrar" && $IdEnvio != ""){

?>
			<div class="card">
				<div class="card-content">
					<form action="index.php?r=<?=$ruta?>" method="POST" class="col s12">
						<div class="center">
							<h4>borrar Cliente</h4>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<h3>Estas seguro que quieres cancelar el envio para <?=$objEnvio->traerDestinatario()?></h3>
							</div>
						</div>

						<div class="row">
							<input  type="hidden" name="id" value="<?=$objEnvio->traerIdEnvio()?>">
							<div class="input-field col s2">
								<button class="btn waves-effect waves-light right red" type="submit" name="action-borrar" value="borrar">Borrar
									<i class="material-icons right">delete</i>
								</button>
							</div>

							<div class="input-field col s2">
								<button class="btn waves-effect waves-light right cyan" type="submit" name="" value="cancelar">Cancelar
									<i class="material-icons right">cancele</i>
								</button>
							</div>

						</div>
					
						
					</form>	
				</div>
			</div>
<?php
	}
?>

<?php
	if(isset($respuesta) && $respuesta['codigo'] == "Error"){
?>

		<div class="red center" style="height: 40px">
			<h4>Hubo algun error</h4>
		</div>
<?php

	}elseif(isset($respuesta) && $respuesta['codigo'] == "Ok"){
?>

		<div class="green center" style="height: 40px">
			<h4>Se realizo correctamente</h4>
		</div>
<?php
	}
?>

		<div class="row">
			<a class="btn-small btn-large waves-effect waves-light cyan modal-trigger right-aling" href="#modal1">Ingresar Clientes</a>

		</div>
		


		<div id="modal1" class="modal">
			<div class="center">
				<h4>
					Ingresar Cliente
				</h4>
			</div>


			<div class="modal-content">
				<form action="index.php?r=<?=$ruta?>" method="POST" class="col s12">

					<div class="row">
							<div class="input-field col s12">
								<input id="CI" type="number" class="validate" name="CI">
								<label for="CI">Documento Cliente</label>
							</div>
					</div>
					<div class="row">
						<div class="input-field col s6">
							<input id="nombre" type="text" class="validate" name="nombre">
							<label for="nombre">Nombre Destinatario</label>
						</div>
						<div class="input-field col s6">
							<input id="Departamento" type="text" class="validate" name="Departamento">
							<label for="departamento">Departamento</label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s3">
							<input id="calle" type="text" class="validate" name="calle">
							<label for="calle">Calle</label>
						</div>
						<div class="input-field col s3">
							<input id="puerta" type="number" class="validate" name="puerta">
							<label for="puerta">puerta</label>
						</div>
						<div class="input-field col s3">
							<input id="fecha" type="date" class="validate" name="fecha">
							<label for="fecha">Fecha Recibido</label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s3">
							<input id="Estado" type="text" class="validate" name="Estado">
							<label for="Estado">Estado Del Paquete</label>
						</div>

						<button class="btn waves-effect waves-light right cyan" type="submit" name="action" value="ingresar">ingresar
							<i class="material-icons right">save</i>
						</button>
					</div>
    			</form>	
			</div>
		</div>
	</div>

	

	
	

	<table class="responsive-table highlight centered">
        <thead>
			
			<div class="nav-wrapper col s3">
				<form action="index.php?" method="GET">
					<div class="input-field ">
						<input type="hidden" name="r" value="<?=$ruta?>">
						<input id="search" type="search" name="busqueda" required>
						<label class="label-icon" for="search">
							<i class="material-icons">search</i>
						</label>
						<i class="material-icons">close</i>
					</div>
				</form>
			</div>

		    <tr class="cyan white-text">
              <th>Documento Cliente</th>
              <th>Nombre Destinatario</th>
              <th>Departamento</th>
			  <th>Calle</th>
			  <th>Numero puerta</th>
			  <th>Fecha Recibido</th>
			  <th>Estado</th>
			  <th></th>
            </tr>
        </thead>

        <tbody>

<?php
	foreach($listaEnvios as $envios){

?>
			<tr>
			  	<td><?=$envios['CI_clientes']?></td>
				<td><?=$envios['destinatario']?></td>
				<td><?=$envios['departamento']?></td>
				<td><?=$envios['calle']?></td>
				<td><?=$envios['puerta']?></td>
				<td><?=$envios['fechaRecibido']?></td>
				<td><?=$envios['id_estado']?></td>
				<td>
					<div class="right-aling">
                        <a href="index.php?r=<?=$ruta?>&a=editar&CI=<?=$envios['id']?>" class="waves-effect cyan btn-floating">
							<i class="material-icons left">edit</i>
						</a>
                        <a href="index.php?r=<?=$ruta?>&a=borrar&CI=<?=$envios['id']?>" class="waves-effect red btn-floating">
							<i class="material-icons left">delete</i>
						</a>
                    </div>
                </td>
			</tr> 

<?php
	}
?>
        </tbody>
    </table>
	<div class="row cyan" style="height: 80px">
		<div class="col s12 center">
			<ul class="pagination" id="pagina">
				<li class="waves-effect">
					<a href="index.php?r=<?=$ruta?>&pagina=<?=$paginaAnterior?>&busqueda=<?=$busqueda?>"><i class="material-icons">chevron_left</i></a>
				</li>

<?php
				for($i = ($pagina-3) ; $i <= ($pagina+3); $i++){

					if($i < 1 || $i > $totalPaginas){

						continue;

					}

					if($pagina == $i){
						$clase = "active black";
					}else{
						$clase = "waves-effect";
					}

?>	

				<li class="<?=$clase?>">
					<a href="index.php?r=<?=$ruta?>&pagina=<?=$i?>&busqueda=<?=$busqueda?>"><?=$i?></a>
				</li>

<?php
							}
?>
							
				<li class="waves-effect"> 
					<a href="index.php?r=<?=$ruta?>&pagina=<?=$paginaSiguiente?>&busqueda=<?=$busqueda?>"><i class="material-icons">chevron_right</i></a>
				</li>

			</ul>
		</div>
	</div>


