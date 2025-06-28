<?php
    require_once 'connect_mysqli.php';
    $work_query = $mysqli->query("SELECT * FROM work_types");
    $list_work = $work_query->fetch_all(MYSQLI_ASSOC);
    $mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Cálculo de Cotizaciones</title>
    <style>
        .container {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="process.php" method="post">
            <fieldset>
                <legend>Crear Cotización</legend>
                <label for="nombre">Tipo de obra:</label>
                <select name="type_work" id="type_work">
                    <option disabled selected>Seleccione tipo de obra</option>
                    <?php foreach ($list_work as $work) { ?>
                    <option value="<?php echo $work['id'];?>"><?php echo ucwords($work['type']);?></option>
                    <?php } ?>
                </select>
                <br><br>
                <label for="apellido">Metros (m²):</label>
                <input type="numbre" name="meters" id="meters" placeholder="Ingrese los metros cuadrados"><br><br>
                <button type="submit">Crear Cotización</button>
            </fieldset>
        </form>
    </div>
</body>
</html>