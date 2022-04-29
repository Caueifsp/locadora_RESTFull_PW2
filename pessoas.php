<?php
   
    require_once("./configs/BancoDados.php");
    require_once("./json/header.php");
    require_once("./json/utils.php");
    require_once("./json/verbs.php");
    require_once("Pessoa.php");
    
    if (isMetodo("GET")) {
        if(isset($_GET["id"])) {
            $id = $_GET["id"];
            $pessoa = Pessoa::listarPessoa($id);
            if ($pessoa == null) {
                header("HTTP/1.1 404 Not Found");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Pessoa não encontrada!"
                ]);
                die;
            } else {
                header("HTTP/1.1 200 OK");
                echo json_encode($pessoa);
                die;
            }
        } else {
            $pessoas = Pessoa::listarPessoas();
            if ($pessoas == null) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Não há pessoas cadastradas!"
                ]);
                die;
            } else {
                header("HTTP/1.1 200 OK");
                echo json_encode($pessoas);
                die;
            }
        }
    }

    if (isMetodo("PUT")) {

        if (!parametrosValidos($_PUT, ["id", "nome", "login", "senha"])) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetros inválidos!"
            ]);
            die;
        }

        $id = $_PUT["id"];
        $nome = $_PUT["nome"];
        $login = $_PUT["login"];
        $senha = $_PUT["senha"];

        if (Pessoa::listarPessoa($id) == null) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "status" => "error",
                "msg" => "Pessoa não existe!"
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

        if (emptyString($nome) || emptyString($login) || emptyString($senha)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro(s) nome e/ou login e/ou senha inválido(s)!"
            ]);
            die;
        }
        
        if (!filterIsEmail($login)){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "O login especificado não é válido!"
            ]);
            die;
        }

        if (!loginIsUnique($login)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "O login especificado não é único!"
            ]);
            die;
        }

        $res = Pessoa::atualizarPessoa($id, $nome, $login, $senha);
        if ($res) {
            header("HTTP/1.1 200 OK");
            echo json_encode([
                "status" => "OK",
                "msg" => "Pessoa atualizada com sucesso!"
            ]);
            die;
        } 
        else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Não foi possivel editar a pessoa!"
            ]);
            die;
        }

        /*if (parametrosValidos($_PUT, ["id", "nome", "login", "senha"])) {
            $id = $_PUT["id"];
            $nome = $_PUT["nome"];
            $login = $_PUT["login"];
            $senha = $_PUT["senha"];

            if (Pessoa::getPessoa($id) == null) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Pessoa não existe!"
                ]);
                die;
            }

            if (filterIsEmail($login)) {

                if (emptyString($nome) || emptyString($login) || emptyString($senha)) {
                    header("HTTP/1.1 400 Bad Request");
                    echo json_encode([
                        "status" => "error",
                        "msg" => "Parâmetros inválidos!"
                    ]);
                    die;
                }
                
                if (!loginIsUnique($login)) {
                    header("HTTP/1.1 400 Bad Request");
                    echo json_encode([
                        "status" => "error",
                        "msg" => "O Login especificado não é único!"
                    ]);
                    die;
                }

                $res = Pessoa::atualizarPessoa($id, $nome, $login, $senha);
                if ($res) {
                    header("HTTP/1.1 200 OK");
                    echo json_encode([
                        "status" => "OK",
                        "msg" => "Pessoa atualizada com sucesso!"
                    ]);
                    die;
                } else {
                    header("HTTP/1.1 400 Bad Request");
                    echo json_encode([
                        "status" => "error",
                        "msg" => "Não foi possivel editar a pessoa!"
                    ]);
                    die;
                }

            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode([
                    "status" => "error",
                    "msg" => "O login especificado não é válido!"
                ]);
                die;
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetros inválidos!"
            ]);
            die;
        }*/
    }
    
    if (isMetodo("DELETE")) {
        if(isset($_DELETE["id"])) {
            $id = $_DELETE["id"];
            $pessoa = Pessoa::listarPessoa($id);
            if ($pessoa == null) {
                header("HTTP/1.1 404 Not Found");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Pessoa não encontrada!"
                ]);
                die;
            }
            
            $pessoas = Pessoa::listarPessoas();
            $carrosVinculados = Pessoa::buscarCarrosVinculados($id); // OUTRA ALTERAÇÃO QUE FIZ
            foreach ($pessoas as $p) {
                    foreach($carrosVinculados as $carro) {
                        if ($carro["idPessoa"] == $p["id"]){    //sistema não permite remover pessoas que tenham ao menos um carro
                            header('HTTP/1.1 403 Forbidden');   // ?? tipo de reposta ideal para este caso - CORRETO
                            echo json_encode([
                                "status" => "error",
                                "msg" => "Não é possível remover uma pessoa que possua algum carro"   
                            ]);
                            die;     
                        }              
                    }
            } 
            header("HTTP/1.1 200 OK");
            echo json_encode([
                "status" => "ok"
            ]);  //requer parâmetro id no corpo da requisição
            die; 
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetros inválidos!"
            ]);
            die;
        }
    }
?>