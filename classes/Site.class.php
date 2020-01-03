<?php 
class Site extends BD{
	
		
    public function getBanners(){
        $sqlBanners = "SELECT * FROM `banners` ORDER BY id DESC ";
        return self::conn()->query($sqlBanners);
    }
    
	public function getProdutosHome($limit = false){
		if($limit == false){
		$query = "SELECT * FROM `produtos` ORDER BY id DESC";
		}else{
		$query = "SELECT * FROM `produtos` ORDER BY id DESC LIMIT $limit";
			}
		return self::conn()->query($query);
		}//PEGAR OS PRODUTOS DA HOME
	public function getProdutosViews($limit = false){
			if($limit == false){
		$query = "SELECT * FROM `produtos` ORDER BY views DESC";
		}else{
		$query = "SELECT * FROM `produtos` ORDER BY views DESC LIMIT $limit";
			}
			return self::conn()->query($query);
		}//pega os produtos mais vistos
    public function atualizarViewCat($slug){
        
        $strSQL = "UPDATE `categorias` SET views = views+1 WHERE slug = ?";
        $executar_view = self::conn()->prepare($strSQL);
        $executar_view->execute(array($slug));
        
    }//ATUALIZA VIEWS DA CATEGORIA
    
    public function atualizarViewSub($slug){
        
        $strSQL = "UPDATE `subcategorias` SET views = views+1 WHERE slug = ?";
        $executar_view = self::conn()->prepare($strSQL);
        $executar_view->execute(array($slug));
        
    }//ATUALIZA VIEWS DA SUBCATEGORIA
	public function atualizarViewProd($slug){
		$strSQL = "UPDATE `produtos` SET views = views+1 WHERE slug = ?";
		$executar_view = self::conn()->prepare($strSQL);
		$executar_view->execute(array($slug));
		}
    
    
    //seleção dinamica
	
	public function selecionar($tabela, $dados, $condicao = false, $order = false){
		$pegarValores = implode(', ', $dados);
		$contarValores = count($pegarValores);

		if($condicao == false){
			if($contarValores > 0){
				if($order != false){
					$sql = "SELECT $pegarValores FROM $tabela ORDER BY $order";
				}else{
					$sql = "SELECT $pegarValores FROM $tabela";
				}
				$this->conexao = self::conn()->prepare($sql);
				$this->conexao->execute();
				return $this->conexao;
			}
		}else{
			//existe condição para selecionar
			$pegarCondCampos = array_keys($condicao);
			$contarCondCampos = count($pegarCondCampos);
			$pegarCondValores = array_values($condicao);
			
			$sql = "SELECT $pegarValores FROM $tabela WHERE ";
			foreach($pegarCondCampos as $campoCondicao){
				$sql .= $campoCondicao." = ? AND ";
			}
			$sql = substr_replace($sql, "", -5, 5);
			
			foreach($pegarCondValores as $condValores){
				$dadosExec[] = $condValores;
			}
			if($order){$sql .= " ORDER BY $order";}
			$this->conexao = self::conn()->prepare($sql);
			$this->conexao->execute($dadosExec);
			return $this->conexao;
		}
	}
    
    public function getMenu(){
		$pegar_categorias = "SELECT * FROM `categorias` ORDER BY id ASC";
		$executar = self::conn()->prepare($pegar_categorias);
		$executar->execute();
		
		if($executar->rowCount() == 0){
		}else{
			while($categoria = $executar->fetchObject()){
				echo '<li><a href="'.PATH.'/categoria/'.$categoria->slug.'">'.$categoria->titulo.'';
					$pegar_subcategorias = "SELECT * FROM `subcategorias` WHERE id_cat = ? ORDER BY titulo ASC";
					$executar_sub = self::conn()->prepare($pegar_subcategorias);
					$executar_sub->execute(array($categoria->id));
					
					if($executar_sub->rowCount() == 0){
						echo '</li>';
					}else{
					echo '<ul class="sub-menu">';
					while($subcategoria = $executar_sub->fetchObject()){
						echo '<li><a href="'.PATH.'/categoria/'.$categoria->slug.'/'.$subcategoria->slug.'">'.$subcategoria->titulo.'</a></li>';
						}//TERMINA O WHILE DAS SUBCATEGORIAS
				echo '</ul></li>';
					}//TERMINA O ELSE DOS RESULTADOS DA SUBCATEGORIAS
				}//TERMINA O WHILE DAS CATEGORIAS
			}//TERMINA ELSE 
		}//FIM GETMENU
    
