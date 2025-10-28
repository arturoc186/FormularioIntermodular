<?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Aquí recupero y proceso los datos enviados por el formulario.
            // Para cifrar la contraseña he optado por el cifrado ROT13, que
            // cambia la letra actual por la que está 13 posiciones adelante
            // No es un cifrado seguro, pero me sirve para hacer este ejemplo.

            $nombre = trim($_POST['nombre'] ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $conf_password = trim($_POST['conf-password'] ?? '');
            $password_cifrada = str_rot13($password);
            $fecha_nacimiento = trim($_POST['fecha-nacimiento'] ?? '');
            $pais = trim($_POST['pais'] ?? '');
            $acepto = isset($_POST['acepto']);


            // Aunque los campos no pueden ser vacíos, ya que vienen regulados por el tag
            // required y por el type de HTML, valido las entradas también en el servidor.
            // Para esto, creo una variable booleana que me indicará si todo es correcto.

            $valido = true;

            if (empty($nombre)){ 
                echo("El campo del nombre no puede estar vacío."); 
                $valido = false;
            }

            if (empty($apellidos)){ 
                echo("El campo de apellidos no puede estar vacío."); 
                $valido = false;
            }

            if (empty($email)) {
                echo "<p>El correo electrónico es obligatorio.</p>";
                $valido = false;
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<p>El formato del correo electrónico no es válido.</p>";
                $valido = false;
            }

            if (strlen($password) < 8) {
                echo "<p>La contraseña debe tener al menos 8 caracteres.</p>";
                $valido = false;
            }

            if ($password !== $conf_password) {
                echo "<p>Las contraseñas no coinciden.</p>";
                $valido = false;
            }

            if (!$acepto) {
                echo("<p>Debes aceptar los términos y condiciones para seguir.</p>");
                $valido = false;
            }

            
            // Consultando medidas de seguridad adicionales, he decidido el hashear la
            // contraseña con el algoritmo SHA-256. Para esto he hecho una función que
            // recibe la contraseña, usando la función hash de PHP, y devuelve el hash.
            // He aprendido que funciona: hash("algoritmo", $cadena, binario_o_no);

            function hashearContrasena($password) {
                 $hash_generado = hash("sha256", $password, false);
                 return $hash_generado;
            }

            $hash_contrasena = hashearContrasena($password);


            // Arriba he validado todos los casos en los que el registro no se llevaría a
            // cabo. Si la variable valido fuera falsa, se habría dado alguno de los casos.
            // La función exit funciona para finalizar el script PHP, lo que detendría la 
            // ejecución del script en caso de éxito, y quitaría el formulario de la pantalla 

            if ($valido === true){
                echo("<h2>¡Bienvenido/a, $nombre!</h2>");

                echo "<h3>Estos son tus datos:</h3>";
                echo "<p>Nombre: ".htmlspecialchars($nombre)."</p>";
                echo "<p>Apellidos: ".htmlspecialchars($apellidos)."</p>";
                echo "<p>Correo: ".htmlspecialchars($email)."</p>";
                echo "<p>Contraseña: " .htmlspecialchars($password_cifrada). "</p>";
                echo "<p>Nacimiento: " .htmlspecialchars($fecha_nacimiento)."</p>";
                echo "<p>País:".htmlspecialchars($pais)."</p>";
                echo "<p>Contraseña hasheada: ". $hash_contrasena ."</p>";

                if ($acepto = 1) {
                    echo("<p>Términos: Has aceptado los términos y condiciones. </p>");
                } else {
                    echo("<p>Términos: No has aceptado los términos y condiciones. </p>");
                }                
                exit;
            } else {
                echo("<p>Revisa los errores e inténtalo de nuevo.</p>");
            }


        } else {
            echo "<p>Rellene el formulario para enviar sus datos.</p>";
        }
    ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Arturo Diseño</title>
</head>
<body>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <fieldset>
            <legend>Formulario</legend>
            <article>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </article>
            <article>
                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" required>
            </article>
            <article>
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
            </article>
            <article>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>

                <label for="conf-password">Conf Contraseña:</label>
                <input type="password" id="conf-password" name="conf-password" required>
            </article>
            <article>
                <label for="fecha-nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fecha-nacimiento" name="fecha-nacimiento" required>
            </article>
            <article>
                <label for="pais">País:</label>
                <select id="pais" name="pais" required>
                    <option value="">Seleccione un país</option>
                    <option value="ES">España</option>
                    <option value="FR">Francia</option>
                    <option value="PT">Portugal</option>
                    <option value="IT">Italia</option>
                </select>
            </article>
            <article>
                <label for="acepto">Acepto los términos y condiciones:</label>
                <input type="checkbox" id="acepto" name="acepto" required>
            </article>
            <input type="submit" value="Enviar">
        </fieldset>
    </form>
    <footer>
        <p>&copy; Designed by Arturo</p>
    </footer>
</body>
</html>
