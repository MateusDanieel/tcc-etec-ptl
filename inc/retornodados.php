<?php
include_once "../config.php";
require_once "../classes/BD.class.php";
BD::conn();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
	require_once "../classes/Site.class.php";
	$site = new Site();
	
		//recebe o post com o tipo de notificação
		$statusTransacao = $_POST['StatusTransacao'];
		//recebe o código da notificação
		$referencia = $_POST['Referencia'];
		
		if($statusTransacao == 'Aprovado'){
				
				$sql = "UPDATE `loja_pedidos` SET status = '1', modificado = NOW() WHERE id = ?";
				$executarSql = BD::conn()->prepare($sql);
				$executarSql->execute(array($referencia));
				
				$pegar_id_cliente = BD::conn()->prepare("SELECT id_cliente FROM `pedidos` WHERE id = ?");
				$pegar_id_cliente->execute(array($referencia));
				$fetchCliente = $pegar_id_cliente->fetchObject();
				
				$pegar_dados_cliente = BD::conn()->prepare("SELECT nome, sobrenome, email FROM `clientes` WHERE id_cliente = ?");
				$pegar_dados_cliente->execute(array($fetchCliente->id_cliente));
				$dadosCliente = $pegar_dados_cliente->fetchObject();
				
				//manda email para o cliente
				$msg = '
					<p>Olá senhor(a): '.$dadosCliente->nome.' '.$dadosCliente->sobrenome.' recebemos a confirmação de pagamento da sua compra em nossa loja referente a compra do id: <strong>:'.$referencia.'</strong></p>
					<p>Em breve seu produto será enviado para o endereço informado no seu cadastro, desde já agradecemos sua compra</p>
					<p>Para melhor acompanhamento do seu pedido acesse o seu painel administrativo</p>
				';
				$destino = $dadosCliente->email;
				$site->sendMail('Informações de seu pedido', $msg, 'vendas@seusite.com.br', 'Nome do seu site', $destino, $dadosCliente->nome);
				
				//manda o email para o admin
				$mensagemAdmin = '<p>Uma nova compra foi aprovada para envio na sua loja virtual, para encontrar este pedido em seu painel pesquise pelo seguinte id: '.$referencia.'</p>';
				$site->sendMail('Compra aprovada para envio', $mensagemAdmin, 'vendas@seusite.com.br', 'Sistema Seusite', 'seuemail@servidor.com', 'Administração Seu site');
		}//se for aprovado
	
	}//se receber o request post
?>