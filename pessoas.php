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
                header("HTTP/1.1 400 Bad Request");
                echo json_encode([
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
    
?>