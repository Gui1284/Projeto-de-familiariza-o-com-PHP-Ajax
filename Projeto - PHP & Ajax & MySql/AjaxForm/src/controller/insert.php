<?php
require_once "../config/config.php";
header('Content-Type: application/json');

if (isset($_POST["user"]) && $_POST["age"]) {

    $user = $_POST["user"];
    $age = $_POST["age"];

    $a = new newUser();
    $a->user($user, $age);
} else {
    echo json_encode(array("status_code" => 400, "msg" => "Faltando informação", "type" => "error"));
    exit;
}

class newUser
{
    public function user($user, $age)
    {
        try {
            $con = new conexao();
            $conectado = $con->connect();

            if (empty($conectado)) {
                echo json_encode(array("status_code" => 500, "msg" => "Houve um erro de conexão!"));
                exit;
            }

            $sql = "INSERT INTO usuario (user, age) VALUES (:user, :age)";

            $stmt = $conectado->prepare($sql);
            $stmt->bindValue(":user", $user);
            $stmt->bindValue(":age", $age);
            $stmt->execute();
            $rowCount = $stmt->rowCount();

            if ($rowCount > 0) {
                echo json_encode(array("status_code" => 201, "msg" => "Inserido com sucesso", "type" => "success"));
                exit;
            }
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                echo json_encode(array("status_code" => 409, "msg" => "Usuario já existe!", "type" => "error"));
                exit;
            } else {
                echo json_encode(array("status_code" => 500, "msg" => "Houve um erro de conexão!", "type" => "error"));
                exit;
            }
        }
    }
}
