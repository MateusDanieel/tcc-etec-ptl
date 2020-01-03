<div id="pesquisar">
    <div id="pesquisar_prod">
    
    <script>
    function carregar(texto) {
            $.ajax({
				type: 'GET',
                url: 'pesquisar.php',
				data: texto,
				
                success: function(retorno) {
                    $('#result').html(retorno);
                }
            });
        }
    </script>
    
<?php
$pesquisa = strip_tags(trim(htmlentities($_GET['s'])));
	if($_GET['s'] != ''){
		
		$explode = explode(' ', $_GET['s']);
		$num = count($explode);
		$busca = '';
		
		for($i =0; $i < $num; $i++){
			$busca .= "`titulo` LIKE :busca$i";
			if($i<>$num-1){$busca .= ' AND';}
		}
		
		$pg = (isset($_GET['pagina'])) ? (int)htmlentities($_GET['pagina']) : '1';
		$maximo = '9';
		$inicio = (($pg * $maximo) - $maximo);
		$buscar = @BD::conn()->prepare("SELECT * FROM `produtos` WHERE $busca LIMIT $inicio, $maximo");
		for($i=0; $i<$num; $i++){
			$buscar->bindValue(":busca$i",'%'.$explode[$i].'%', PDO::PARAM_STR);
		}
		$buscar->execute();

	}//se a busca for diferente que vazio
	
		$buscaAjax = @BD::conn()->prepare("SELECT * FROM `produtos` WHERE $busca LIMIT $inicio, $maximo");
		for($i=0; $i<$num; $i++){
			$buscaAjax->bindValue(":busca$i",'%'.$explode[$i].'%', PDO::PARAM_STR);
		}
		$buscaAjax->execute();
		if($buscaAjax->rowCount() > 0){
			echo '<p id="aviso">SUA PESQUISA RETORNOU '.$buscar->rowCount().' PRODUTOS</p>';
			echo '<div id="produtos">';
				while($resultado = $buscar->fetchObject()){
?>

<div class="prod_search">
	<a href="<?php echo PATH;?>/produto/<?php echo @$resultado->slug;?>" title="<?php echo @$resultado->title;?>">
		<div class="img_prod_search">
			<img src="<?php echo PATH;?>/produtos_img/<?php echo $resultado->img_padrao;?>" width="150" height="150" border="0" title="<?php echo $resultado->titulo;?>" alt="" />
		</div>
		
		<p class="nome_prod_search"><?php echo $resultado->titulo;?></p>
		<span class="preco_anterior_prod_search"><strike>De: R$<?php echo number_format($resultado->valor_anterior, 2,',','.');?></strike><br /></span>
        <span class="preco_prod_search">Por: R$<?php echo number_format($resultado->valor_atual, 2,',','.');?></span><br /><br />
		
	</a>
	<a href="<?php echo PATH;?>/carrinho/add/<?php echo $resultado->id;?>" id="cart"></a>
	</div>
<!-- produto_box -->
    

<?php
		}}else{echo '<h1 class="else_noresult">DESCULPE, PRODUTO NÃO ENCONTRADO :(</h1>';}
?>
</div><!--- id produtos -->

<div id="paginator">
<?php
	$sql_res = @BD::conn()->prepare("SELECT id FROM `produtos` WHERE $busca");
	for($i=0; $i<$num; $i++){
		$sql_res->bindValue(":busca$i",'%'.$explode[$i].'%', PDO::PARAM_STR);
	}
	$sql_res->execute();
	$total = $sql_res->rowCount();
	$pags = ceil($total/$maximo);
	$links = '5';
	
	echo '<span class="page">Página: '.$pg.' de '.$pags.'</span>';
	for($i = $pg-$links; $i<=$pg-1;$i++){
		if($i<=0){}else{
			echo '<a href="'.PATH.'/?s='.$pesquisa.'&pagina='.$i.'">'.$i.'</a>';
		}
	}echo '<strong>'.$pg.'</strong>';
	
	for($i = $pg+1; $i<=$pg+$links; $i++){
		if($i>$pags){}else{
			echo '<a href="'.PATH.'/?s='.$pesquisa.'&pagina='.$i.'">'.$i.'</a>';
		}
	}
	echo '<a href="'.PATH.'/?s='.$pesquisa.'&pagina='.$pags.'"> <strong>| Última Página</strong></a>';
?>
</div>
</div><!-- content produtos -->