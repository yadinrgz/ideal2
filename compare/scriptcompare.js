$(document).ready(function() {
    // Realizar una solicitud AJAX para obtener los registros no coincidentes
    $.ajax({
      url: 'compare.php',
      type: 'GET',
      dataType: 'json',
      success: function(data) {
        // Construir la tabla HTML con los resultados
        var tableHtml = '<table class="table table-striped"><thead><tr><th>Empleado</th><th>Otro Campo</th></tr></thead><tbody>';
  
        $.each(data, function(index, row) {
          tableHtml += '<tr><td>' + row.empleado + '</td></tr>';
        });
  
        tableHtml += '</tbody></table>';
  
        // Agregar la tabla al elemento con el ID resultTable
        $('#resultTable').html(tableHtml);
      },
      error: function(error) {
        console.log('Error en la solicitud AJAX: ', error);
      }
    });
  });
  