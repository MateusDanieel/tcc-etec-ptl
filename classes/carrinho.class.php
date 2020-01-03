<?php class Carrinho{
 private $pref ='pitylu_';
    
    private function existe($id){
        if(!isset($_SESSION[$this->pref.'produto'])){
            $_SESSION[$this->pref.'produto'] = array();
        }
        
        if(!isset($_SESSION[$this->pref.'produto'][$id])){
            return false;
        }else{
            return true;
        }
    }//VERIFICA A EXISTENCIA DO PRODUTO E DA SESSION COMO UM ARRAY

    public function verificaAdiciona($id){
        if(!$this->existe($id)){
            $_SESSION[$this->pref.'produto'][$id] = 1;
        }else{
            $_SESSION[$this->pref.'produto'][$id] += 1;
        }
    }//VERIFICA E ADICIONA UM PRODUTO AO CARRINHO
    
    private function prodExiste($id){
        if(isset($_SESSION[$this->pref.'produto'][$id])){
            return true;
        }else{
            return false;
        }
    }//VERIFICA SE O PRODUTO EXISTE
    
    public function deletarProduto($id){
        if(!$this->prodExiste($id)){
            return false;
        }else{
            unset($_SESSION[$this->pref.'produto'][$id]);
            return true;
        }
    }//DELETAR PRODUTO DO CARRINHO DE COMPRAS
    
    private function isArray($post){
        if(is_array($post)){
            return true;
        }else{
            return false;
        }
    }//VERIFICA SE O POST PASSADO POR PARAMETRO É OU NAO UM ARRAY
    
    public function atualizarQuantidades($post){
        if($this->isArray($post)){
            foreach($post as $id => $qtd){
                $id = (int)$id;
                $qtd = (int)$qtd;
                
                if($qtd != ''){
                    $_SESSION[$this->pref.'produto'][$id] = $qtd;
                }else{
                    unset($_SESSION[$this->pref.'produto'][$id]);
                }
            }
            return true;
        }else{
            return false;
        }//SE NAÕ FOR UM ARRAY
    }//DELETA OU ATUALZA QUANTIDADE REFERENTE A UM PRODUTO NO NOSSO CARRINHO DE COMPRAS
    
      public function setarByPost($post, $post2){
        if($this->isArray($post)){
            foreach($post as $id => $qtd){
				//aqui
				$id = $post2;
                //$id = (int)$id;
                $qtd = (int)$qtd;
	
				if(!isset($_SESSION[$this->pref.'produto'][$id])){
                    $_SESSION[$this->pref.'produto'][$id] = $qtd;
                }else{
                    $_SESSION[$this->pref.'produto'][$id] += $qtd;
                }
            }
            return true;
        }else{
            return false;
        }//SE NAÕ FOR UM ARRAY
    }
    
    public function qtdProdutos(){
		return @count($_SESSION[$this->pref.'produto']);
    }
   
    
    //FUNÇAO PARA CALCULO DO FRETE
 public function calcularFrete($cod_servico, $cep_origem, $cep_destino, $peso, $altura='2', $largura='11', $comprimento='16', $valor_declarado='0.50'){
		# Código dos Serviços dos Correios
		# 41106 PAC sem contrato
		# 40010 SEDEX sem contrato
		# 40045 SEDEX a Cobrar, sem contrato
		# 40215 SEDEX 10, sem contrato

    $correios = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=".$cep_origem."&sCepDestino=".$cep_destino."&nVlPeso=".$peso."&nCdFormato=1&nVlComprimento=".$comprimento."&nVlAltura=".$altura."&nVlLargura=".$largura."&sCdMaoPropria=n&nVlValorDeclarado=".$valor_declarado."&sCdAvisoRecebimento=n&nCdServico=".$cod_servico."&nVlDiametro=0&StrRetorno=xml";
    $xml = simplexml_load_file($correios);
            return $xml->cServico->Valor;
    
	}
}

?>