    public function listar(){
		$lista = $this->conexao->fetchAll();
		return $lista;
    }
    public function inserir($tabela, $dados){
		/*$pegarCampos = array_keys($dados);
		$contarCampos = count($pegarCampos);
		$pegarValores = array_values($dados);
		$contarValores = count($pegarValores);
		*/
		
		$nome = $_POST['nome'];
		$sobrenome = $_POST['sobrenome'];
		$email = $_POST['email'];
		$telefone = $_POST['telefone'];
		$cpf = $_POST['cpf'];
		$logradouro = $_POST['logradouro'];
		$numero = $_POST['numero'];
		$complemento = $_POST['complemento'];
		$bairro = $_POST['bairro'];
		$cidade = $_POST['cidade'];
		$uf = $_POST['uf'];
		$cep = $_POST['cep'];
		$emailLog = $_POST['emailLog'];
		$senhaLog = $_POST['senhaLog'];
		$dados[0] = $nome;
		$dados[1] = $sobrenome;
		$dados[2] = $email;
		$dados[3] = $telefone;
		$dados[4] = $cpf;
		$dados[5] = $logradouro;
		$dados[6] = $numero;
		$dados[7] = $complemento;
		$dados[8] = $bairro;
		$dados[9] = $cidade;
		$dados[10] = $uf;
		$dados[11] = $cep;
		$dados[12] = $emailLog;
		$dados[13] = $senhaLog;
		
	
			
		$sql = "INSERT INTO $tabela (`id_cliente`, `nome`, `sobrenome`, `email`, `telefone`, `cpf`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `uf`, `cep`, `email_log`, `senha_log`, `data_log`) VALUES (null, '$dados[0]','$dados[1]','$dados[2]','$dados[3]','$dados[4]','$dados[5]','$dados[6]','$dados[7]','$dados[8]','$dados[9]','$dados[10]','$dados[11]','$dados[12]','$dados[13]',NOW())";
		
		/*if($contarCampos == $contarValores){
			foreach($pegarCampos as $campo){
				$sql .= $campo.', ';
			}
			$sql = substr_replace($sql, ")", -2, 1);
			$sql .= "VALUES (";
			
			for($i = 0; $i <$contarValores; $i++){
				$sql .= "?, ";
				$i;
			}
			
			$sql = substr_replace($sql, ")", -2, 1);
		}else{
			return false;
		}
		*/


		try{
			$inserir = self::conn()->prepare($sql);
			if($inserir->execute($pegarValores)){
				return true;
			}else{
				return false;
			}
		}catch(PDOException $e){
			return false;
		}
        
	}
	public function inserirProd($nomeImg, 
					$titulo, 
					$slug, 
					$categoria, 
					$subcategoria, 
					$val_anterior, 
					$val_atual, 
					$descricao, 
					$peso, 
					$qtdEstoque, 
					$now){
		
	
			
		$sql = "INSERT INTO `produtos`(`id`, `img_padrao`, `titulo`, `slug`, `categoria`, `subcategoria`, `valor_anterior`, `valor_atual`, `descricao`, `peso`, `estoque`, `qtdVendidos`, `data`, `views`) VALUES (null,'$nomeImg','$titulo','$slug','$categoria','$subcategoria',$val_anterior,$val_atual,'$descricao','$peso',$qtdEstoque,0,NOW(),0)";
		

		try{
			$inserir = self::conn()->prepare($sql);
			if($inserir->execute($pegarValores)){
				return true;
			}else{
				return false;
			}
		}catch(PDOException $e){
			return false;
		}
        
	}
	
	
	//validar adm
	public function inserirAdm($tabela, $dados){
		//$pegarCampos = array_keys($dados);
		//$contarCampos = count($pegarCampos);
		//$pegarValores = array_values($dados);
		//$contarValores = count($pegarValores);

		$titulo = $_POST['titulo'];
		$categoria = $_POST['categoria'];
		$subcategoria = $_POST['subcategoria'];
		$descricao = $_POST['descricao'];
		$peso = $_POST['peso'];
		$dados[0] = $titulo;
		$dados[1] = $categoria;
		$dados[2] = $subcategoria;
		$dados[3] = $descricao;
		$dados[4] = $peso;

		
		
		/*$sql = "INSERT INTO $tabela (";
		if($contarCampos == $contarValores){
			foreach($pegarCampos as $campo){
				$sql .= $campo.', ';
			}
			$sql = substr_replace($sql, ")", -2, 1);
			$sql .= "VALUES (";
			
			for($i = 0; $i <$contarValores; $i++){
				$sql .= "?, ";
				$i;
			}
			
			$sql = substr_replace($sql, ")", -2, 1);
		}else{
			return false;
		}*/
		
		$sql = "INSERT INTO $tabela (`img_padrao`, `titulo`, `categoria`, `subcategoria`, `descricao`, `peso`,`data`) VALUES (NULL, '$dados[0]', '$dados[1]', '$dados[2]', '$dados[3]', '$dados[4]')";

		
	}
    
