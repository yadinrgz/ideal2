<?php
/**
 * Function to create a form for uploading a large CSV file and insert its content into a MySQL table.
 *
 * @param string $formAction The action URL for the form submission.
 * @param string $tableName The name of the MySQL table to insert the CSV content into.
 * @param string $dbHost The MySQL database host.
 * @param string $dbUser The MySQL database username.
 * @param string $dbPass The MySQL database password.
 * @param string $dbName The MySQL database name.
 *
 * @return string The HTML code for the form.
 */
function createCSVUploadForm($formAction, $tableName, $dbHost, $dbUser, $dbPass, $dbName) {
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if a file is uploaded
        if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
            // Get the temporary file path
            $csvFilePath = $_FILES['csvFile']['tmp_name'];

            // Open the CSV file for reading
            $csvFile = fopen($csvFilePath, 'r');

            // Connect to the MySQL database
            $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

            // Check if the connection is successful
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Prepare the SQL statement for inserting data into the table
            $sql = "INSERT INTO $tableName (employee_number, name, area, position) VALUES (?, ?, ?, ?)";

            // Prepare the statement
            $stmt = mysqli_prepare($conn, $sql);

            // Bind parameters to the statement
            mysqli_stmt_bind_param($stmt, 'ssss', $employee_number, $name, $area, $position);





    // Ignore the first three rows
    for ($i = 0; $i < 3; $i++) {
        fgetcsv($csvFile);
    }

            // Read the CSV file line by line
            while (($data = fgetcsv($csvFile)) !== FALSE) {
                // Extract the data from the CSV row
                $employee_number = $data[0];
                $name = $data[1];
                $area = $data[2];
                $position = $data[3];

                // Execute the statement
                mysqli_stmt_execute($stmt);
            }

            // Close the statement and the database connection
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            // Close the CSV file
            fclose($csvFile);

            // Return success message
            return "<div class='alert alert-success rounded-0 mb-3'>El documento se importó con éxito.</div> 
            <button class='btn btn-primary btn-ejecutivo btn-lg btn-block'><a href='/importexcel/' class='text-white'>SIGUIENTE</a></button>";
            
        } else {
            // Return error message if no file is uploaded
            return "Error al cargar el archivo.";
        }
    }

    // Return the HTML code for the form
    return '
        <form action="' . $formAction . '" method="post" enctype="multipart/form-data">
        <label for="fileData" class="form-label">Importar documento <strong>"empleado.csv"</strong></label>
        <input type="file" name="csvFile" id="csvFile" accept=".csv">
        <div class="card-footer py-1">
        <div class="text-center">
        <input type="submit" class="btn btn-primary rounded-pill col-lg-5 col-md-6 col-sm-12 col-xs-12" value="Importar">

        </div>
      </div>

        </form>
    ';
}

?>

<!-- Usage example -->
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Importar</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js" integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</head>
<body style="background:#9CB4CC">

<div class="container-fluid px-5 pb-2 pt-5">
    <div class="col-lg-10 col-md-10 col-sm-12 mx-auto">

<div class="card rounded-0 mb-3">

<div class="card-body rounded-0">
  <div class="container-fluid">
    <div class="col">
      <h3>ALTAS Y BAJAS</h3>
      <p>Este proceso compara las Altas y Bajas entre el documento de Eslabon "B.xlsx" vs "Empleado.csv" de INGRESSIO </p>
      <h4>Instrucciones</h4>

      <p>
      <ol>
        <li>Haga clic en el botón "Seleccionar archivo"</li>

        <li> Seleccione el documento <strong>"empleado.csv"</strong> obtenido de INGRESSIO</li>
        <li>Haga clic en importar</li>
        <li>Espere a que se muestren los resultados en la tabla</li>
        <li>Haga clic en siguiente</li>
      </ol>
      </p>
    </div>


 
    <?php
    // Set the form action URL
    $formAction = $_SERVER['PHP_SELF'];

    // Set the MySQL table name
    $tableName = "employees";

    // Set the MySQL database credentials
    $dbHost = "localhost";
    $dbUser = "root";
    $dbPass = "root";
    $dbName = "ideal2";

    // Create the CSV upload form
    echo createCSVUploadForm($formAction, $tableName, $dbHost, $dbUser, $dbPass, $dbName);
    ?>
    
  </div>
</div>

</div>
</div>
</div>



</body>
</html>