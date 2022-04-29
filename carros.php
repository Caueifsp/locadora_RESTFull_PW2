<?php   
    require_once("./configs/BancoDados.php");
    require_once("./json/header.php");
    require_once("./json/utils.php");
    require_once("./json/verbs.php");
    require_once("Pessoa.php");
    require_once("Carro.php");

    if (isMetodo("GET")) {     
        if(isset($_GET["id"])) {
            $id = $_GET["id"];
            $carro = Carro::listarCarro($id);
            if ($carro == null) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Carro não encontrado!"
                ]);
                die;
            } else {
                header("HTTP/1.1 200 OK");
                echo json_encode($carro);
                die;
            }
        } else {
            $carros = Carro::listarCarros();   
            if ($carros == null) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode([
                    "msg" => "Não há carros cadastrados!"
                ]);
                die;
            } else {
                header("HTTP/1.1 200 OK");
                echo json_encode($carros);
                die;
            }
        }
    }

    if (isMetodo("PUT")) {

            if (!parametrosValidos($_PUT, ["id", "nome", "marca", "ano", "idPessoa"])) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Parâmetros inválidos!"
                ]);
                die;
            }
            
            $id = $_PUT["id"];
            $nome = $_PUT["nome"];
            $marca = $_PUT["marca"];
            $ano = $_PUT["ano"];
            $idPessoa = $_PUT["idPessoa"];

            if (Carro::getCarro($id) == null) {
                header("HTTP/1.1 404 Not Found");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Carro não existe!"
                ]);
                die;
            }
            
            if (!filterIsInt($id)) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Parâmetro ID não é um inteiro!"
                ]);
                die;
            }

            if (emptyString($nome) || emptyString($marca) || emptyString($ano)) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Parâmetro(s) nome e/ou marca e/ou ano inválido(s)!"
                ]);
                die;
            }
            
            if (Pessoa::listarPessoa($idPessoa) == null) {
                header("HTTP/1.1 404 Not Found");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Pessoa de id $idPessoa não encontrada!"
                ]);
                die; 
            }
    
            $res = Carro::atualizarCarro($id, $nome, $marca, $ano, $idPessoa);
            if ($res) {
                header("HTTP/1.1 200 OK");
                echo json_encode([
                    "status" => "OK",
                    "msg" => "Carro editado com sucesso!"
                ]);
                die;
            } 
            else {
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Não foi possivel editar o carro!"
                ]);
                die;
            }
    }

    if (isMetodo("DELETE")) {        
        if(isset($_DELETE["id"])) {
            $id = $_DELETE["id"];
            $carro = Carro::listarCarro($id);
            if ($carro == null) {
                header("HTTP/1.1 404 Not Found");
                echo json_encode([
                    "msg" => "Carro não encontrado!"
                ]);
                die;
            } else {
                $res = Carro::deletarCarro($id); // acrescentei a função de deletar carro
                if ($res) {
                    header("HTTP/1.1 200 OK");
                    echo json_encode([
                        "status" => "OK",
                        "msg" => "Carro deletado com sucesso!"
                    ]);
                    die;
                } 
                else {
                    header("HTTP/1.1 500 Internal Server Error");
                    echo json_encode([
                        "status" => "error",
                        "msg" => "Não foi possivel deletar o carro!"
                    ]);
                    die;
                }
            }
        } 
    }

?>
    