    //metodo para envio de emails junto ao phpmailer
	public function sendMail($subject, $msg, $from, $nomefrom, $destino, $nomedestino){
		require_once "mailer/class.phpmailer.php";
		$mail = new PHPMailer();//instancia a classe PHPMailer
		
		$mail->isSMTP();//habilita envio smtp
		$mail->SMTPAuth = true;//autentico o envio smtp
		$mail->Host = 'mail.seusite.com';
		$mail->Port = 'suaporta';
		
		//começar o envio do email
		$mail->Username = 'seu@email.com';
		$mail->Password = 'suasenha';
		
		$mail->From = $from;//email de quem envia
		$mail->FromName = $namefrom;//nome de quem envia
		
		$mail->isHTML(true);//seta que é html o email
		$mail->Subject = utf8_decode($subject);
		$mail->Body = utf8_decode($msg);//corpo da mensagem
		$mail->AddAddress($destino, utf8_decode($nomedestino));//seto o destino do email
		
		if($mail->Send()){
			return true;
		}else{
			return false;
		}
	}
	
function upload($tmp, $name, $nome, $larguraP, $pasta){
			   
	$ext = end(explode('.', $name));
	if($ext=='jpg' || $ext == 'JPG' || $ext == 'jpeg' || $ext == 'JPEG'){
			$img = imagecreatefromjpeg($tmp);
	}elseif($ext == 'png'){
			$img = imagecreatefrompng($tmp);
	}elseif($ext == 'gif'){
			$img = imagecreatefromgif($tmp);
	}
   	list($larg, $alt) = getimagesize($tmp);
	$x = $larg;
	$y = $alt;
	$largura = ($x>$larguraP) ? $larguraP : $x;
	$altura = ($largura*$y)/$x;
   
	if($altura>$larguraP){
			$altura = $larguraP;
			$largura = ($altura*$x)/$y;
	}
	$nova = imagecreatetruecolor($largura, $altura);
	imagecopyresampled($nova, $img, 0,0,0,0, $largura, $altura, $x, $y);
   
	imagejpeg($nova, $pasta.$nome);
	imagedestroy($img);
	imagedestroy($nova);
	return (file_exists($pasta.$nome)) ? true : false;
}
    
}//FIM CLASS


?>
