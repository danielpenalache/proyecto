<?php
$host = 'localhost';
$user = 'postgres';
$password = '12345';
$dbname = 'login';
$port = '5432';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar las credenciales del usuario
    if ($_POST['username'] === 'usuario' && $_POST['password'] === 'contraseña') {
        // Iniciar sesión y redirigir al usuario a proyecto4.html
        session_start();
        $_SESSION['username'] = $_POST['username'];
        header('Location: proyecto4.html');
        exit();
    }

    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname;port=$port;user=$user;password=$password");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Aquí definimos las variables $username, $userpassword y $submit
        $username = $_POST['username'];
        $userpassword = $_POST['password'];
        $submit = $_POST['submit'];

        if ($submit === 'login') {
            $sql = "SELECT * FROM estudiante WHERE usuario = :username AND contrasena = :password";
            $ps = $conn->prepare($sql);
            $ps->bindParam(':username', $username);       //bindparam vincula valores de una consulta preparada
            $ps->bindParam(':password', $userpassword);

            $ps->execute();
            $rs = $ps->fetch(PDO::FETCH_ASSOC);

            if ($rs) {
                // No necesitamos imprimir "Credenciales inválidas" aquí
                // Redirigir al usuario a proyecto4.html
                session_start();
                $_SESSION['username'] = $rs['usuario'];
                header('Location: proyecto4.html');
                exit();
            } else {
                echo "Usuario o contraseña inválidos";
            }
        } elseif ($submit === 'register') {
            $sql_register = "INSERT INTO estudiante (usuario, contrasena) VALUES (:username, :password)";
            $ps_register = $conn->prepare($sql_register);
            $ps_register->bindParam(':username', $username);             //bindparam vincula valores de una consulta preparada
            $ps_register->bindParam(':password', $userpassword);

            $result_register = $ps_register->execute();    //almacena el registro

            if ($result_register) {
                echo "Usuario registrado correctamente";
            } else {                                         //muestra el mensaje dependiendo del registro
                echo "Error al registrar el usuario";
            }
        }
    } catch (PDOException $th) {
        echo $th;
    }
}

?>
