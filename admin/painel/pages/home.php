<div id="grafico">
                </div>
                <!--GRAFICO-->
                <h1 class="title">últimas vendas</h1>
                <table class="list" cellpadding="0" cellspacing="0" border="1px">
                    <thead>
                        <tr>
                            <th>#id</th>
                            <th>Tipo de envio</th>
                            <th>Valor total</th>
                            <th>Data de criação</th>
                            <th>Detalhes</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                       <?php
                       $dados = array('id', 'valor_total', 'status', 'criado', 'tipo_frete');
                       $site->selecionar('pedidos', $dados, false, 'id DESC LIMIT 7');
                        
                       foreach($site->listar() as $campos){
                            if($campos['status'] == 0){
                                $status = 'Pendente';
                                
                            }elseif($campos['status'] == 1){
                                $status = 'Aguardando Envio';
                                
                            }elseif($campos['status'] == 2){
                                $status = 'Produto Enviado';
                            }
                        
                        ?>
                        <tr>
                            <td><?php echo $campos['id'];?></td>
                            <td><?php echo $campos['tipo_frete'];?></td>
                            <td>R$ <?php echo number_format($campos['valor_total'],2,',','.');?></td>
                            <td><?php echo date('d/m/Y', strtotime($campos['criado']));?></td>
                            <td>
                                <a href="#" title="Detalhes"><img src="imagens/detalhes.png" borde="0"></a>
                            </td>
                            <td><?php echo $status;?></td>
                        </tr>
                    <?php }?>    
                    </tbody>
                    
                </table>
                <div id="outras_estatisticas">
                <?php
                    $selfConfigs = @BD::conn()->prepare("SELECT * FROM `loja_configs`");
                    $selfConfigs->execute();
                    $fetchConf = $selfConfigs->fetchObject();
                    $manutencao = ($fetchConf->manutencao == 0) ? 'Não' : 'Sim'; 
                    
                    //clientes
                    $clientesCad = @BD::conn()->prepare("SELECT id_cliente FROM `clientes`");
                    $clientesCad->execute();
                    
                    //categorias
                    $Cats = @BD::conn()->prepare("SELECT id FROM `categorias`");
                    $Cats->execute();
                    
                    //subcategoria
                    $subCats = @BD::conn()->prepare("SELECT id FROM `subcategorias`");
                    $subCats->execute();
                    
                    ?>
                <h1 class="title outras">Outras Estastísticas</h1>
                <table class="list" cellpadding="0" cellspacing="0" border="1">
                    <tbody>
                        <tr>
                            <td>Visitas</td>
                            <td><?php echo $fetchConf->visitas;?></td>
                        </tr>
                         <tr>
                            <td>Manutenção</td>
                            <td><?php echo $manutencao;?></td>
                        </tr>
                        <tr>
                            <td>Clientes Cadastrados</td>
                            <td><?php echo $clientesCad->rowCount();?></td>
                        </tr>
                        <tr>
                            <td>Categorias</td>
                            <td><?php echo $Cats->rowCount();?></td>
                        </tr>
                        <tr>
                            <td>Subcategorias</td>
                            <td><?php echo $subCats->rowCount();?></td>
                        </tr>
                    </tbody>
                </table>
                </div><!--OUTRAS ESTATISTICAS-->
                
                <div id="last-tickets">
                    <h1 class="title tickets">últimos tickets Abertos</h1>
                    <table class="list" cellpadding="0" cellspacing="0" border="1">
                        <thead>
                            <tr>
                                <th>Por</th>
                                <th>Descrição</th>
                                <th>Data</th>
                                <th>Ver</th>
                            </tr>
                        </thead>
                        <tbody>
                           <?php for($i = 1; $i <= 10; $i++):?>
                            <tr>
                                <td>Amauri</td>
                                <td>tudo certo</td>
                                <td>10/10/2017</td>
                                <td>
                                    <a hret="#" title="Ver"><img src="imagens/editar.png"></a>
                                </td>
                            </tr>
                            <?php endfor;?>
                        </tbody>
                    </table>
                </div>
            <!--INCLUSÃO CONTEUDO-->