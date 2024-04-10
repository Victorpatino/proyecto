<?php
$host = 'localhost';
$user = 'postgres';
$password = '12345';
$dbname = 'proyecto';
$port = '5432';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar qué botón se presionó
    if (isset($_POST['submit1'])) {
        // Procesar el formulario y realizar la inserción en la base de datos
        $aulaCode = $_POST['courseCode'];
        $aulaName = $_POST['courseName'];
        $numStudents = $_POST['numStudents'];

        try {
            $conn = new PDO("pgsql:host=$host;dbname=$dbname;port=$port;user=$user;password=$password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the courseCode and courseName already exist in the database
            $sql = "SELECT * FROM salones WHERE codigosalon = :aulaCode OR nombresalon = :aulaName";
            $ps = $conn->prepare($sql);
            $ps->bindParam(':aulaCode', $aulaCode);
            $ps->bindParam(':aulaName', $aulaName);
            $ps->execute();
            $existingRecord = $ps->fetch(PDO::FETCH_ASSOC);

            if ($existingRecord) {
                // The courseCode or courseName already exist, show an error message
                echo "Error: The entered courseCode or courseName already exist in the database.";
            } else {
                // Insert the new record
                $sql = "INSERT INTO salones (codigosalon, nombresalon, capacidad) VALUES (:aulaCode, :aulaName, :numStudents)";
                $ps = $conn->prepare($sql);
                $ps->bindParam(':aulaCode', $aulaCode);
                $ps->bindParam(':aulaName', $aulaName);
                $ps->bindParam(':numStudents', $numStudents);

                $ps->execute();

                // Redireccionar después de insertar en la base de datos
                
                exit;
            }

        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $conn = null;
    } 
    exit;
}

?>