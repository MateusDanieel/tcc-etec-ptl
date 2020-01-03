<h1 class="title">Cadastrar novo Produto</h1>
<?php
if(isset($_POST['acao']) && $_POST['acao'] == 'cadastrar'):
	$site = new Site();
include_once "inc/slug.php";
	$img_padrao = $_FILES['img_padrao'];
	$titulo = strip_tags(filter_input(INPUT_POST, 'titulo'));
	$slug = slug($titulo);
	$categoria = $_POST['categoria'];
	$subcategoria = $_POST['subcategoria'];
	
	$val_anterior = $_POST['valAnterior'];
	$val_atual = $_POST['valAtual'];
	$descricao = htmlentities($_POST['descricao'], ENT_QUOTES);
	$peso = strip_tags(filter_input(INPUT_POST, 'peso'));
	$qtdEstoque = strip_tags(filter_input(INPUT_POST, 'qtdEstoque'));
	
	$tamanho1 = strip_tags(filter_input(INPUT_POST, 'tamanho1'));
	$estoque1 = strip_tags(filter_input(INPUT_POST, 'estoque1'));
	
	$tamanho2 = strip_tags(filter_input(INPUT_POST, 'tamanho2'));
	$estoque2 = strip_tags(filter_input(INPUT_POST, 'estoque2'));

	$tamanho3 = strip_tags(filter_input(INPUT_POST, 'tamanho3'));
	$estoque3 = strip_tags(filter_input(INPUT_POST, 'estoque3'));
	
	$tamanho4 = strip_tags(filter_input(INPUT_POST, 'tamanho4'));
	$estoque4 = strip_tags(filter_input(INPUT_POST, 'estoque4'));

	

	$verificar_slug = @BD::conn()->prepare("SELECT id FROM `produtos` WHERE slug = ?");
	$verificar_slug->execute(array($slug));
	if($verificar_slug->rowCount() > 0){
		$slug .= $verificar_slug->rowCount();
	}
	
	$val->set($titulo, 'Titulo')->obrigatorio();
	$val->set($categoria, 'Categoria')->obrigatorio();
	$val->set($subcategoria, 'Subcategoria')->obrigatorio();
	$val->set($val_atual, 'Valor Atual')->obrigatorio();
	$val->set($descricao, 'Descrição')->obrigatorio();
	$val->set($peso, 'Peso')->obrigatorio();
	$val->set($qtdEstoque, 'Quantidade em Estoque')->obrigatorio();
	$val->set($tamanho1, 'tamanho1')->obrigatorio();
	$val->set($estoque1, 'estoque1')->obrigatorio();
	
	if(!$val->validar()){
		$erro = $val->getErro();
		echo '<div class="erros">Erro: '.$erro[0].'</div>';
	}elseif($img_padrao['error'] == '4'){
		echo '<div class="erros">Informe uma imagem padrão para o produto!</div>';
	}else{
	$nomeImg = md5(uniqid(rand(), true)).$img_padrao['name'];
	$site->upload($img_padrao['tmp_name'], $img_padrao['name'], $nomeImg, '350', '../../produtos_img/');
	$now = date('Y-m-d H:i:s');
	
	$dados = array('img_padrao' => $nomeImg, 
					'titulo' => $titulo, 
					'slug' => $slug, 
					'categoria' => $categoria, 
					'subcategoria' => $subcategoria, 
					'valor_anterior' => $val_anterior, 
					'valor_atual' => $val_atual, 
					'descricao' => $descricao, 
					'peso' => $peso, 
					'estoque' => $qtdEstoque, 
					'qtdVendidos' => 0, 
					'data' => $now);
					echo "pego";
		if($site->inserirProd($nomeImg, 
					$titulo, 
					$slug, 
					$categoria, 
					$subcategoria, 
					$val_anterior, 
					$val_atual, 
					$descricao, 
					$peso, 
					$qtdEstoque, 
					$now)){
			$_SESSION['ultimoId'] = BD::conn()->lastInsertId();
			$idProd = $_SESSION['ultimoId'];
				if($tamanho1 != '' && $estoque1 != '' && $tamanho2 != '' && $estoque2 != '' && $tamanho3 != '' && $estoque3 != '' && $tamanho4 != '' && $estoque4 != ''){ 
					$sql = "INSERT INTO `produtos_tamanho`(`id`, `id_produto`, `tamanho`, `qtd_estoque`, `qtdVendidos`, `peso`) VALUES (null,$idProd,'$tamanho1',$estoque1,0,'$peso')";
					$inserir = BD::conn()->prepare($sql);
					$inserir->execute();
					
					$sql = "INSERT INTO `produtos_tamanho`(`id`, `id_produto`, `tamanho`, `qtd_estoque`, `qtdVendidos`, `peso`) VALUES (null,$idProd,'$tamanho2',$estoque2,0,'$peso')";
					$inserir = BD::conn()->prepare($sql);
					$inserir->execute();
					
					$sql = "INSERT INTO `produtos_tamanho`(`id`, `id_produto`, `tamanho`, `qtd_estoque`, `qtdVendidos`, `peso`) VALUES (null,$idProd,'$tamanho3',$estoque3,0,'$peso')";
					$inserir = BD::conn()->prepare($sql);
					$inserir->execute();
					
					$sql = "INSERT INTO `produtos_tamanho`(`id`, `id_produto`, `tamanho`, `qtd_estoque`, `qtdVendidos`, `peso`) VALUES (null,$idProd,'$tamanho4',$estoque4,0,'$peso')";
					$inserir = BD::conn()->prepare($sql);
					$inserir->execute();
				}
				elseif($tamanho1 != '' && $estoque1 != '' && $tamanho2 != '' && $estoque2 != '' && $tamanho3 != '' && $estoque3 != ''){ 
					$sql = "INSERT INTO `produtos_tamanho`(`id`, `id_produto`, `tamanho`, `qtd_estoque`, `qtdVendidos`, `peso`) VALUES (null,$idProd,'$tamanho1',$estoque1,0,'$peso')";
					$inserir = BD::conn()->prepare($sql);
					$inserir->execute();
					
					$sql = "INSERT INTO `produtos_tamanho`(`id`, `id_produto`, `tamanho`, `qtd_estoque`, `qtdVendidos`, `peso`) VALUES (null,$idProd,'$tamanho2',$estoque2,0,'$peso')";
					$inserir = BD::conn()->prepare($sql);
					$inserir->execute();
					
					$sql = "INSERT INTO `produtos_tamanho`(`id`, `id_produto`, `tamanho`, `qtd_estoque`, `qtdVendidos`, `peso`) VALUES (null,$idProd,'$tamanho3',$estoque3,0,'$peso')";
					$inserir = BD::conn()->prepare($sql);
					$inserir->execute();
				}
				elseif($tamanho1 != '' && $estoque1 != '' && $tamanho2 != '' && $estoque2 != ''){ 
					$sql = "INSERT INTO `produtos_tamanho`(`id`, `id_produto`, `tamanho`, `qtd_estoque`, `qtdVendidos`, `peso`) VALUES (null,$idProd,'$tamanho1',$estoque1,0,'$peso')";
					$inserir = BD::conn()->prepare($sql);
					$inserir->execute();
					
					$sql = "INSERT INTO `produtos_tamanho`(`id`, `id_produto`, `tamanho`, `qtd_estoque`, `qtdVendidos`, `peso`) VALUES (null,$idProd,'$tamanho2',$estoque2,0,'$peso')";
					$inserir = BD::conn()->prepare($sql);
					$inserir->execute();
				}
				elseif($tamanho1 != '' && $estoque1 != ''){ 
					$sql = "INSERT INTO `produtos_tamanho`(`id`, `id_produto`, `tamanho`, `qtd_estoque`, `qtdVendidos`, `peso`) VALUES (null,$idProd,'$tamanho1',$estoque1,0,'$peso')";
					$inserir = BD::conn()->prepare($sql);
					$inserir->execute();
				}
			
			
			
			
			header("Location: index.php?pagina=passo2.php");
		}
	}
	
