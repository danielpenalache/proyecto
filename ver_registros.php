<?php
$host = 'localhost';
$user = 'postgres';
$password = '12345';
$dbname = 'login';
$port = '5432';

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname;port=$port;user=$user;password=$password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para obtener todos los registros ordenados por id_camion
    $sql = "SELECT * FROM camion ORDER BY id_camion";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Metodo de ordenamiento burbuja
    $n = count($registros);
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            if ($registros[$j]['id_camion'] > $registros[$j + 1]['id_camion']) {
                // Intercambiar registros
                $temp = $registros[$j];
                $registros[$j] = $registros[$j + 1];
                $registros[$j + 1] = $temp;
            }
        }
    }

    // Mostrar los registros ordenados en una tabla
    echo "<table border='1'>";
    echo "<tr><th>ID Camion</th><th>Conductor</th><th>Placa</th><th>Tipo de mercancía</th><th>Especifica la mercancía</th><th>Ciudad de despacho</th><th>Ciudad de destino</th><th>Hora de despacho</th><th>Cedula del exportador</th><th>Nombre del exportador</th><th>Cedula del consignatario</th><th>Nombre del consignatario</th><th>Dirección del consignatario</th></tr>";
    foreach ($registros as $registro) {
        echo "<tr>";
        echo "<td>{$registro['id_camion']}</td>";//td es para darle "interfaz" a la impresion
        echo "<td>{$registro['conductor']}</td>";
        echo "<td>{$registro['placa']}</td>";
        echo "<td>{$registro['tipo_mercancia']}</td>";
        echo "<td>{$registro['espec_merc']}</td>";
        echo "<td>{$registro['c_despacho']}</td>";
        echo "<td>{$registro['c_destino']}</td>";
        echo "<td>{$registro['hora']}</td>";
        echo "<td>{$registro['cedula_exp']}</td>";
        echo "<td>{$registro['nombre_exp']}</td>";
        echo "<td>{$registro['cedula_consig']}</td>";
        echo "<td>{$registro['nombre_consig']}</td>";
        echo "<td>{$registro['direccion']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    echo "Error al obtener los registros: " . $e->getMessage();
}
?>
