<?php
    

    require_once("./configs/BancoDados.php");
    
    class Pessoa{
        public static function adicionarPessoa($nome, $email, $senha){
            try{
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("INSERT INTO pessoas(nome, login, senha) VALUES(?, ?, ?)"); 
                $stmt->execute([$nome, $email, $senha]); 
                
                if($stmt->rowCount() > 0){ 
                    return true; 
                }else{
                    return false;
                }

            }catch(Exception $e){
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "msg" => "Houve um erro na base de dados: " . $e.getMessage()
                ]);
                exit;
            }
 
        }

        public static function listarPessoa($id) {
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("SELECT id, nome, login, senha FROM pessoas WHERE id=?"); 
                $stmt->execute([$id]); 
                
                
                $resultado = $stmt->fetchAll();
                if(count($resultado) == 0){
                    return null;
                }
                return $resultado[0];

                }catch(Exception $e){
                    header("HTTP/1.1 500 Internal Server Error");
                    echo json_encode([
                        "msg" => "Houve um erro na base de dados: " . $e.getMessage()
                    ]);
                    exit;
                }
        }

        public static function listarPessoas(){
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("SELECT id, nome, login, senha FROM pessoas ORDER BY nome"); 
                $stmt->execute(); 
            
                $resultado = $stmt->fetchAll(); 
                if(count($resultado) == 0){
                    return null;
                }
                return $resultado;

            }catch(Exception $e){
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "msg" => "Houve um erro na base de dados: " . $e.getMessage()
                ]);
                exit;
            }

        }

        public static function atualizarPessoa($id, $nome, $login, $senha)
        {   
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("UPDATE pessoas SET nome = ?, login = ?, senha = ? WHERE id = ?");
                $stmt->execute([$nome, $login, $senha, $id]);

                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }

            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function buscarCarrosVinculados($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM carros WHERE idPessoa = ?");
                $stmt->execute([$id]);
        
                $resultado = $stmt->fetchAll();
                if (count($resultado) > 0) {
                    return $resultado;
                } else {
                    return null;
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function deletarPessoa($id){
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("DELETE FROM pessoas where id = ?"); 
                $stmt->execute([$id]); 
                
                if($stmt->rowCount() > 0){ 
                    return true; 
                }else{
                    return false;
                }    
                

            }catch(Exception $e){
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "msg" => "Houve um erro na base de dados: " . $e.getMessage()
                ]);
                exit;
            }

        }
        
    }
?>