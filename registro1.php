<?php
$host = 'localhost';
$user = 'postgres';
$password = '12345';
$dbname = 'login';
$port = '5432';

// Función para verificar si una cadena contiene solo letras
function contiene_solo_letras($cadena) {
    return preg_match('/^[A-Za-z]+$/', $cadena);
}

// Función para verificar si una cadena tiene el formato de hora adecuado (HH:MM AM/PM)
function formato_hora_valido($hora) {
    return preg_match('/^(0?[1-9]|1[0-2]):([0-5][0-9]) (AM|PM)$/', $hora);//preg_match(parametro)
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que ninguna casilla esté en blanco
    if (empty($_POST['driver-name'])) {
        echo "Por favor, completa el campo del Conductor.";   
    } elseif (preg_match('/\d/', $_POST['driver-name'])) {//pregmatch en las casillas necesarias
        echo "El nombre del conductor no puede contener números.";//mensajes por si el espacio queda vacio
    } elseif (empty($_POST['license-plate'])) {
        echo "Por favor, completa el campo de la Placa.";//mensajes por si el espacio queda vaciov
    } elseif (empty($_POST['cargo-type'])) {
        echo "Por favor, completa el campo del Tipo de mercancía.";//mensajes por si el espacio queda vacio
    } elseif (preg_match('/\d/', $_POST['cargo-type'])) {
        echo "El tipo de mercancía no puede contener números.";//mensajes por si el espacio queda vacio
    } elseif (empty($_POST['specific-cargo'])) {
        echo "Por favor, completa el campo de la Especificación de la mercancía.";//mensajes por si el espacio queda vacio
    } elseif (empty($_POST['dispatch-city'])) {
        echo "Por favor, completa el campo de la Ciudad de despacho.";//mensajes por si el espacio queda vacio
    } elseif (preg_match('/\d/', $_POST['dispatch-city'])) {
        echo "El nombre de la ciudad de despacho no puede contener números.";//mensajes por si el espacio queda vacio
    } elseif (empty($_POST['destination-city'])) {
        echo "Por favor, completa el campo de la Ciudad de destino.";//mensajes por si el espacio queda vacio
    } elseif (preg_match('/\d/', $_POST['destination-city'])) {
        echo "El nombre de la ciudad de destino no puede contener números.";//mensajes por si el espacio queda vacio
    } elseif (empty($_POST['dispatch-time'])) {
        echo "Por favor, completa el campo de la Hora de despacho.";//mensajes por si el espacio queda vacio
    } elseif (!formato_hora_valido($_POST['dispatch-time'])) {
        echo "El formato de la hora de despacho es incorrecto. Por favor, utiliza el formato HH:MM AM/PM.";//mensajes por si el espacio queda vacio
    } elseif (empty($_POST['exporter-id'])) {
        echo "Por favor, completa el campo de la Cédula del exportador.";//mensajes por si el espacio queda vacio
    } elseif (empty($_POST['exporter-name'])) {
        echo "Por favor, completa el campo del Nombre del exportador.";//mensajes por si el espacio queda vacio
    } elseif (empty($_POST['consignee-id'])) {
        echo "Por favor, completa el campo de la Cédula del consignatario.";//mensajes por si el espacio queda vacio
    } elseif (empty($_POST['consignee-name'])) {
        echo "Por favor, completa el campo del Nombre del consignatario.";//mensajes por si el espacio queda vacio
    } elseif (empty($_POST['consignee-address'])) {
        echo "Por favor, completa el campo de la Dirección del consignatario.";//mensajes por si el espacio queda vacio
    } else {
        // Procesar el formulario si todas las validaciones pasan
        try {
            $conn = new PDO("pgsql:host=$host;dbname=$dbname;port=$port;user=$user;password=$password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Obtener los datos del formulario
            $driverName = $_POST['driver-name'];
            $licensePlate = strtoupper($_POST['license-plate']); // Convertir a mayúsculas
            $cargoType = $_POST['cargo-type'];
            $specificCargo = $_POST['specific-cargo'];
            $dispatchCity = $_POST['dispatch-city'];
            $destinationCity = $_POST['destination-city'];
            $dispatchTime = $_POST['dispatch-time'];
            $exporterID = $_POST['exporter-id'];
            $exporterName = $_POST['exporter-name'];
            $consigneeID = $_POST['consignee-id'];
            $consigneeName = $_POST['consignee-name'];
            $consigneeAddress = $_POST['consignee-address'];

            // Preparar la consulta SQL para el INSERT
            $sql = "INSERT INTO camion (conductor, placa, tipo_mercancia, espec_merc, c_despacho, c_destino, hora, cedula_exp, nombre_exp, cedula_consig, nombre_consig, direccion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            // Ejecutar la consulta con los valores del formulario
            $stmt->execute([$driverName, $licensePlate, $cargoType, $specificCargo, $dispatchCity, $destinationCity, $dispatchTime, $exporterID, $exporterName, $consigneeID, $consigneeName, $consigneeAddress]);

            echo "Registro de envío agregado correctamente.";
        } catch (PDOException $e) {
            echo "Error al insertar el registro: " . $e->getMessage();
        }
    }
}
?>
