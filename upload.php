<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["csvFile"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Verificar si el archivo es un archivo CSV
    if ($fileType != "csv") {
        echo "Solo se permiten archivos CSV.";
        $uploadOk = 0;
    }

    // Verificar si hay errores en la subida
    if ($uploadOk == 0) {
        echo "Error al subir el archivo.";
    } else {
        if (move_uploaded_file($_FILES["csvFile"]["tmp_name"], $targetFile)) {
            header("Location: process_upload.php?file=" . $targetFile);
        } else {
            echo "Error al subir el archivo.";
        }
    }
}
?>
