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

    // Consulta SQL para obtener los datos
    $sql = "SELECT numregistro, codigocurso, codigosalon, dia, hora FROM registro";
    $stmt = $conn->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Algoritmo de ordenamiento burbuja
    $n = count($results);
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            if ($results[$j]['numregistro'] > $results[$j + 1]['numregistro']) {
                // Intercambiar registros
                $temp = $results[$j];
                $results[$j] = $results[$j + 1];
                $results[$j + 1] = $temp;
            }
        }
    }

    // Devolver los datos como JSON
    header('Content-Type: application/json');
    echo json_encode($results);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>
