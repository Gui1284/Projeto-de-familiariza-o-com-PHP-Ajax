<?php
class conexao
{

    private $host = 'localhost';
    private $dbname = 'bd_ajax-teste';
    private $user = 'root';
    private $passwd = '';


    public function connect()
    {

        try {
            $dsn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->passwd);
            return $dsn;
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
}

// $con = new Conexao();
// $conectado = $con->connect();
// var_dump($conectado);

// if ($conectado) {

//     $sql = "SELECT * FROM tb_login";
//     $result = $conectado->query($sql);
//     foreach ($result as $row) {
//         print_r($row);
//     }
// }