endif;
?>
<div id="formularios">
	<form action="" method="post" enctype="multipart/form-data">
		<label class="img">
			<span class="title">Imagem Padrão</span>
			<input type="file" name="img_padrao" />
		</label>
		
		<label>
			<span class="title">Titulo do produto</span>
			<input type="text" name="titulo" />
		</label>
		
		<div class="fix">
			<label>
				<span class="title">Escolha a categoria:</span>
				<select name="categoria">
					<option value="" selected="selected">Selecione...</option>
					<?php
						$pegar_categorias = BD::conn()->prepare("SELECT * FROM `categorias` ORDER BY id DESC");
						$pegar_categorias->execute();
						while($cat = $pegar_categorias->fetchObject()){
					?>
					<option value="<?php echo $cat->slug;?>"><?php echo $cat->titulo;?></option>
					<?php }?>
				</select>
			</label>
			
			<label>
				<span class="title">Escolha a subcategoria:</span>
				<select name="subcategoria">
					<option value="" selected="selected">Selecione...</option>
				</select>
			</label>
		</div><!--- div para fixar -->
		
		<div class="fix">
		<label>
			<span class="title">Valor Anterior</span>
			<input type="text" name="valAnterior" id="preco"/>
		</label>
		<label>
			<span class="title">Valor Atual</span>
			<input type="text" name="valAtual" id="preco1"/>
		</label>
		</div><!--- div para fixar -->
		
		<label>
			<span class="title">Escreva as características deste produto</span>
			<textarea name="descricao" cols="30" rows="5" id="tiny"></textarea>
		</label>
		
		<div class="fix">
		<label>
			<span class="title">Peso do produto</span>
			<input type="text" name="peso"/>
		</label>
		<label>
			<span class="title">Quantidade em estoque</span>
			<input type="text" name="qtdEstoque" />
		</label>
		<label>
			<span class="title">Tamanho 1</span>
			<input type="text" name="tamanho1"/>
		</label>
		<label>
			<span class="title">Estoque Produto 1</span>
			<input type="text" name="estoque1" />
		</label>
			<label>
			<span class="title">Tamanho 2</span>
			<input type="text" name="tamanho2"/>
		</label>
		<label>
			<span class="title">Estoque Produto 2</span>
			<input type="text" name="estoque2" />
		</label>
			<label>
			<span class="title">Tamanho 3</span>
			<input type="text" name="tamanho3"/>
		</label>
		<label>
			<span class="title">Estoque Produto 3</span>
			<input type="text" name="estoque3"/>
		</label>
			<label>
			<span class="title">Tamanho 4</span>
			<input type="text" name="tamanho4"/>
		</label>
		<label>
			<span class="title">Estoque Produto 4</span>
			<input type="text" name="estoque4"/>
		</label>
		</div><!--- div para fixar -->
		
		<input type="hidden" name="acao" value="cadastrar" />
		<input type="submit" value="Próximo Passo" class="btn"/>
	</form>
</div>