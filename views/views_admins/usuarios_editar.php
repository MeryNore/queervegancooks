<?php
    require_once __DIR__ . '/../../config/config.php';
    require_once '../../controllers/db_conn.php';

    # Comprobar si existe una sesión activa, si no existe, la vamos a crear/inciar una nueva sesión
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    # Comprobar si el usuario está logueado y es rol admin
    if(!isset($_SESSION['user_login']['idLogin'])){
        $_SESSION['mensaje_error'] = "Debes iniciar sesión para acceder a este apartado";
        header("Location: ../login.php");
        exit();
    }

    # Recuperamos los datos de la base de datos
    $idUser = $_GET['idUser'];

    # OBTENEMOS las noticias desde la bbdd
    try{
        $select_stmt = $mysqli_connection->prepare('SELECT u.*, l.* FROM users_data u INNER JOIN users_login l ON u.idUser = l.idUser WHERE u.idUser = ?');
        $select_stmt->bind_param('i', $idUser);
        # Comprobar si se puede ejecutar la sentencia una vez preparada y se ejecuta
        if(!$select_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $select_stmt -> error);
            header("Location: ../errors/error500.html");
            exit();
        }
        # Obtener el resultado de la consulta
        $result = $select_stmt -> get_result();
        if($result -> num_rows > 0){

            $usuario = $result -> fetch_assoc();

            # Cerramos la consulta de selección
            $select_stmt->close();
        }
    }catch(Exception $e){
        error_log("Error durante el proceso de obtención de datos: " . $e -> getMessage());
        header("Location: ../errors/error500.html");
        exit();
    }finally{
        # Cerrar la conexión a la base de datos si aún sigue abierta.
        if(isset($mysqli_connection) && ($mysqli_connection)){
            $mysqli_connection -> close();
        }
    }
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vegan Queer Cooks</title>
    <!-- FABICON -->
    <link rel="icon" href="../../favicon.png" type="image/png">
    <!-- ESTILOS CSS -->
    <link rel="stylesheet" href="../../assets/css/estilos.css">
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- LIBRERÍA JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- LIBRERÍA FONTAWESOME -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"></noscript>
</head>

