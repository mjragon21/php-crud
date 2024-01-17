<?php
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mystore";

    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "DELETE FROM inventory WHERE id=$id";
    $conn->query($sql);

}

    header("location: /mystore/index.php");
    exit;

?>
