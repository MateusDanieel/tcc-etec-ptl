<div id="cadastre-se-page">
    <h1 class="title-page-cad">FAÇA SEU CADASTRO EM NOSSA LOJA</h1>
    <h5 class="title-page-cad">atenção: os campos com '*' são obrigatórios.</h5>
<?php if(isset($_POST['acao']) && $_POST['acao'] == 'cadCliente'){
    $nome = strip_tags(filter_input(INPUT_POST, 'nome'));
    $sobrenome = strip_tags(filter_input(INPUT_POST, 'sobrenome'));
    $email = strip_tags(filter_input(INPUT_POST, 'email'));
    $telefone = strip_tags(filter_input(INPUT_POST, 'telefone'));
    $cpf = strip_tags(filter_input(INPUT_POST, 'cpf'));
    $logradouro = strip_tags(filter_input(INPUT_POST, 'logradouro'));
    $numero = strip_tags(filter_input(INPUT_POST, 'numero'));
    $complemento = strip_tags(filter_input(INPUT_POST, 'complemento'));
    $bairro = strip_tags(filter_input(INPUT_POST, 'bairro'));
    $cidade = strip_tags(filter_input(INPUT_POST, 'cidade'));
    $uf = strip_tags(filter_input(INPUT_POST, 'uf'));
    $cep = strip_tags(filter_input(INPUT_POST, 'cep'));
    $emailLog = strip_tags(filter_input(INPUT_POST, 'emailLog'));
    $senhaLog = strip_tags(filter_input(INPUT_POST, 'senhaLog'));
    
    $val = new Validacao();
    
    $val->set($nome, 'Nome')->obrigatorio();
    $val->set($sobrenome, 'Sobrenome')->obrigatorio();
    $val->set($email, 'Email')->isEmail();
    $val->set($telefone, 'Telefone')->isTel();
    $val->set($cpf, 'CPF')->isCpf();
    $val->set($logradouro, 'logradouro')->obrigatorio();
    $val->set($numero, 'Numero')->obrigatorio();
    $val->set($complemento, 'Complemento')->obrigatorio();
    $val->set($bairro, 'Bairro')->obrigatorio();
    $val->set($cidade, 'Cidade')->obrigatorio();
    $val->set($uf, 'UF')->obrigatorio();
    $val->set($cep, 'CEP')->obrigatorio();
    $val->set($emailLog, 'Email de Login')->isEmail();
    $val->set($senhaLog, 'Senha de Login')->obrigatorio();
    
    if(!$val->validar()){
        $erros = $val->getErro();
        echo '<br /><p id="aviso">Não foi possível cadastrar o usuário pois '.$erros[0].'</p><br />';
    }else{
        $verificarUsuario = @BD::conn()->prepare("SELECT id_cliente FROM `clientes` WHERE email_log = ?");
		$verificarUsuario->execute(array($emailLog));
		if($verificarUsuario->rowCount() > 0){
			echo '<p id="aviso">Já existe um usúario com esse e-mail, tente outro!</p>';
		}else{
			echo "pego";
			$dados = array('nome' => $nome,
						   'sobrenome' => $sobrenome,
						   'email' 	=> $email,
						   'telefone' => $telefone,
						   'cpf' => $cpf,
						   'logradouro' => $logradouro,
						   'numero' => $numero,
						   'complemento' => $complemento,
						   'bairro' => $bairro,
						   'cidade' => $cidade,
						   'uf' => $uf,
						   'cep' => $cep,
						   'emaillog' => $emailLog,
						   'senhalog' => $senhaLog);
				if($site->inserir('clientes', $dados)){
					if(isset($_SESSION['valor_frete'])){
						echo  '<script>alert("Cadastro realizado com sucesso, agora efetue o login para concluir sua compra");location.href="'.PATH.'/verificar"</script>';
					}else{
                        header("Location: ".PATH."");
						//echo '<p id="ok">Seu cadastro foi realizado com sucesso em nosso site!</p>';
					}
				}//se inserir
		}//else do usuario já cadastrado
    }//else do get erros
} 
?>
    <div id="cadastro_cliente">
        <form action="" method="post" enctype="multipart/form-data">
            
            <fieldset>
                <legend>DADOS PESSOAIS</legend>
                <div class="fix">
                    <label>
                        <input type="text" name="nome" placeholder=" Nome*" /><br /><br />
                    </label>

                     <label>
                        
                        <input type="text" name="sobrenome" placeholder=" Sobrenome*" />
                    </label>
                </div>
                
                 <label class="email-label">
                    <input type="text" name="email" class="email-menor" placeholder=" E-mail*"/>
                </label>

                 <label class="telefone">
                    
                    <input type="text" name="telefone" placeholder=" Telefone*" id="tel"/>
                </label>
                
                <label class="cpf">
                    <input type="text" name="cpf" placeholder=" CPF*" id="cpf"/>
                </label>
            </fieldset>
            
            <fieldset>
                <legend>ENDEREÇO</legend>
                 <label class="rua-label">
                    <input type="text" name="logradouro" placeholder=" Logradouro*" /><br /><br />
                </label>

                 <label class="num-label">
                 	<input type="text" name="numero" placeholder=" Número*" />
                </label>
                <div class="fix">
                     <label>                        
                     	<input type="text" name="complemento" placeholder=" Complemento" /><br /><br />
                     </label>

                     <label>
                        
                        <input type="text" name="bairro" placeholder=" Bairro*" />
                    </label>
                </div>
                
                <div class="fix">
                 <label>
                    
                    <input type="text" name="cidade" placeholder=" Cidade*" /><br /><br />
                </label>

                 <label>
                    
                    <input type="text" name="uf" placeholder=" UF*" />
                </label>
                </div>
                 <label>
                    
                    <input type="text" name="cep" placeholder=" CEP*" />
                </label>
            </fieldset>
            
            <fieldset>
            <legend> DADOS DE LOGIN</legend>
                <div class="fix">
                    <label>
                        <input type="text" name="emailLog"  placeholder=" E-mail*"/>
                    </label>

                    <label>
                        
                        <input type="password" name="senhaLog" placeholder=" Senha*" />
                    </label>
                </div>
            </fieldset>
            <input type="hidden" name="acao" value="cadCliente" />
            <input type="submit" value="FINALIZAR CADASTRO" class="btn-cadCliente" />
        </form>
    </div>
</div>