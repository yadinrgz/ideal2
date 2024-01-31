<?php
require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["file"])) {
    $csvFile = $_GET["file"];

    $file = fopen($csvFile, "r");

    // Leer el archivo CSV y insertar los datos en la base de datos
    while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
        $employeeNumber = $data[0];
        $name = $data[1];
        $area = $data[2];
        $position = $data[3];

        $query = "INSERT INTO employees (employee_number, name, area, position) VALUES ('$employeeNumber', '$name', '$area', '$position')";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            die("Error al insertar datos: " . mysqli_error($connection));
        }
    }

    fclose($file);
    unlink($csvFile);

    echo "Datos insertados correctamente en la base de datos.";
}
?>
