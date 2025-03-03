<?php
    # Comprobar si existe una sesión activa
    if(session_status() == PHP_SESSION_NONE){
        session_start();
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
    <link rel="icon" href="../favicon.png" type="image/png">
    <!-- ESTILOS CSS -->
    <link rel="stylesheet" href="../assets/css/estilos.css">
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
                <a class="navbar-brand px-4" href="../index.php">
                    <img class="logo" src="../assets/images/QVC.jpg" alt="logo">
                    <span class="logo">Queer Vegan Cooks</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="../index.php"><i class="fa-solid fa-house"></i>Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./noticias.php"><i class="fa-regular fa-file-lines"></i>Noticias </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./registro.php"><i class="fa-solid fa-user"></i>Registro</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link active" aria-current="page" href="#"><i class="fa-solid fa-right-from-bracket"></i>Login</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <!-- CONTENIDO PÁGINA REGISTRO -->
        <h2 class="my-4 text-center">INICIAR SESIÓN</h2>

        <!-- MENSAJES DE ERROR O ÉXITO AL LOGEARSE -->
        <div>
            <?php
                # Comprobar si existe una variable de sesión con un mensaje de error
                if(isset($_SESSION['mensaje_error'])){
                    echo '<div class="alert alert-danger text-center" role="alert">' . $_SESSION['mensaje_error'] . '</div>';
                  
                    # Eliminar la variable de sesión con el mensaje de error para que no vuelva a aparecer, que no se quede guardado.
                    unset($_SESSION['mensaje_error']);
                }
            ?>
        </div>

        <!-- FORMULARIO -->
        <div class="container p-5 d-flex flex-wrap justify-content-center">
            <form id="login_form" name="form" action="../controllers/c_login.php" method="POST">
                <ul class="p-0">
                    <li>
                        <label for="user_email">USUARIO</label>
                        <input type="text" id="user_email" name="user_mail" placeholder="Email" title="correo tipo xxxx@xxxx.xxx"
                          required>
                        <small class="input_error alert alert-danger p-1 m-0" role="alert"></small>
                    </li>
                    <li>
                        <label for="user_password">CONTRASEÑA</label>
                        <div class="d-flex flex-row align-items-center gap-2">
                            <input type="password" id="user_password" name="user_password" placeholder="Escribe tu contraseña" title="La contraseña debe contener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial" required>
                            <small class="input_error alert alert-danger p-1 m-0" role="alert"></small>
                            <i id="eyepass" class="fa-regular fa-eye fa-xl icons"></i>
                        </div>
                    </li>
                    <li class="liButton d-flex flex-wrap">
                        <input type="submit" name="login" value="ENTRAR">
                        <a href="#" target="_self">¿Olvidaste tu clave?</a>
                        <a href="../views/registro.php" target="_self">¿No estás registrado? <span>REGISTRATE</span></a>
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
    <script src="../assets/scripts/v_login.js"></script>
    <script src="../assets/scripts/show_pass.js"></script>
    <!-- JAVASCRIPT DE BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"></script>
</body>

</html>