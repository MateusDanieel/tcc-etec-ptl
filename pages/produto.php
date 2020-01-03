<!-- CONTEÚDO DA SINGLE -->
<?php
			$pegar_produto = htmlentities($parametros[1]);
			$site->atualizarViewProd($pegar_produto);
			//atualiza views produto
			$selecionar_produto = "SELECT * FROM `produtos` WHERE slug = ?";
			$executar = @BD::conn()->prepare($selecionar_produto);
			$executar->execute(array($pegar_produto));
			$fetch_produto = $executar->fetchObject();
			//pega imagens do produto
			$sqlPegaImgs = "SELECT * FROM `img_prod_single` WHERE id_produto = ?";
			$executarImg = @BD::conn()->prepare($sqlPegaImgs);
			$executarImg->execute(array($fetch_produto->id));
			//pega os tamanhos do produto
			$sqlPegaTamanho = "SELECT * FROM `produtos_tamanho` WHERE id_produto = ?";
			$executarTamanho = @BD::conn()->prepare($sqlPegaTamanho);
			$executarTamanho->execute(array($fetch_produto->id));

?>

    <div id="conteudo_barra_single">
        <div id="bloco_geral">
            <!-- SLIDE DOS PRODUTOS -->
            <div id="bloco_left">
                <div class="img_big">
							<img src="<?php echo PATH.'/produtos_img/'.$fetch_produto->img_padrao;?>" border="0" title=""/>
						    
                            </div>
                        <?php
							while($imgProd = $executarImg->fetchObject()){
						 ?>
                    <ul id="gallery">
                        <li><img src="<?php echo PATH;?>/produtos_img/<?php echo $imgProd->img;?>" border="0" />
                        </li>
                        </ul>
                    <?php }?>
            </div>

            <div id="bloco_right">
                <h1 class="title"><?php echo $fetch_produto->titulo;?></h1>
                <form action="<?php echo PATH.'/carrinho';?>" method="post" enctype="multipart/form-data">
                
                <div id="dados_produto">
                  <div id="bloco_right_right">
							<?php 
								//ver se existe um tamanho para o produto
								$verificaTamanho = $executarTamanho->fetchObject();
							 	if(@$verificaTamanho->tamanho != ""){
						?>
                        	<div id="txtqtd"><span>QUANTIDADE :</span>
                       		<input type="text" value="1" name="prodSingle[2]" /></label><br /><br /></div>
              				 <select name="tamanho" id="tamanho">
						 		<option selected>- ESCOLHA O TAMANHO -</option>
                                <option value="<?php echo $verificaTamanho->id; ?>"><?php echo $verificaTamanho->tamanho; ?></option>
							<?php
								while($tamanhoProd = $executarTamanho->fetchObject()){
							 ?>

								<option value="<?php echo $tamanhoProd->id; ?>"><?php echo $tamanhoProd->tamanho; ?></option><br />
                                
							<?php 
								}
									echo "</select>
										 </label>";
								}
								
							?>  <script>
							 //testei aqui
							 
                        function tamanhoProduto(sel){
										var qtd = sel.options[sel.selectedIndex].value;
										var id = $(this).attr('rel');
										alert(qtd);
										alert(id);
                   }
							</script>
                
                       
           				<input type="submit" value="COMPRAR" name="comprar" id="btn_comprar" />
                       
                        </form>
                       
                    </div>
                    <div id="conteudo_dados_produto">
                    <span class="disp_estoque">Vendido e entregue por <strong><a href="<?php echo PATH;?>">PITYLU.COM.BR</a></strong></span>
                    <span class="de">De: <strike>R$ <?php echo  number_format($fetch_produto->valor_anterior ,2 ,',','.');?></strike></span>
                    <span class="por">Por: R$ <?php echo  number_format($fetch_produto->valor_atual ,2 ,',','.');?></span>

                    <span class="parcelas">Pagamento via boleto bancário ou cartão de crédito</span>
                    <span class="exemplares">Foram vendidas <?php echo $fetch_produto->qtdVendidos;?> unidade(s) deste produto</span>
                    </div>      
                          
                   </div>     
								
                </div><!--dados do produto-->
                <div id="prod_rel">
                <h2>PRODUTOS RELACIONADOS</h2>
                <?php 
						$relacionados = "SELECT * FROM `produtos` WHERE subcategoria = ? ORDER BY id DESC LIMIT 3";
						$exeRel = @BD::conn()->prepare($relacionados);
						$exeRel->execute(array($fetch_produto->subcategoria));
						
						
                
						if($exeRel->RowCount() == 0){
							echo "<p>não existem produtos relacionados</p>";
						}
						else{
						while($prodRel = $exeRel->fetchObject()){
						?>
                        
                    <div class="produto_relacionado">
                        <a href="<?php echo PATH.'/produto/'.$prodRel->slug;?>">
									<img src="<?php echo PATH.'/produtos_img/'.$prodRel->img_padrao;?>" border="0" title="<?php echo $prodRel->titulo;?>" alt="" />
									<span class="tit_prod"><?php echo $prodRel->titulo; ?></span>
                                    <span class="preco_prod_ant"><strike>De: R$<?php echo number_format($prodRel->valor_anterior ,2,',','.'); ?></strike></span>
									<span class="preco_prod">Por: R$<?php echo number_format($prodRel->valor_atual ,2,',','.'); ?></span>
								</a>
                    </div><!--produtos relacionados-->
                    <?php }}?>

                </div><!--produtos relcionados-->

            <div id="prod_conteudo">
            <h1 class="tit_conteudo_prod"><strong>SOBRE O PRODUTO</strong></h1>
                <span id="desc_conteudo_prod"><?php echo $fetch_produto->descricao;?></span>
            </div>
            
            <div id="fb-root">
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.11';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
        </div>
        <div class="fb-comments" data-href="http://localhost:8080/pitylu/" data-width="1000" data-numposts="5"></div>
        </div>
    </div>