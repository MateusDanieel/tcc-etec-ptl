<?php 
	error_reporting(0);
	ini_set(“display_errors”, 0 );
    session_start();
    include_once "config.php";
    function __autoload($classe){
        if(!strstr($classe,'PagSeguro')){
        require_once 'classes/'.$classe.'.class.php';
        }
    }
    @BD::conn();
	$site = new Site();
    $carrinho = new Carrinho();
    $login = new Login('pitylu_', 'clientes');
    
if($login->isLogado()){
	$strSQL = "SELECT * FROM `clientes` WHERE email_log = ? AND senha_log = ?";
	$stmt = @BD::conn()->prepare($strSQL);
	$stmt->execute(array($_SESSION['pitylu_emailLog'], $_SESSION['pitylu_senhaLog']));
	$usuarioLogado = $stmt->fetchObject();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Russo+One" rel="stylesheet">
    <title>PITYLU BABY</title>

    <link rel="stylesheet" href="<?php echo PATH;?>../css/style.css" media="screen">
    <script type="text/javascript" src="<?php echo PATH;?>../js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="<?php echo PATH;?>../js/cycle.js"></script>
    <script type="text/javascript" src="<?php echo PATH;?>../js/funcoes.js"></script>
	<script type="text/javascript" src="<?php echo PATH;?>../js/mask.js"></script>
    
</head>

<body>
    <div id="principal">

        <div id="barra_topo">
            <div id="barra_topo_conteudo">
                <div id="welcome">
                    <span><?php if (@$usuarioLogado->nome!=""){echo "OLÁ ".$usuarioLogado->nome. ", BEM VINDO";}else{echo"OLÁ VISITANTE,";?> </span><a href="<?php echo PATH;?>/cadastre-se">CADASTRE-SE</a><span> OU FAÇA </span><a href="#">LOGIN</a><?php } ?>
                </div>
                <div id="pedidos">
                    <img src="<?php echo PATH;?>../img/pedidos.png" border="0" alt="" title="" /><a href="#"> MEUS PEDIDOS</a>
                </div>

            </div>
            <!--TERMINA BARRA TOPO CONTEUDO-->
        </div>
        <!--TERMINA BARRA TOPO-->
    </div>
    <!--TERMINA DIV PRINCIPAL-->
    <!-- HEADER -->
    <div id="header">
        <div id="conteudo_header">
            <div id="logo" >
            <a href="http://localhost:8080/pitylu" title="">
                    <img src="<?php echo PATH;?>../img/logo%20vector.fw.png" border="0" id="logomarca" alt="" title="" />
                </a>
            </div>
            <div id="boxpesquisa">
                <form action="<?php echo PATH;?>" method="get" enctype="multipart/form-data">
                    <label>
                <input type="text" name="s" value="" placeholder=" digite o produto que deseja procurar..." id="search"/>
                	
                    </label>
                    <input type="submit" value="" id="btn_pesquisa" />
                </form>
            </div>
               <div class="sacolaCompras">
                	<img src="<?php echo PATH;?>../img/sacola.png" border="0" id="sacola" alt="" title="" /><a href="<?php echo PATH.'/carrinho';?>">MINHA SACOLA (<?php echo $carrinho->qtdProdutos();?>)</a>
                </div>

        </div>
    </div>

    <!-- MENU -->
    <div id="barra_menu">
        <div class="menu">
  			<ul class="menu-list">
                <?php $site->getMenu();?>
                <!--<li><a href="#">ACESSÓRIOS</a>
                    	<ul>
                        	<li><a href="#">Categoria 1</a></li>
                            <li><a href="#">Categoria 2</a></li>
                            <li><a href="#">Categoria 3</a></li>
                        </ul></li>
                    <li><a href="#">CALÇADOS</a>
                    <ul>
                        	<li><a href="#">Categoria 1</a></li>
                            <li><a href="#">Categoria 2</a></li>
                            <li><a href="#">Categoria 3</a></li>
                        </ul></li>
                    <li><a href="#">MENINAS</a>
                    <ul>
                        	<li><a href="#">Categoria 1</a></li>
                            <li><a href="#">Categoria 2</a></li>
                            <li><a href="#">Categoria 3</a></li>
                        </ul></li>
                    <li><a href="#">MENINOS</a>
                    <ul>
                        	<li><a href="#">Categoria 1</a></li>
                            <li><a href="#">Categoria 2</a></li>
                            <li><a href="#">Categoria 3</a></li>
                        </ul></li>
                    <li><a href="#">BEBES</a>
                       
                        <ul><li><a href="#">Categoria 1</a></li>
                            <li><a href="#">Categoria 2</a></li>
                            <li><a href="#">Categoria 3</a></li>
                        </ul></li>--> 
            </ul>
        </div>
    </div>