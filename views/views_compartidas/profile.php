<?php
    require_once __DIR__ . '/../../config/config.php';

    # Comprobar si existe una sesión activa, si no existe, la vamos a crear/inciar una nueva sesión
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    # Redirigir al LOGIN si el usuario no ha iniciado sesión (SI no existe idLogin). Comprobar si el usuario está logueado
    if(!isset($_SESSION['user_login']['idLogin'])){
        $_SESSION['mensaje_error'] = "Debes iniciar sesión para acceder a este apartado";
        header("Location: ../login.php");
        exit();
    }else{
        $user_data = $_SESSION['user_data'];

        #print_r($user_data);

        /*foreach($user_data as $key => $value){
            echo "$key: $value <br>";
        }*/
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
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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

                        <!-- Comprobamos si hay una sesión iniciada y el rol -->
                        <!-- SI NO está logueado -->
                        <?php if(isset($_SESSION['user_login']['idLogin'])): ?>
                            
                            <!-- SI el usuario tiene rol 'admin' se muestan: -->
                            <?php if($_SESSION['user_login']['rol'] == "admin"): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="../views_admins/usuarios_admin.php"><i class="fa-solid fa-pen-to-square"></i>Usuarios</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../views_admins/citas_admin.php"><i class="fa-solid fa-pen-to-square"></i>Citas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../views_admins/noticias_admin.php"><i class="fa-solid fa-pen-to-square"></i>Noticias</a>
                                </li>
                            <!-- SI el usuario tiene rol 'user' se muestran: -->
                            <?php elseif($_SESSION['user_login']['rol'] == 'user'): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="../views_users/citas.php"><i class="fa-regular fa-calendar-days"></i>Citas</a>
                                </li>
                            <?php endif; ?>

                            <!-- Opciones compartidas (para rol admin y user) -->
                            <li class="nav-item active">
                                <a class="nav-link active" aria-current="page" href="#"><i class="fa-solid fa-user"></i>Perfil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../controllers/users/cerrar_sesion.php"><i class="fa-solid fa-right-from-bracket"></i></a>
                            </li>
                        
                        <!-- Si NO ha iniciado sesión -->
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../registro.php"><i class="fa-solid fa-user"></i>Registro</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../login.php"><i class="fa-solid fa-right-from-bracket"></i>Login</a>
                            </li>              
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <!-- CONTENIDO PÁGINA REGISTRO -->
        <h2 class="my-4 text-center">PERFIL</h2>

        <!-- MENSAJES DE ERROR O ÉXITO ?? -->
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
        
        <!-- FORMULARIO DE DATOS -->
        <div class="container d-flex flex-wrap justify-content-center">
            <form id="profile_form" name="profile_form" action="../../controllers/users/c_profile.php" method="POST">
                <ul class="p-0">
                    <li>
                        <label for="nombre">NOMBRE</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo $user_data['nombre']; ?>" title="Escribe sólo letras y máximo 45 caracteres" required>
                        <small class="input_error alert alert-danger p-1 m-0" role="alert"></small>
                    </li>
                    <li>
                        <label for="apellido">APELLIDOS</label>
                        <input type="text" id="apellido" name="apellido" value="<?php echo $user_data['apellido']; ?>" title="Escribe sólo letras y máximo 60 caracteres" required>
                        <small class="input_error alert alert-danger p-1 m-0" role="alert"></small>
                    </li>
                    <li>
                        <label for="email">EMAIL</label>
                        <input type="email" id="email" name="email" value="<?php echo $user_data['email']; ?>" title="correo tipo xxxx@xxxx.xxx" required>
                        <small class="input_error alert alert-danger p-1 m-0" role="alert"></small>
                    </li>
                    <li>
                        <label for="telefono">TELÉFONO</label>
                        <input type="tel" id="telefono" name="telefono" value="<?php echo $user_data['telefono']; ?>" title="Escriba sólo números y máximo 9 digitos" required>
                        <small class="input_error alert alert-danger p-1 m-0" role="alert"></small>
                    </li>
                    <li>
                        <label for="fecha_nac">FECHA DE NACIMIENTO</label>
                        <input type="date" id="fecha_nac" name="fecha_nac" value="<?php echo $user_data['fecha_nac']; ?>" required>
                        <small class="input_error alert alert-danger p-1 m-0" role="alert"></small>
                    </li>
                    <li>
                        <label for="direccion">DIRECCIÓN</label>
                        <input type="text" id="direccion" name="direccion" value="<?php echo $user_data['direccion']; ?>" title="Escriba una dirección válida sin caracteres especiales">
                        <small class="input_error alert alert-danger p-1 m-0" role="alert"></small>
                    </li>
                    <li>
                        <label for="sexo">GENERO</label>
                        <select name="sexo" id="sexo">
                            <option value="Mujer" <?php echo ($user_data['sexo'] == "Mujer") ? "selected" : ""; ?>>Mujer</option>
                            <option value="Hombre" <?php echo ($user_data['sexo'] == "Hombre") ? "selected" : ""; ?>>Hombre</option>
                            <option value="No Binario" <?php echo ($user_data['sexo'] == "No Binario") ? "selected" : ""; ?>>No binario</option>
                            <option value="Prefiero no decirlo" <?php echo ($user_data['sexo'] == "Prefiero no decirlo") ? "selected" : ""; ?>>Prefiero no decirlo</option>
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
                </ul>
                <!-- BOTONES  -->
                <ul class="container-fluid d-flex flex-column align-items-center pt-4">
                    <li class="liButton">
                        <input type="submit" name="actualizar_datos" value="ACTUALIZAR">
                    </li>
                </ul>
            </form>
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
    <script src="../../assets/scripts/v_profile_form.js"></script>
    <script src="../../assets/scripts/show_pass.js"></script>
    <!-- JAVASCRIPT DE BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"></script>
</body>

</html>