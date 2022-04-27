<?php

    require_once("./configs/BancoDados.php");

    class Carro{
        public static function adicionarCarro($nome, $marca, $ano, $idPessoa){
            try{
                $conexao = Conexao::getConexao(); 

                $stmt = $conexao->prepare("INSERT INTO carros(nome, marca, ano, idPessoa) VALUES(:abacaxi, :pMarca, :pAno, :pPessoa)"); 
                $stmt->execute([
                    "pPessoa" => $idPessoa,
                    "pMarca" => $marca,
                    "abacaxi" => $nome,
                    "pAno" => $ano
                ]); 

                if($stmt->rowCount() > 0){ 
                    return true; 
                }else{
                    return false;
                }

            }catch(Exception $e){
                echo $e->getMessage();
                exit;
            } 

        }

        public static function listarCarros(){
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("SELECT c.id, c.nome, c.marca, c.ano, p.nome as nomePessoa FROM carros c JOIN pessoas p on c.idPessoa = p.id ORDER BY nome"); 
                $stmt->execute(); 
                
                $resultado = $stmt->fetchAll(); 
                return $resultado;

            }catch(Exception $e){
                echo $e->getMessage();
                exit;
            }

        }

        public static function deletarCarro($id){
            try{
                $conexao = Conexao::getConexao(); 

                $stmt = $conexao->prepare("DELETE FROM carros WHERE id=? ");
                $stmt->execute([$id]); 

                if($stmt->rowCount() > 0){ 
                    return true; 
                }else{
                    return false;
                }
            } catch(Exception $e){
                echo $e->getMessage();
            }
        }

        public static function getCarro($id){
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("SELECT id, nome, marca, ano, idPessoa  FROM carros WHERE id=? "); 
                $stmt->execute([$id]); 
                
                $resultado = $stmt->fetchAll();
                if(count($resultado) == 1){
                    return $resultado[0];
                }else{
                    return null;
                }

            }catch(Exception $e){
                echo $e->getMessage();
                exit;
            }
        }

        public static function atualizarCarro($id, $nome, $marca, $ano, $idPessoa){
            try{
                $conexao = Conexao::getConexao(); 

                $stmt = $conexao->prepare("UPDATE carros SET nome=?, marca=?, ano=?, idPessoa=? WHERE id=?");
                $stmt->execute([$nome, $marca, $ano, $idPessoa, $id]); 

                if($stmt->rowCount() > 0){ 
                    return true; 
                }else{
                    return false;
                }
            } catch(Exception $e){
                echo $e->getMessage();
            }
        }

    }
?>