<body>
    <header>
        <!-- BARRA DE NAVEGACIÓN -->
        <div class="container-fluid p-0">
            <nav class="navbar navbar-expand-lg bg-black p-0 m-0 position-fixed fixed-top" data-bs-theme="dark">
                <a class="navbar-brand px-4" href="../../index.php">
                    <img class="logo" src="../../assets/images/QVC.jpg" alt="logo">
                    <span class="logo">Queer Vegan Cooks</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="../../index.php"><i class="fa-solid fa-house"></i>Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../noticias.php"><i class="fa-regular fa-file-lines"></i>Noticias</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link active" aria-current="page" href="#"><i class="fa-solid fa-pen-to-square"></i>Usuarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./citas_admin.php"><i class="fa-solid fa-pen-to-square"></i>Citas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./noticias_admin.php"><i class="fa-solid fa-pen-to-square"></i>Noticias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../views_compartidas/profile.php"><i class="fa-solid fa-user"></i>Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../controllers/users/cerrar_sesion.php"><i class="fa-solid fa-right-from-bracket"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="container-fluid">
            <!-- CONTENIDO PÁGINA REGISTRO -->
            <h2 class="my-4 text-center">EDITAR USUARIO</h2>

            <!-- MENSAJES DE ERROR O ÉXITO AL REGISTRARSE -->
            <div>
                <?php
                    # Comprobar si existe una variable de sesión con un mensaje de error o éxito
                    if(isset($_SESSION['mensaje_error'])){
                        echo '<div class="alert alert-danger text-center" role="alert">' . $_SESSION['mensaje_error'] . '</div>';
                        # Eliminar la variable de sesión con el mensaje de error para que no vuelva a aparecer, que no se quede guardado.
                        unset($_SESSION['mensaje_error']);
                    }

                    # Comprobar si existe una variable de sesión con un mensaje de éxito
                    if(isset($_SESSION['mensaje_exito'])){
                        echo '<div class="alert alert-success text-center" role="alert">' . $_SESSION['mensaje_exito'] . '</div>';
                        unset($_SESSION['mensaje_exito']);
                    }
                ?>
            </div>

            <!-- FORMULARIO -->
            <div class="container d-flex flex-wrap flex-row justify-content-center">
                <form id="form" name="form" action="../../controllers/administradores/usuarios/c_editar_user.php" method="POST">
                    <ul class="p-0">
                        <li>
                            <label for="nombre">NOMBRE</label>
                            <input type="text" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" title="Escribe sólo letras y máximo 45 caracteres" required>
                            <small class="input_error alert alert-danger p-1 m-0" role="alert"></small>
                        </li>
                        <li>
                            <label for="apellido">APELLIDOS</label>
                            <input type="text" id="apellido" name="apellido" value="<?php echo $usuario['apellido']; ?>" title="Escribe sólo letras y máximo 60 caracteres" required>
                            <small class="input_error alert alert-danger p-1 m-0" role="alert"></small>
                        </li>
                        <li>
                            <label for="email">EMAIL</label>
                            <input type="email" id="email" name="email" value="<?php echo $usuario['email']; ?>" title="correo tipo xxxx@xxxx.xxx" required>
                            <small class="input_error alert alert-danger p-1 m-0" role="alert"></small>
                        </li>
                        <li>
                            <label for="telefono">TELÉFONO</label>
                            <input type="tel" id="telefono" name="telefono" value="<?php echo $usuario['telefono']; ?>" title="Escriba sólo números y máximo 9 digitos" required>
                            <small class="input_error alert alert-danger p-1 m-0" role="alert"></small>
                        </li>
                        <li>
                            <label for="fecha_nac">FECHA DE NACIMIENTO</label>
                            <input type="date" id="fecha_nac" name="fecha_nac" value="<?php echo $usuario['fecha_nac']; ?>" title="Introduce una fecha correcta" required>
                            <small class="input_error alert alert-danger p-1 m-0" role="alert"></small>
                        </li>
                        <li>
                            <label for="direccion">DIRECCIÓN</label>
                            <input type="text" id="direccion" name="direccion" value="<?php echo $usuario['direccion']; ?>" title="Escriba una dirección válida sin caracteres especiales">
                            <small class="input_error alert alert-danger p-1 m-0" role="alert"></small>
                        </li>
                        <li>
                            <label for="sexo">GENERO</label>
                            <select name="sexo" id="sexo">
                                <option value="Mujer" <?php echo ($usuario['sexo'] == "Mujer") ? "selected" : ""; ?>>Mujer</option>
                                <option value="Hombre" <?php echo ($usuario['sexo'] == "Hombre") ? "selected" : ""; ?>>Hombre</option>
                                <option value="No Binario" <?php echo ($usuario['sexo'] == "No Binario") ? "selected" : ""; ?>>No binario</option>
                                <option value="Prefiero no decirlo" <?php echo ($usuario['sexo'] == "Prefiero no decirlo") ? "selected" : ""; ?>>Prefiero no decirlo</option>
                            </select>
                        </li>
                        <li>
                            <label for="user_password">CAMBIAR CONTRASEÑA</label>
                            <div class="d-flex flex-row align-items-center gap-2">
                                <input type="password" id="user_password" name="user_password" placeholder="Escribe la nueva contraseña (opcional)" title="La contraseña debe contener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial" autocomplete="new-password">
                                <small class="input_error alert alert-danger p-1 m-0" role="alert"></small>
                                <i id="eyepass" class="fa-regular fa-eye fa-xl icons"></i>
                            </div>
                        </li>
                        <li>
                            <label for="rol">ROL</label>
                            <select name="rol" id="rol">
                                <option value="user" <?php echo ($usuario['rol'] == "user") ? "selected" : ""; ?>>User</option>
                                <option value="admin" <?php echo ($usuario['rol'] == "admin") ? "selected" : ""; ?>>Admin</option>
                            </select>
                        </li>
                    </ul>
                    <!-- BOTONES  -->
                    <ul class="container-fluid d-flex flex-column align-items-center pt-4">
                        <li class="liButton">
                            <input type="hidden" name="idUser" value="<?php echo $idUser ?>">
                            <input type="submit" name="actualizar_usuario" value="ACTUALIZAR">
                        </li>
                    </ul>
                </form>
            </div>

            <!-- BOTÓN DE SALIR -->
            <div class="liButton container d-flex flex-wrap justify-content-center">
                <a href="./usuarios_admin.php"><input type="submit" name="salir" value="SALIR"></a>
            </div>
        </div>

    </main>


    <footer>
        <!-- PIE DE PÁGINA -->
        <div class="container-fluid py-4 bg-black" data-bs-theme="dark">
            <div id="sectionborder" class="d-flex flex-wrap justify-content-center align-items-center py-3">
                <div class="col-sm-12 col-lg-6 d-flex justify-content-center">
                    <a href="https://es.wikipedia.org/wiki/Copyright" target="_blank" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
                        <div class="text-body fs-6 text-center">&COPY; Todos los derechos reservados 2024 Company, Inc</div>
                    </a>
                </div>

                <div class="col-12 col-lg-6 d-flex justify-content-center">
                    <div class="mb-3 mb-md-0 text-body text-center fs-6">Queer Vegan Cooks - C/ Vargas, 17 - Santander - Cantabria</div>
                </div>

                <ul class="nav list-unstyled d-flex justify-content-center my-2">
                    <li class="ms-3">
                        <a class="text-body" href="https://www.instagram.com/queervegancooks_/" target="_blank">
                            <i class="icons fa-brands fa-instagram fa-2xl"></i>
                        </a>
                    </li>
                    <li class="ms-3">
                        <a class="text-body" href="https://mail.google.com/" target="_blank">
                            <i class="icons fa-solid fa-envelope fa-2xl"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>


    <!-- JAVASCRIPT -->
    <script src="../../assets/scripts/v_form.js"></script>
    <script src="../../assets/scripts/show_pass.js"></script>
    <!-- JAVASCRIPT DE BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>