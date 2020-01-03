<?php

function calculaFrete($cod_servico, $cep_origem, $cep_destino, $peso, $valor_declarado, $altura='2', $largura='11', $comprimento='16'){
		# Código dos Serviços dos Correios
		# 41106 PAC sem contrato
		# 40010 SEDEX sem contrato
		# 40045 SEDEX a Cobrar, sem contrato
		# 40215 SEDEX 10, sem contrato

    $correios = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=".$cep_origem."&sCepDestino=".$cep_destino."&nVlPeso=".$peso."&nCdFormato=1&nVlComprimento=".$comprimento."&nVlAltura=".$altura."&nVlLargura=".$largura."&sCdMaoPropria=n&nVlValorDeclarado=".$valor_declarado."&sCdAvisoRecebimento=n&nCdServico=".$cod_servico."&nVlDiametro=0&StrRetorno=xml";
    $xml = simplexml_load_file($correios);
            return $xml->cServico->Valor;
    
	}


?>

<?php
if(isset($parametros[1]) && $parametros[1] == 'add' && isset($parametros[2]) && $parametros[2] != '0'){
    $id = (int)$parametros[2];
    $carrinho->verificaAdiciona($id);
}

if(isset($_SESSION['pitylu_produto'][0])){unset($_SESSION['pitylu_produto'][0]);}
if(count(@$_SESSION['pitylu_produto']) == 0){unset($_SESSION['pitylu_produto']);}

//verifica se o produto que o usuário está tentando adicionar está disponível para compra no banco de dados
	/*$verificar_no_banco = BD::conn()->prepare("SELECT estoque FROM `produtos` WHERE id = ?");
	$produto_Id = (int)$parametros[2];
	$verificar_no_banco->execute(array($produto_Id));
	$fetchEstoque = $verificar_no_banco->fetchObject();
	if($fetchEstoque->estoque == '0'){
		unset($_SESSION['pitylu_produto'][$produto_Id]);
		echo '<p id="aviso">Desculpe, mais este produto encontra-se em falta em nosso estoque!</p>';
	}*/


	if(isset($parametros[1]) && $parametros[1] == 'add' || isset($_POST['atualizar'])){
		unset($_SESSION['valor_frete']);
		foreach($_SESSION['pitylu_produto'] as $id => $qtd){
			unset($_SESSION['valor_frete_'.$id]);
		}
	}

if(isset($parametros[1]) && $parametros[1] == 'del' && isset($parametros[2])){
    $idDel = (int)$parametros[2];
    if($carrinho->deletarProduto($idDel)){
        echo '<script>alert("Produto deletado do carrinho");location.href="'.PATH.'/carrinho"</script>';
    }else{
        echo '<script>alert("Erro ao deletar produto");location.href="'.PATH.'/carrinho"</script>';
    }
} 

if(isset($_POST['prodSingle'])){
	
		$produtoValor = $_POST['prodSingle'];
		//aqui
		$idd = $_POST['tamanho'];
		if($carrinho->setarByPost($produtoValor, $idd)){}else{
			echo '<p id="aviso">Não foi possivel adicionar este produto</p>';
		}	
	}

    
    if(isset($_POST['atualizar'])){
		$produto = $_POST['prod'];
		foreach($produto as $chave => $qtd){
			$selecionar_produto = @BD::conn()->prepare("SELECT * FROM `produtos_tamanho` WHERE id = ?");
			$selecionar_produto->execute(array($chave));
			$fetchProd = $selecionar_produto->fetchObject();
			if($qtd > @$fetchProd->estoque){
				echo '<p id="aviso">Não é possivel adicionar mais que: '.@$fetchProd->estoque.' produtos para compra deste produto: '.@$fetchProd->titulo.'</p>';
				
				$warn = true;
			}
		}
        
        if(@$warn == true){}else{
			if($carrinho->atualizarQuantidades($produto)){
				echo '<script>alert("Quantidade foi alterada");location.href="'.PATH.'/carrinho"</script>';
			}else{
				echo '<script>alert("Erro ao alterar quantidades");location.href="'.PATH.'/carrinho"</script>';
			}
		}
	}

//FRETE

