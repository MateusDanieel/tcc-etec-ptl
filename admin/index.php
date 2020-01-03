<?php
    session_start();
    function __autoload($classe){
        require_once "../classes/$classe".'.class.php';
    }
    include_once "../config.php";
    @BD::conn();
    $login = new Login('adm_','adm');
    
    
    if($login->isLogado()){
        header("Location: painel/index.php");
        //echo "<script>window.location.href ='".PATH."admin/painel/index.php';</script>";
    }  
?>
    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <title>Pitylu - login</title>
        <link href="style.css" rel="stylesheet" type="text/css" media="screen">
    </head>

    <body>
        <div id="box_log">
            <h1>Efetuar Login</h1>
            <?php
        if(isset($_POST['acao']) && $_POST['acao'] == 'entrar'):
            $email = strip_tags(filter_input(INPUT_POST, 'email'));
            $senha = strip_tags(filter_input(INPUT_POST, 'senha'));
                if($email == '' || $senha == ''){
                    echo '<div class="aviso">Preencha todos os campos, por favor!</div>';
                }else{
                    $login->setEmail($email);
                    $login->setSenha($senha);
                    if($login->logar()){
                        header("Location: painel/index.php");
                        //echo "<script>window.location.href ='".PATH."/admin/painel/index.php';</script>";
                    }else{
                        echo '<div class="aviso">Erro, usuário não encontrado!</div>';
                    }
                }
            endif;
    ?>
                <?php if(!$login->isLogado()){?>
                <div class="aviso">
                    <p>Para acessar o painel, você deve ter um acesso confirmado com o administrador do site</p>
                </div>
                <?php }?>
                <form action="" method="post" enctype="multipart/form-data">
                    <label>
               <span>Email</span>
               <input type="text" name="email">
           </label>
                    <label>
               <span>Senha</span>
               <input type="password" name="senha">
           </label>
                    <input type="hidden" name="acao" value="entrar">
                    <input type="submit" value="Logar">
                </form>
        </div>
    </body>

    </html>
