<?php
	error_reporting(0);
	ini_set(“display_errors”, 0 );
    session_start();
    include_once"../../config.php";
    function __autoload($classe){
        require_once"../../classes/".$classe.'.class.php';
    }
    @BD::conn();
    $login = new Login('adm_','adm');
    $site = new Site();
    $val = new Validacao();

    if(!$login->isLogado()){
        header("Location: ../");
        exit;
    }else{
        $pegar_dados = @BD::conn()->prepare("SELECT * FROM `adm` WHERE email_log = ? AND senha_log = ?");
		$pegar_dados->execute(array($_SESSION['adm_emailLog'], $_SESSION['adm_senhaLog']));
		$usuarioLogado = $pegar_dados->fetchObject();
    }
    if(isset($_GET['acao']) && $_GET['acao'] == 'sair'):
        if($login->deslogar()){
            header("Location: ../");
        }
    endif;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Pitylu - Painel de administração</title>
    <link rel="stylesheet" href="css/style_painel.css" type="text/css" media="screen">
    <!--<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript" src="js/botoes.js"></script>-->
    
    <script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript" src="../../js/jquery.js"></script>
    <script type="text/javascript" src="js/botoes.js"></script>
    <script type="text/javascript" src="../../js/price.js"></script>
    <script type="text/javascript" src="js/functions.js"></script>
   
    <?php if(!isset($_GET['pagina'])):?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          <?php
		 	$sqlVendas = @BD::conn()->prepare("SELECT SUM(valor_total) AS total_venda FROM `pedidos` 
            WHERE TO_DAYS(NOW()) - TO_DAYS(criado) <= 90 GROUP BY MONTH(criado)");
			$sqlVendas->execute();
            
			while($fetchVendas = $sqlVendas->fetchObject()){
		 ?>
          ['<?php echo date('M/Y',strtotime('NOW'));?>', <?php echo $fetchVendas->total_venda;?>],
		 <?php }?>
        ]);

        var options = {
          title: 'Ganho trimestral de vendas em R$',
            'width':610,
            'heigth':240
        };

        var chart = new google.visualization.PieChart(document.getElementById('grafico'));

        chart.draw(data, options);
      }
    </script>
    <?php endif;?>
</head>

<body>

    <div id="box">
        <div id="header">
            <div><h1>PITYLU - PAINEL ADMINISTRATIVO</h1></div>
        </div>
        <!--HEADER-->
        <div id="content_painel">