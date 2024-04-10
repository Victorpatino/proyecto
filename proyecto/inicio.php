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
        $courseCode = $_POST['courseCode'];
        $courseName = $_POST['courseName'];
        $numStudents = $_POST['numStudents'];

        try {
            $conn = new PDO("pgsql:host=$host;dbname=$dbname;port=$port;user=$user;password=$password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if courseCode already exists in the database
            $sql = "SELECT * FROM horario WHERE codigocurso = :courseCode";
            $stmt_code = $conn->prepare($sql);
            $stmt_code->bindParam(':courseCode', $courseCode);
            $stmt_code->execute();
            $existingCourseByCode = $stmt_code->fetch(PDO::FETCH_ASSOC);

            // Check if courseName already exists in the database
            $sql = "SELECT * FROM horario WHERE nombrecurso = :courseName";
            $stmt_name = $conn->prepare($sql);
            $stmt_name->bindParam(':courseName', $courseName);
            $stmt_name->execute();
            $existingCourseByName = $stmt_name->fetch(PDO::FETCH_ASSOC);

            if ($existingCourseByCode || $existingCourseByName) {
                // Course code or name already exists, show an error message
                echo "Error: The course code or name already exists.";
            } else {
                // Insert the new course
                $sql = "INSERT INTO horario (codigocurso, nombrecurso, numeroestu) VALUES (:courseCode, :courseName, :numStudents)";
                $ps = $conn->prepare($sql);
                $ps->bindParam(':courseCode', $courseCode);
                $ps->bindParam(':courseName', $courseName);
                $ps->bindParam(':numStudents', $numStudents);

                $ps->execute();

                // Redireccionar después de insertar en la base de datos
                header("Location: inicio.php");
                exit;
            }

        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $conn = null;
    } elseif (isset($_POST['submit2'])) {
        // Redireccionar al presionar el botón "Volver"
        header("Location: logininter.html");
        exit;
    } elseif (isset($_POST['submit3'])) {
        // Redireccionar al presionar el botón "Siguiente"
        header("Location: salones.html");
        exit;
    }
}
?>