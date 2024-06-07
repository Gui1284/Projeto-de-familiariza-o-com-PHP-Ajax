<?php
require_once "../config/config.php";
header('Content-Type: application/json');

if (isset($_POST["id_user"])) {

    $id_user = $_POST["id_user"];

    $a = new delete_user();
    $a->user($id_user);
} else {
    echo json_encode(array("status_code" => 400, "msg" => "Faltando informação", "type" => "error"));
    exit;
}

class delete_user
{
    public function user($id_user)
    {
        try {
            $con = new conexao();
            $conectado = $con->connect();

            if (empty($conectado)) {
                echo json_encode(array("status_code" => 500, "msg" => "Houve um erro de conexão!"));
                exit;
            }

            $sql = "DELETE FROM usuario WHERE id_user=:id_user";

            $stmt = $conectado->prepare($sql);
            $stmt->bindValue(":id_user", $id_user);
            $stmt->execute();
            $rowCount = $stmt->rowCount();

            if ($rowCount > 0) {
                echo json_encode(array("status_code" => 204, "msg" => "Deletado com Sucesso", "type" => "success"));
                exit;
            } else {
                echo json_encode(array("status_code" => 500, "msg" => "Não foi possivel deletar o usuario", "type" => "error"));
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode(array("status_code" => 500, "msg" => "Internal Server Error", "type" => "error"));
            exit;
        }
    }
}
