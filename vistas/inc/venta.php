<script>
	/*--------- Buscar clientes en bd---------*/
    function buscar_cliente(){
    	let input_cliente=document.querySelector('#input_cliente').value;

    	input_cliente=input_cliente.trim(); 

    	if (input_cliente!="") {
    		 let datos = new FormData();
    		 datos.append("buscar_cliente",input_cliente);

    		 fetch("<?php echo SERVERURL ?>ajax/ventaAjax.php",{
    		 	method: 'POST',
    		 	body:datos
    		 })
    		 .then(respuesta=>respuesta.text())
    		 .then(respuesta=>{
    		 	let tabla_clientes=document.querySelector('#tabla_clientes');
    		 	tabla_clientes.innerHTML=respuesta;
    		 });
    	} 
    }/*--------- FIN---------*/

    /*--------- Agregando clientes --------*/
    function agregar_cliente(id){
    	$('#ModalCliente').modal('hide');
		    	Swal.fire({
				title: '¿Quieres agregar este cliente?',
				text: 'Se agregará el cliente',
				type: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Aceptar',
				cancelButtonText: 'Cancelar'
			}).then((result) => {
				if(result.value){
					let datos = new FormData();
		    		 datos.append("id_agregar_cliente",id);

		    		 fetch("<?php echo SERVERURL ?>ajax/ventaAjax.php",{
		    		 	method: 'POST',
		    		 	body:datos
		    		 })
		    		 .then(respuesta=>respuesta.json())
		    		 .then(respuesta=>{
		    		 	return alertas_ajax(respuesta);
		    		 });
				}else{
					$('#ModalCliente').modal('show');
				}
			});
    }/*--------- FIN---------*/

    /*--------- Buscar producto --------*/
    function buscar_producto(){
    	let input_producto=document.querySelector('#input_producto').value;

    	input_producto=input_producto.trim(); 


    	if (input_producto!="") {
    		 let datos = new FormData();
    		 datos.append("buscar_producto",input_producto);

    		 fetch("<?php echo SERVERURL ?>ajax/ventaAjax.php",{
    		 	method: 'POST',
    		 	body:datos
    		 })
    		 .then(respuesta=>respuesta.text())
    		 .then(respuesta=>{
    		 	let tabla_productos=document.querySelector('#tabla_productos');
    		 	tabla_productos.innerHTML=respuesta;
    		 });
    	} 
    }/*--------- FIN---------*/
    
    /*--------- Modal Producto --------*/
    function modal_agregar_producto(id){
    	$('#ModalProducto').modal('hide');
    	$('#ModalAgregarProducto').modal('show');
    	document.querySelector('#id_agregar_producto').setAttribute("value",id);
    }/*--------- FIN---------*/

    /*--------- Modal Buscar Producto --------*/
    function modal_buscar_producto(){
    	$('#ModalAgregarProducto').modal('hide');
    	$('#ModalProducto').modal('show');
    	
    	
    }/*--------- FIN---------*/

</script>