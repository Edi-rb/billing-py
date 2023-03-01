$(document).ready(function(){
	$('body #crearXML').click(function() {
		console.log('Hola');
		crearXML();
	});
});


function crearXML(argument) {
	link = "facturas/crearXML.php";
	folio = $('#folio').val();
	tipoFactura = $('#TipoFactura').val();
	usoFactura = $('#UsoFactura').val();
	formaPago = $('#FormaPago').val();
	data = {'function' : 'crear',
			'factura' : folio,
			};
	console.log(data);
	$.ajax({
		url: link, 
		type: 'POST',
		dataType: 'JSON',
		data: data,
		success: function (data){
			console.log(data);
			if(data.status==1){
				console.log(data);
			}
			else{
				console.log('error');
				console.log(data);
			}
		},
		error: function (xhr, ajaxOptions, thrownError)
		{
			console.log(xhr);
		}
	});
}