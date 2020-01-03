<?php
if($login->isLogado()){
    //header("Location: ".PATH."/finalizar");
    echo "<script>window.location.href ='".PATH."/finalizar';</script>";
}else{
    if(isset($_POST['acao']) && $_POST['acao'] == 'logar'):
    $email = strip_tags(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
    $senha = strip_tags(filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING));
    
    if($email == '' || $senha == ''){
        echo '<script>alert("Por favor, preencha o formulário!");location.href="'.PATH.'/verificar"</script>';
    }else{
    $login->setEmail($email);
    $login->setSenha($senha);
    if($login->logar()){
        //header("Location: ".PATH."/finalizar");
        echo "<script>window.location.href = '".PATH."/finalizar';</script>";
    }else{
        echo '<script>alert("Desculpe, mas o usuário não foi encontrad");location.href="'.PATH.'/verificar"</script>';
    }
    
    }
    endif;
}
?>   

<div id="verification">
    <div class="text">
        <span class="spn-title">Ainda não é cadastrado?</span>
        <p>Se você ainda não é cadastrado em nossa loja, por favor, cadastre-se para prosseguir com o processo de compra do seu produto</p>
        <p><a href="<?php echo PATH;?>/cadastre-se">Clique aqui</a></p>
    </div><!-- TEXT -->
    
    <div class="logar">
        <span class="spn-title">Já é cadastrado? faça login!</span>
        <form action="" method="post" enctype="multipart/form-data">
          
            <label>
                <span>E-mail</span>
                <input type="text" name="email">
            </label>
            <label>
                <span>Senha</span>
                <input type="password" name="senha">
            </label>
            <input type="hidden" name="acao" value="logar">
            <p><a  href="#">Esqueceu sua senha?</a></p>   
             <input type="submit" value="Login">     
            </form>
    </div>
</div><!-- VERIFICATION -->