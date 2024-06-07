<?php
require_once "../config/config.php";
header('Content-Type: application/json');

try {
    $con = new conexao();
    $conectado = $con->connect();

    if (empty($conectado)) {
        echo json_encode(array("status_code" => 500, "msg" => "Houve um erro de conexão!"));
        exit;
    }

    $sql = "SELECT * FROM usuario";

    $stmt = $conectado->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        echo json_encode(array($stmt->fetchAll(PDO::FETCH_ASSOC)));
        exit;
    } else {
        echo json_encode(array("status_code" => 404, "msg" => "Nenhum dado a ser consultado", "type" => "error"));
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(array("status_code" => 500, "msg" => "Internal Server Error", "type" => "error"));
    exit;
}
