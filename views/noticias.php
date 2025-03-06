<?php
    require_once __DIR__ . '/../config/config.php';
    require_once '../controllers/db_conn.php';

    # Comprobar si existe una sesión activa y SI no la activamos
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    # OBTENEMOS las noticias desde la bbdd
    try{
        $select_stmt = $mysqli_connection->prepare('SELECT n.*, u.nombre FROM noticias n INNER JOIN users_data u ON n.idUser = u.idUser ORDER BY fecha ASC');
        # Comprobar si se puede ejecutar la sentencia una vez preparada y se ejecuta
        if(!$select_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $select_stmt -> error);
            header("Location: ../errors/error500.html");
            exit();
        }
        # Obtener el resultado de la consulta
        $result = $select_stmt -> get_result();
        if($result -> num_rows > 0){
            # Inicializamos un array vacío para almacenar las noticias
            $noticias = [];
            # fetch_assoc() nos permite obtener los datos de las noticias como un array asociativo (clave: valor), como fetch trae sólo el primer resultado, añadimos $fila para que mientras existan datos los traiga en una fila nueva
            while ($row = $result -> fetch_assoc()){
                $noticias[] = $row;
            }
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
        <div class="container-fluid p-0" data-bs-theme="dark">
            <nav class="navbar navbar-expand-lg bg-black p-0 m-0 position-fixed fixed-top">
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
                        <li class="nav-item active">
                            <a class="nav-link active" aria-current="page" href="#"><i class="fa-regular fa-file-lines"></i>Noticias</a>
                        </li>

                        <!-- Comprobamos si hay una sesión iniciada y el rol -->
                        <!-- SI NO está logueado -->
                        <?php if(isset($_SESSION['user_login']['idLogin'])): ?>
                            
                            <!-- SI el usuario tiene rol 'admin' se muestan: -->
                            <?php if($_SESSION['user_login']['rol'] == "admin"): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="./views_admins/usuarios_admin.php"><i class="fa-solid fa-pen-to-square"></i>Usuarios</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="./views_admins/citas_admin.php"><i class="fa-solid fa-pen-to-square"></i>Citas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="./views_admins/noticias_admin.php"><i class="fa-solid fa-pen-to-square"></i>Noticias</a>
                                </li>
                            <!-- SI el usuario tiene rol 'user' se muestran: -->
                            <?php elseif($_SESSION['user_login']['rol'] == 'user'): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="./views_users/citas.php"><i class="fa-regular fa-calendar-days"></i>Citas</a>
                                </li>
                            <?php endif; ?>

                            <!-- Opciones compartidas (para rol admin y user) -->
                            <li class="nav-item">
                                <a class="nav-link" href="./views_compartidas/profile.php"><i class="fa-solid fa-user"></i>Perfil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../controllers/users/cerrar_sesion.php"><i class="fa-solid fa-right-from-bracket"></i></a>
                            </li>
                        
                        <!-- Si NO ha iniciado sesión -->
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="./registro.php"><i class="fa-solid fa-user"></i>Registro</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./login.php"><i class="fa-solid fa-right-from-bracket"></i>Login</a>
                            </li>              
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="container mb-5 w-100">
            <h1 class="my-4 text-center">NOTICIAS</h1>

            <div class="container d-flex flex-wrap gap-5 justify-content-center my-5 w-100">
                <?php if(isset($noticias)){
                    $contador = 0;
                    foreach($noticias as $noticias){
                        if ($contador > 5) break;
                        $contador++;?>
                        <div class="card w-100">
                            <img src="../assets/images/uploads/<?php echo $noticias['imagen']; ?>" class="card-img-top object-fit-cover w-100" alt="........">
                            <div class="card-body">
                                <h4><strong class="card-title"><?php echo $noticias['titulo']; ?></strong></h4>
                                <small><?php $fecha = date("d-m-Y", strtotime($noticias['fecha'])); echo $fecha; ?></small>
                                <small><?php echo $noticias['nombre'] ; ?></small>
                                <p class="card-text"><?php echo $noticias['texto']; ?></p>
                                <a href="#" class="btn btn-primary">Seguir leyendo</a>
                            </div>
                        </div>
                    <?php } ?>
                <?php }else{?>
                    <div class="alert alert-danger text-center" role="alert">No hay noticias en la base de datos</div>
                <?php } ?>
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
                    <span class="mb-3 mb-md-0 text-body text-center fs-6">Queer Vegan Cooks - C/ Vargas, 17 - Santander - Cantabria</span>
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





    <!-- JAVASCRIPT DE BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"></script>
</body>

</html>