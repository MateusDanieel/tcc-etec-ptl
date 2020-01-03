<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<div id="aguarde"><h1>Por Favor Aguarde...<h1></div>
</body>
</html>

<?php

//$login = new Login;

if(!$login->isLogado()){
  echo "<script>window.location.href ='".PATH."';</script>";  
}elseif($carrinho->qtdProdutos() == 0){
  echo "<script>window.location.href ='".PATH."';</script>";
}elseif(!isset($_SESSION['frete_type']) || !isset($_SESSION['valor_frete'])){
    echo '<script>alert("Por favor, calcule o frete da sua compra");location.href="'.PATH.'/carrinho"</script>';
}else{

    if(!isset($_SESSION['realizado'])){
        $strSQL = "INSERT INTO `pedidos` (id_cliente, valor_total, status, criado, modificado, tipo_frete, valor_frete) VALUES(?,?,0,NOW(),NOW(), ?, ?)";
        $stmt =  @BD::conn()->prepare($strSQL);
        $stmt->execute(array($usuarioLogado->id_cliente, $_SESSION['total_compra'], $_SESSION['frete_type'], $_SESSION['valor_frete']));
        $_SESSION['lastId'] = @BD::conn()->lastInsertId();
        
        foreach($_SESSION['pitylu_produto'] as $id => $qtd){
            $strSQLdois = "INSERT INTO `produtos_pedidos` (id_pedido, id_produto, quantidade) VALUES(?,?,?)";
            $stmtdois = @BD::conn()->prepare($strSQLdois);
            $stmtdois->execute(array($_SESSION['lastId'], $id, $qtd));
            
            $atualizar_qtds = @BD::conn()->prepare("UPDATE `produtos` SET estoque = estoque-$qtd, qtdVendidos = qtdVendidos+$qtd WHERE id = ?");
            $atualizar_qtds->execute(array($id));
        }
        
        $_SESSION['realizado'] = 1;


    }
    
    //INSTANCIO A CLASSE
        require_once "classes/PagSeguroLibrary/PagSeguroLibrary.php";
    //require_once "classes/pagseguro-php-sdk-master/source/Library.php";
        $pagseguro = new PagSeguroPaymentRequest();

    
    //SETO O TIPO DE MOEDA ULTILIZADA
    $pagseguro->setCurrency('BRL');
    
    
    //INFORMO O TIPO DE FRETE
    //$pagseguro->setShippingType($array_types[$_SESSION['frete_type']]);
    $pagseguro->setShippingType(2);
    
    //INFORMAR O CODIGO DE REFERENCIA DA COMPRA
    $pagseguro->setReference($_SESSION['lastId']);
    
    //INFORMO OS DADOS DO CLIENTE
    $pagseguro->setShippingAddress($usuarioLogado->cep, $usuarioLogado->logradouro, $usuarioLogado->numero, $usuarioLogado->complemento, $usuarioLogado->bairro, $usuarioLogado->cidade, $usuarioLogado->uf, 'BRA');    

//recuperar os ids dos produtos selecionados no site, em uma só variavel usando o implode
$ids = implode(', ', array_keys($_SESSION['pitylu_produto']));
$sql = sprintf("SELECT * FROM `produtos_tamanho` WHERE id IN (%s)", $ids);
$executar = @BD::conn()->prepare($sql);
$executar->execute();

while($row = $executar->fetchObject()){
    $id2 =  $row->id;
    $id = $row->id_produto;
        $selecao = @BD::conn()->prepare("SELECT * FROM `produtos` WHERE id = ?");
                        $selecao->execute(array($id));
                        $fetchProduto = $selecao->fetchObject();
    $produto = $fetchProduto->titulo;
    $qtd = $_SESSION['pitylu_produto'][$id2];
    $preco = $fetchProduto->valor_atual;
    $peso = (int)$fetchProduto->peso;
    
    //agora vamos adicionar ao carrinho
    $pagseguro->addItem($id, $produto, $qtd, $preco, $peso, $_SESSION['valor_frete_'.$id2]);
    
    //AGORA IREMOS ULTILIZAR A CLASSE ACCOUNTCRENDENTIAL PARA ADICIONAR NOSSAS CREDENCIAIS
    $credenciais = new PagSeguroAccountCredentials('amauridesouzaferreira@gmail.com','866415C4ED324EA7A5C0D3BAB1FE4EC3');
    $url = $pagseguro->register($credenciais);
    
    session_destroy();
    //header("Location: PATH");
    }
     echo "<script>window.location.href = '".$url."';</script>";
       
}
?>




