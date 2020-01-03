<?php include_once "inc/header.php";?>

<?php include_once "inc/sidebar.php";?>

<div id="inc_conteudo">
<?php 
if(!isset($_GET['pagina']) || $_GET['pagina'] == ''){
	include_once "pages/home.php";
}else{
	$pagina = strip_tags($_GET['pagina']);
	
	if(file_exists('pages/'.$pagina.'.php')){
		include_once "pages/$pagina".'.php';
		
	}else{
		echo '<p>Desculpe mais a pagina que você procura, não existe!</p>';
		
	}
}

?>
</div>
<!--inclusão do conteudo-->
<!--CONTENT PAINEL-->
<?php include_once "inc/footer.php";?>
