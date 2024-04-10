<?php
$host = 'localhost';
$user = 'postgres';
$password = '12345';
$dbname = 'pdf';
$port = '5432';

include('library/tcpdf.php');

try {
    // Connect to the PostgreSQL database using PDO
    $conn = new PDO("pgsql:host=$host;dbname=$dbname;port=$port;user=$user;password=$password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL query
    $query = "SELECT id, nombre FROM estudiantes ORDER BY nombre";

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Create a new PDF document
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('times', 'B', 12);

    // Header
    $pdf->Cell(30, 10, 'ID', 1, 0, 'C');
    $pdf->Cell(80, 10, 'Nombre', 1, 1, 'C'); // Use 1 as the last argument to move to the next line after this cell

    // Loop through the query results
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $pdf->Cell(30, 10, $row['id'], 1, 0, 'C');
        $pdf->Cell(80, 10, $row['nombre'], 1, 1, 'C'); // Move to the next line after printing 'nombre'
    }

    // Close the connection and generate the PDF
    $conn = null;
    $pdf->Output('consulta_postgresql.pdf', 'I');
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


// Definir una lista vacía
$lista = array();

// Agregar elementos a la lista
$lista[] = "Elemento 1";
$lista[] = "Elemento 2";
$lista[] = "Elemento 3";

// Acceder a elementos individuales de la lista
echo "El primer elemento es: " . $lista[0] . "<br>";
echo "El segundo elemento es: " . $lista[1] . "<br>";
echo "El tercer elemento es: " . $lista[2] . "<br>";

// Iterar sobre la lista
echo "Los elementos de la lista son: <br>";
foreach ($lista as $elemento) {
    echo $elemento . "<br>";
}




?>
