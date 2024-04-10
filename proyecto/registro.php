<?php
// Conexión a la base de datos
$host = 'localhost';
$user = 'postgres';
$password = '12345';
$dbname = 'proyecto';
$port = '5432';

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname;port=$port;user=$user;password=$password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recoger los datos del formulario
        $codigo_clase = $_POST['codigo_clase'];
        $codigo_salon = $_POST['codigo_salon'];
        $dia = $_POST['dia'];
        $hora = $_POST['hora'];

        // Check if course code exists in horario table
        $stmt = $conn->prepare("SELECT * FROM horario WHERE codigocurso = :codigo_clase");
        $stmt->bindParam(':codigo_clase', $codigo_clase);
        $stmt->execute();
        $course_exists = $stmt->rowCount() > 0;

        // Check if classroom code exists in salones table
        $stmt = $conn->prepare("SELECT * FROM salones WHERE codigosalon = :codigo_salon");
        $stmt->bindParam(':codigo_salon', $codigo_salon);
        $stmt->execute();
        $classroom_exists = $stmt->rowCount() > 0;

        // Check if same day and hour combination exists in registro table
        $stmt = $conn->prepare("SELECT * FROM registro WHERE dia = :dia AND hora = :hora");
        $stmt->bindParam(':dia', $dia);
        $stmt->bindParam(':hora', $hora);
        $stmt->execute();
        $same_day_hour = $stmt->rowCount() > 0;

        if ($course_exists && $classroom_exists && !$same_day_hour) {
            // Obtener el número de estudiantes en la clase
            $stmt = $conn->prepare("SELECT numeroestu FROM horario WHERE codigocurso = :codigo_clase");
            $stmt->bindParam(':codigo_clase', $codigo_clase);
            $stmt->execute();
            $row = $stmt->fetch();
            $numeroestu = $row['numeroestu'];

            // Obtener la capacidad del salón
            $stmt = $conn->prepare("SELECT capacidad FROM salones WHERE codigosalon = :codigo_salon");
            $stmt->bindParam(':codigo_salon', $codigo_salon);
            $stmt->execute();
            $row = $stmt->fetch();
            $classroom_capacity = $row['capacidad'];
        
            // Verificar si el número de estudiantes es menor o igual a la capacidad del salón
            if ($numeroestu <= $classroom_capacity) {
                // Insertar el horario en la tabla registro
                $stmt = $conn->prepare("INSERT INTO registro (codigocurso, codigosalon, dia, hora) VALUES (:codigo_clase, :codigo_salon, :dia, :hora)");
                $stmt->bindParam(':codigo_clase', $codigo_clase);
                $stmt->bindParam(':codigo_salon', $codigo_salon);
                $stmt->bindParam(':dia', $dia);
                $stmt->bindParam(':hora', $hora);
                $stmt->execute();
            } else {
                echo "No se pudo registrar el horario. La capacidad del salón no es suficiente para la clase.";
            }
        } else {
            echo "No se pudo registrar el horario. Por favor verifique los datos.";
        }
    } elseif (isset($_POST['submit2'])) {
        //Redirection to "horario.html"
        header("Location: horario.html");
        exit;
    }
} catch(PDOException $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage();
}

$conn = null;
?>
