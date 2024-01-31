<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Comparación de Registros</title>
</head>
<body>

<div class="container mt-5">
    <h2>Registros Diferentes</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Número de employee_number</th>
                <th>Nombre Completo</th>
                <th>Detalles</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Conexión a la base de datos
            $servername = "localhost";
            $username = "root";
            $password = "root";
            $database = "ideal2";

            $conn = new mysqli($servername, $username, $password, $database);

            // Verificar la conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Consulta SQL para obtener registros diferentes con pay_slip_name
            $sql = "SELECT eslabonexcel.employee_id, eslabonexcel.pay_slip_name
                    FROM eslabonexcel
                    LEFT JOIN employees ON eslabonexcel.employee_id = employees.employee_number
                    WHERE employees.employee_number IS NULL";

            $result = $conn->query($sql);

            // Mostrar resultados en una tabla
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['employee_id'] . '</td>';
                    echo '<td>' . $row['pay_slip_name'] . '</td>';
                    echo '<td>Detalles aquí</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="3">No hay registros diferentes</td></tr>';
            }

            // Cerrar conexión
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<div class="container mt-3">
<?php
    // Botón para exportar a XLSX
    echo '<button class="btn btn-primary" onclick="exportToXlsx()">Exportar a XLSX</button>';
    echo '<button class="btn btn-success" onclick="exportar()">Exportar a PDF</button>';

    ?>
</div>

<div class="container mt-3">


<?php
    // Conexión a la base de datos nuevamente para contar registros totales
    $conn = new mysqli($servername, $username, $password, $database);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener el total de registros en la tabla "eslabonexcel"
    $totaleslabonexcel = $conn->query("SELECT COUNT(*) as total FROM eslabonexcel")->fetch_assoc()['total'];

    // Obtener el total de registros en el resultado
    $totalResult = $result->num_rows;

    // Calcular el porcentaje
    $percentage = ($totalResult / $totaleslabonexcel) * 100;

    echo '<p>Total de registros analizados: ' . $totaleslabonexcel . '</p>';
    echo '<p>Total de registros no concordantes: ' . $totalResult . '</p>';
    echo '<p>Porcentaje de no concordantes: ' . round($percentage, 2) . '%</p>';

    // Cerrar conexión
    $conn->close();
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function exportToXlsx() {
        // Crear un nuevo libro de trabajo y una hoja de cálculo
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.table_to_sheet(document.querySelector('table'));

        // Añadir la hoja de cálculo al libro de trabajo
        XLSX.utils.book_append_sheet(wb, ws, 'Registros Diferentes');

        // Guardar el libro de trabajo como un archivo XLSX
        XLSX.writeFile(wb, 'registros_diferentes.xlsx');
    }

    function exportar() {
        // Configurar opciones de PDF
        var options = {
            margin: 10,
            filename: 'registros_diferentes.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }

            
        };

        // Obtener el contenedor de la tabla
        var element = document.querySelector('table');

        // Convertir a PDF
        html2pdf(element, options);
    } 




</script>
</body>
</html>
