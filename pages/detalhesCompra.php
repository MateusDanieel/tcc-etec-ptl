<div id="carrinho-page">
        <h1 class="title-page"><img src="<?php echo PATH;?>" borde="0" alt="">Finalizar Pedido</h1>
        <form action="<?php echo PATH.'/carrinho/atualizar';?>" method="post" enctype="multipart/form-data">
            <table border="1" cellpadding="0" cellspacing="0" class="carrinho">
                   <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Valor Unitário</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                if($carrinho->qtdProdutos() == 0){
                    echo '<tr><td colspan="5">Não existem produtos em seu carrinho!</td></tr>';
                }else{
                    foreach($_SESSION['pitylu_produto'] as $id => $quantidade){
                        $id = (int)$id;
                        $selecao = @BD::conn()->prepare("SELECT * FROM `produtos_tamanho` WHERE id = ?");
                        $selecao->execute(array($id));
                        $fetchProduto = $selecao->fetchObject();
						//daqui
					  		$pegaProduto = @BD::conn()->prepare("SELECT * FROM `produtos` WHERE id = ?");
							$pegaProduto->execute(array($fetchProduto->id_produto));
							$exibeProduto = $pegaProduto->fetchObject();
						//ate aqui
					?>
                        <tr>
                            <td><img src="<?php echo PATH;?>/produtos_img/<?php echo $exibeProduto->img_padrao;?>" width="70" id="prodimg" title="<?php echo $exibeProduto->titulo;?>" alt="" border="0"><span><?php echo $exibeProduto->titulo, ", ", $fetchProduto->tamanho;?></span></td>
                            <td><?php echo $quantidade;?></td>
                            <td class="unitario">R$
                                <?php echo number_format($exibeProduto->valor_atual, 2,',','.');?>
                            </td>
                            <td class="sub">R$
                                <?php echo number_format($exibeProduto->valor_atual * $quantidade, 2,',','.');?>
                            </td>
                            <td><a href="<?php echo PATH.'/carrinho/del/'.$id;?>" title="Deletar produto"><img src="#"alt=""></a></td>
                        </tr>
                        <?php $total += $exibeProduto->valor_atual *$quantidade;}}?>
                        <tr>
                            <td colspan="4" align="right" class="last">Total:</td>
                            <td class="total">R$
                                <?php echo number_format($total,2,',','.');?>
                            </td>
                            <td colspan="4" align="right" class="freteFinalizar">Frete:</td>
                            <td class="total">R$
                            <?php echo @$_SESSION['valor_frete'];?>
                            <td colspan="4" align="right" class="freteFinalizar">Total Compra:</td>
                            <td class="total">R$
                            <?php echo number_format($total+$_SESSION['valor_frete'],2,',','.');?>
                        </tr>
                </tbody>
            </table>
        </form>
        <div id="opcoes">
            <div id="outros">
 
               
                Formas de pagamento
                <!-- INICIO FORMULARIO BOTAO PAGSEGURO -->
<form action="https://pagseguro.uol.com.br/checkout/v2/payment.html" method="post" onsubmit="PagSeguroLightbox(this); return false;">
<!-- NÃO EDITE OS COMANDOS DAS LINHAS ABAIXO -->

<input type="hidden" name="code" value="5E57478A76763D28845E5FA9C9791249" />
<input type="hidden" name="iot" value="button" />
<input type="image" src="https://stc.pagseguro.uol.com.br/public/img/botoes/pagamentos/209x48-comprar-assina.gif" name="submit" alt="Pague com PagSeguro - é rápido, grátis e seguro!" />
</form>
              
<script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>
<!-- FINAL FORMULARIO BOTAO PAGSEGURO -->
            </div>
            <div class="calcular">
                
            </div>
        </div>
        <!--opcoes-->
    </div>
