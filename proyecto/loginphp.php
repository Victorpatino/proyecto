<?php

$host = 'localhost';
$user = 'postgres';
$password = '12345';
$dbname = 'proyecto';
$port = '5432';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $userpassword = $_POST['password'];
    $submit = $_POST['submit'];

    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname;port=$port;user=$user;password=$password");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($submit === 'login') {
            $sql = "SELECT * FROM usuarios WHERE usuario = :username AND contrasena = :password";
            $ps = $conn->prepare($sql);
            $ps->bindParam(':username', $username);
            $ps->bindParam(':password', $userpassword);
            $ps->execute();
            $result = $ps->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                
                header("location: hub.html");
                exit();
                
            } else {
                
                echo "Nombre de usuario o contraseña incorrectos";
            }
        } elseif ($submit === 'register') {
          
            $sql = "INSERT INTO usuarios (usuario, contrasena) VALUES (:username, :password)";
            $ps = $conn->prepare($sql);
            $ps->bindParam(':username', $username);
            $ps->bindParam(':password', $userpassword);
            $ps->execute();
            echo "Has sido registrado exitosamente papá!!!";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;
}

?>

