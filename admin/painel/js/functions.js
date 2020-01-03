$(function(){
	$('#preco').priceFormat({
		prefix: '',
		centsSeparator: '.',
		thousandsSeparator: '.'
	});
	
	$('#preco1').priceFormat({
		prefix: '',
		centsSeparator: '.',
		thousandsSeparator: '.'
	});
	
});

$(document).ready(function(){
$("select[name=categoria]").change(function(){
		$("select[name=subcategoria]").html('<option value="" selected="selected">Carregando...</option>');
		
		$.post("combo.php", {categoria:$(this).val()}, function(valor){
			$("select[name=subcategoria]").html(valor);
		});
	});

	
$("select[name=status]").change(function(){
	var valor = $("select[name=status]").val();
	
	if(valor == 2){
		$('div.ocultar').show('slow');
	}else{
		$('div.ocultar').hide('slow');
	}
});
})

function confirmCat(id){
	if(confirm("Você tem certeza que deseja excluir esta categoria? isto pode danificar seu SEO!")){
		location.href="index.php?pagina=editarCategorias&deletar=sim&categoria_id="+id+"";
	}
}
function confirmSub(id){
	if(confirm("Você tem certeza que deseja excluir esta Subcategoria? isto pode danificar seu SEO!")){
		location.href="index.php?pagina=listarSubcategorias&deletar=sim&subcat="+id+"";
	}
}
function confirmPedido(id){
	if(confirm("Você tem certeza que deseja excluir este pedido?")){
		location.href="index.php?pagina=detalhes_compra&compra_id="+id+"&deletar=sim";
	}
}
function confirmTicket(id){
	if(confirm("Você tem certeza que deseja excluir este ticket?")){
		location.href="index.php?pagina=ver_ticket&ticket_id="+id+"&deletar=sim";
	}
}	