if(isset($_POST['acao']) && $_POST['acao'] == 'calcular');
@$frete = $_POST['frete'];
$_SESSION['frete_type'] = $frete;
$cep = strip_tags(filter_input(INPUT_POST, 'cep'));
switch($frete){    
    case 'pac';
        $valor = '41106';
        $peso_total = 0;
        foreach($_SESSION['pitylu_produto'] as $id => $qtd){
            $selecionar_produto = @BD::conn()->prepare("SELECT peso FROM `produtos` WHERE id = ?");
            $selecionar_produto->execute(array($id));
            $fetch_produto = $selecionar_produto->fetchObject();
            	
			//$peso_total += $fetch_produto->peso;
		    // echo $_SESSION['valor_frete_'.$id];
        }
         $_SESSION['valor_frete_'.$id] = calculaFrete($valor, '08210450', $cep, $peso_total, '50');
    break;
        
    case 'sedex';
        $valor = '40010';
        $peso_total = 0;
        foreach($_SESSION['pitylu_produto'] as $id => $qtd){
            $selecionar_produto = @BD::conn()->prepare("SELECT peso FROM `produtos` WHERE id = ?");
            $selecionar_produto->execute(array($id));
            $fetch_produto = $selecionar_produto->fetchObject();
            
			//$peso_total += $fetch_produto->peso;
		    // echo $_SESSION['valor_frete_'.$id];
        }
            $_SESSION['valor_frete_'.$id] = calculaFrete($valor, '08210450', $cep, $peso_total, '50');
            
        
            
    break;
}
$_SESSION['valor_frete'] = 0;
foreach(@$_SESSION['pitylu_produto'] as $id => $qtd){
	@$_SESSION['valor_frete_'.$id] = str_replace(",",".",$_SESSION['valor_frete_'.$id]);
    @$_SESSION['valor_frete_'.$id] = $_SESSION['valor_frete_'.$id];
    
    @$_SESSION['valor_frete'] += $_SESSION['valor_frete_'.$id]*$qtd;
}

?>
    <div id="carrinho-page">
        <img src="<?php echo PATH;?>/img/sacola.png" id="img_sacola" border="0" alt=""><h1 class="title-page">MINHA SACOLA</h1>
        <form action="<?php echo PATH.'/carrinho/atualizar';?>" method="post" enctype="multipart/form-data">
            <table border="1" cellpadding="0" cellspacing="0" class="carrinho">
                   <thead>
                    <tr>
                        <th>PRODUTO</th>
                        <th>QUANTIDADE</th>
                        <th>VALOR UNITÁRIO</th>
                        <th>SUB-TOTAL</th>
                        <th>REMOVER</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                if($carrinho->qtdProdutos() == 0){
                    echo '<tr><td colspan="5"><br />VOCÊ NÃO POSSUI PRODUTOS NA SUA SACOLA<br /><br /></td></tr>';
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
                            <td><img src="<?php echo PATH;?>/produtos_img/<?php echo $exibeProduto->img_padrao;?>" width="70" id="prodimg" title="<?php echo $exibeProduto->titulo;?>" alt="" border="0"><span><?php echo $exibeProduto->titulo, " T. ", $fetchProduto->tamanho;?></span></td>
                            <td><input type="text" name="prod[<?php echo $id;?>]" value="<?php echo $quantidade;?>" size="3" /></td>
                            <td class="unitario">R$
                                <?php echo number_format($exibeProduto->valor_atual, 2,',','.');?>
                            </td>
                            <td class="sub">R$
                                <?php echo number_format($exibeProduto->valor_atual * $quantidade, 2,',','.');?>
                            </td>
                            <td><a href="<?php echo PATH.'/carrinho/del/'.$id;?>" title="Deletar produto"><img src="<?php echo PATH;?>../img/del.png" border="0" alt=""></a></td>
                        </tr>
                        <?php $total += $exibeProduto->valor_atual *$quantidade;}}?>
                        <tr>
                        	<td align="right" class="last"><strong>VALOR DO FRETE:</strong></td> 
                			<td class="total">R$ <?php echo number_format($_SESSION['valor_frete'],2,',','.');?></td>
                            <td align="right" class="last"><strong>VALOR TOTAL:</strong></td>
                            <td class="total">R$
                                <?php echo (isset($_SESSION['valor_frete'])) ? number_format($total+$_SESSION['valor_frete'],2,',','.') : number_format($total, 2,',','.');?>
                            </td>
                        </tr>
                </tbody>
            </table>
            <input type="submit" value="Atualizar Quantidades" id="update" name="atualizar">
        </form>
        <div id="opcoes">
            <div id="outros">
                
                <a href="<?php echo PATH.'/verificar';?>" id="finalizar">Finalizar Compra</a>
                <a href="<?php echo PATH;?>" id="continuar">Continuar Comprando</a>
            </div>
            <div class="calcular">
                <form action="<?php echo PATH.'/carrinho'; ?>" method="post" enctype="multipart/form-data">
                    <input type="submit" value="Calcular Frete">
                    <label>
                    <span>Escolha a forma de envio</span>
                    <select name="frete">
                        <option value="pac">PAC</option>
                        <option value="sedex" selected>SEDEX</option>
                    </select>
                </label>
                    <label>
                    <span id="cep">Seu CEP</span>
                    <input type="text" name="cep">  
                </label>
                    <input type="hidden" name="acao" value="calcular">
                </form>
            </div>
        </div>
        <!--opcoes-->
    </div>
    <!--carrinho - page-->
    <?php 
    (isset($_SESSION['valor_frete'])) ? 
    $_SESSION['total_compra'] = number_format($total+$_SESSION['valor_frete'],2,',','.') :
    $_SESSION['total_compra'] = number_format($total,2,',','.');
    $_SESSION['total_compra'] = str_replace(",",".", $_SESSION['total_compra']);
?>
