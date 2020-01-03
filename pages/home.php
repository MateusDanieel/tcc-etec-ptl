<!-- SLIDE -->
  
<div id="slide">
    <ul>
       <?php
        foreach($site->getBanners() as $Banners){
        ?>
        <li>
            <img src="<?php echo PATH;?>../banners/banner1.jpg" class="img_slide" border="0" alt="">
        </li>
        <li>
            <img src="<?php echo PATH;?>../banners/banner2.png" class="img_slide" border="0" alt="">
        </li>
        <li>
            <img src="<?php echo PATH;?>../banners/banner3.jpg" class="img_slide" border="0" alt="">
        </li>
    </ul>
    <?php }?>
</div>


<!-- PROPAGANDA -->
<div id="propaganda">
    <div id="conteudo_propaganda">
        <div class="box">
            <img src="<?php echo PATH;?>../img/boleto.png" alt="">
            <span>Desconto no Boleto a vista ou em 1x no Cartão</span>
        </div>
        <div class="box">
            <img src="<?php echo PATH;?>../img/cart%C3%A3o.png" alt="">
            <span>Pagamento facil em até 6x sem juros no cartão</span>
        </div>
        <div class="box">
            <img src="<?php echo PATH;?>../img/cadeado1.png" alt="">
            <span>Compra com segurança. Ambiente seguro e certificado.</span>
        </div>
        <div class="box">
            <img src="<?php echo PATH;?>../img/frete.png" alt="">
            <span>Frete gratis para o estado de São Paulo</span>
        </div>
    </div>
</div>

<!-- LINHA DIVISORIA 1 -->
<div id="linha1">
    <div class="row1"></div>
</div>


<!-- PRODUTOS EM DESTAQUE -->
<div id="produtos_destaque">
    <div id="conteudo_produtos_destaque">
        <h2>PRODUTOS EM DESTAQUE</h2>
        <div id="box_produtos1">

            <?php
               foreach($site->getProdutosHome(2) as $produto){ 
            ?>
                <div id="prod">
                    <a href="<?php echo PATH.'/produto/'.$produto['slug'];?>" title="<?php echo $produto['titulo'];?>">
                    <div id="img_produto">
                		<img src="<?php echo PATH;?>/produtos_img/<?php echo $produto['img_padrao'];?>" border="0" title="">
                	</div>
                 <div id="info_prod">
                    <div id="prod_nome"> 
                        <p class="titulo_produto"><?php echo $produto['titulo'];?></p>
                    </div>
                    <div id="preco_prod">
                        <p>A partir de</p>
                        <p class="preco">R$ <?php echo number_format($produto['valor_atual'],2 ,',','.');?></p>
                    </div>
                      <?php
				   ///mexi aqui e tenho que lembrar
                        $pegaProduto = @BD::conn()->prepare("SELECT * FROM `produtos_tamanho` WHERE  id_produto = ?");
						$pegaProduto->execute(array($produto['id']));
						$pegaUmTamanho = $pegaProduto->fetchObject();
				   	  ?>
                       </a>
                    </div>
                </div>
                <?php }?>


               

        </div><!--TERMINA BOX PRODUTOS 1-->

        <div id="box_produtos2">
        <?php
               foreach($site->getProdutosHome(4) as $produto){ 
            ?>
            <div class="prod3">
                <a href="<?php echo PATH.'/produto/'.$produto['slug'];?>" title="<?php echo $produto['titulo'];?>">
                    <div class="img_produto2">
                		<img src="<?php echo PATH;?>/produtos_img/<?php echo $produto['img_padrao'];?>" border="0" title="">
                	</div>
                    <p class="titulo_prod3"><?php echo $produto['titulo'];?></p>
                    <p class="preco_prod3">A partir de</p>
                    <p class="preco_prod3">R$ <?php echo number_format($produto['valor_atual'],2 ,',','.');?></p>
                
                <div class="btn2">
                       <?php
///mexi aqui e tenho que lembrar
                        $pegaProduto = @BD::conn()->prepare("SELECT * FROM `produtos_tamanho` WHERE id_produto = ?");
						$pegaProduto->execute(array($produto['id']));
						while($pegaUmTamanho = $pegaProduto->fetchObject()){$pegaIdTamanho = $pegaUmTamanho->id;}
				   	  ?>
                </div>
                </a>
            </div>
            <?php }?>
        </div>
        
    </div>
    <!--TERMINA CONTEUDO PRODUTOS EM DESTAQUE-->
</div>
<!--TERMINA PRODUTOS EM DESTAQUE-->

<!-- LINHA DIVISORIA 2 -->


<!-- PRODUTOS MAIS VENDIDOS -->
<div id="produtos_mvendidos">
    <div id="conteudo_produtos_mvendidos">
        <h2>PRODUTOS MAIS VISTOS</h2>
        <div id="box_produtos_mvendidos1">
            <div id="linha2">
                <div class="row2"> </div>
            </div>
            <?php
               foreach($site->getProdutosViews(4) as $produto){ 
            ?>
            <div id="box_mvendidos">
            	<a href="<?php echo PATH.'/produto/'.$produto['slug'];?>" title="">
                <img src="<?php echo PATH;?>/produtos_img/<?php echo $produto['img_padrao'];?>" class="img_box_mvendidos" />
                <p class="p1"><?php echo $produto['titulo'];?></p>
                <p class="p3"><strike>De: R$ <?php echo number_format($produto['valor_anterior'],2 ,',','.');?></strike></p>
                <P class="p4">Por: R$ <?php echo number_format($produto['valor_atual'],2 ,',','.');?></P>
                
                <div class="btn2">
                      <?php
///mexi aqui e tenho que lembrar
                        $pegaProduto = @BD::conn()->prepare("SELECT * FROM `produtos_tamanho` WHERE id_produto = ?");
						$pegaProduto->execute(array($produto['id']));
						while($pegaUmTamanho = $pegaProduto->fetchObject()){$pegaIdTamanho = $pegaUmTamanho->id;}
				   	  ?>
                </div>
             </a>
            </div>
            <?php } ?>
           
        </div>
        
    </div>
</div>
