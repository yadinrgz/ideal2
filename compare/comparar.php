<?php
$servername = "localhost";
$username = "root";
$password = "root";
$database = "ideal2";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener registros diferentes
$sql = "SELECT eslabonexcel.employee_id
        FROM empleados
        LEFT JOIN employees ON eslabonexcel.employee_id = employees.employee_number
        WHERE employees.employee_number IS NULL";

$result = $conn->query($sql);

// Mostrar resultados en una tabla
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr><td>' . $row['employee_id'] . '</td><td>Detalles aquí</td></tr>';
    }
} else {
    echo '<tr><td colspan="2">No hay registros diferentes bbbbbb</td></tr>';
}

// Cerrar conexión
$conn->close();
?>
