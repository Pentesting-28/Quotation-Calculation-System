<?php

require_once 'connect_mysqli.php';

// Array para almacenar errores
$errors = [];
$success = false;

// Validar si se enviaron los datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar tipo de obra
    if (!isset($_POST['type_work']) || empty($_POST['type_work'])) {
        $errors[] = "El tipo de obra es obligatorio";
    } else {
        $type_work_id = filter_var($_POST['type_work'], FILTER_VALIDATE_INT);
        if ($type_work_id === false || !in_array($type_work_id, [1, 2, 3])) {
            $errors[] = "El tipo de obra seleccionado no es válido";
        }
    }

    // Validar cantidad de metros
    if (!isset($_POST['meters']) || empty($_POST['meters'])) {
        $errors[] = "La cantidad de metros es obligatorios";
    } else {
        $quantity_meters = filter_var($_POST['meters'], FILTER_VALIDATE_INT);
        if ($quantity_meters === false || $quantity_meters <= 0) {
            $errors[] = "La cantidad de metros deben ser un número mayor a 0";
        }
    }

    // Si no hay errores, procesar los datos
    if (empty($errors)) {
        try {
            // Obtener información del tipo de obra
            $sql = "SELECT * FROM work_types WHERE id = ? LIMIT 1";
            $result = $mysqli->execute_query($sql, [$type_work_id]);
            $row = $result->fetch_assoc();

            if (!is_null($row)) {
                if ($result) {
                    // Precio unitario por metro cuadrado
                    $price_m2 = $row['m2_price'];

                    // Obtener la lista de tipos de obra
                    $work_query = $mysqli->query("SELECT * FROM work_types");
                    $list_work = $work_query->fetch_all(MYSQLI_ASSOC);

                    // Determinar el nombre del tipo de obra
                    $name_work = $list_work[$type_work_id]["type"];

                    // Calcular el precio total
                    $total_price = $price_m2 * $quantity_meters;

                    $success = true;
                    
                } else {
                    $errors[] = "Error en la consulta: " . $mysqli->error;
                    $success = false;
                }
            } else {
                $errors[] = "El tipo de obra seleccionado no existe";
                $success = false;
            }
        } catch (Exception $e) {
            $errors[] = "Error del servidor: " . $e->getMessage();
        }
    }
} else {
    $errors[] = "Método de envío no válido";
    $success = false;
}

if (!$success) {
    echo "<script>alert('" . implode("<br>", $errors) . "');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Cálculo de Cotizaciones</title>
</head>
<body>
    <h1>Sistema de Cálculo de Cotizaciones</h1>
    <h2>Tipo de obra: <?php echo $name_work; ?></h2>
    <h2>Precio unitario por metro cuadrado: <?php echo $price_m2; ?></h2>
    <h2>Cantidad de metros: <?php echo $quantity_meters; ?></h2>
    <h2>Precio total: <?php echo $total_price; ?></h2>
    <a href="index.php">Volver</a>
</body>
</html>