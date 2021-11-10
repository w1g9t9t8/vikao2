<script>
	/*--------- Buscar proveedors en bd---------*/
    function buscar_proveedor(){
    	let input_proveedor=document.querySelector('#input_proveedor').value;

    	input_proveedor=input_proveedor.trim(); 

    	if (input_proveedor!="") {
    		 let datos = new FormData();
    		 datos.append("buscar_proveedor",input_proveedor);

    		 fetch("<?php echo SERVERURL ?>ajax/gastoAjax.php",{
    		 	method: 'POST',
    		 	body:datos
    		 })
    		 .then(respuesta=>respuesta.text())
    		 .then(respuesta=>{
    		 	let tabla_proveedores=document.querySelector('#tabla_proveedores');
    		 	tabla_proveedores.innerHTML=respuesta;
    		 });
    	} 
    }/*--------- FIN---------*/

    /*--------- Agregando proveedores --------*/
    function agregar_proveedor(id){
    	$('#ModalProveedor').modal('hide');
		    	Swal.fire({
				title: '¿Quieres agregar este proveedor?',
				text: 'Se agregará el proveedor',
				type: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Aceptar',
				cancelButtonText: 'Cancelar'
			}).then((result) => {
				if(result.value){
					let datos = new FormData();
		    		 datos.append("id_agregar_proveedor",id);

		    		 fetch("<?php echo SERVERURL ?>ajax/gastoAjax.php",{
		    		 	method: 'POST',
		    		 	body:datos
		    		 })
		    		 .then(respuesta=>respuesta.json())
		    		 .then(respuesta=>{
		    		 	return alertas_ajax(respuesta);
		    		 });
				}else{
					$('#ModalProveedor').modal('show');
				}
			});
    }/*--------- FIN---------*/

    /*--------- Buscar item --------*/
    function buscar_item(){
    	let input_item=document.querySelector('#input_item').value;

    	input_item=input_item.trim(); 


    	if (input_item!="") {
    		 let datos = new FormData();
    		 datos.append("buscar_item",input_item);

    		 fetch("<?php echo SERVERURL ?>ajax/gastoAjax.php",{
    		 	method: 'POST',
    		 	body:datos
    		 })
    		 .then(respuesta=>respuesta.text())
    		 .then(respuesta=>{
    		 	let tabla_items=document.querySelector('#tabla_items');
    		 	tabla_items.innerHTML=respuesta;
    		 });
    	} 
    }/*--------- FIN---------*/
    
    /*--------- Modal item --------*/
    function modal_agregar_item(id){
    	$('#ModalItem').modal('hide');
    	$('#ModalAgregarItem').modal('show');
    	document.querySelector('#id_agregar_item').setAttribute("value",id);
    }/*--------- FIN---------*/

    /*--------- Modal Buscar item --------*/
    function modal_buscar_item(){
    	$('#ModalAgregarItem').modal('hide');
    	$('#ModalItem').modal('show');
    	
    	
    }/*--------- FIN---------*/

</script>