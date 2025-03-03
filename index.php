<?php
    require_once __DIR__ . '/config/config.php';

    # Comprobar si existe una sesión activa y SI no la activamos
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
    <link rel="icon" href="./favicon.png" type="image/png">
    <!-- ESTILOS CSS -->
    <link rel="stylesheet" href="./assets/css/estilos.css">
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
                <a class="navbar-brand px-4" href="#">
                    <img class="logo" src="./assets/images/QVC.jpg" alt="logo">
                    <span class="logo">Queer Vegan Cooks</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <a class="nav-link active" aria-current="page" href="#"><i class="fa-solid fa-house"></i>Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./views/noticias.php"><i class="fa-regular fa-file-lines"></i>Noticias</a>
                        </li>

                        <!-- Comprobamos si hay una sesión iniciada y el rol -->
                        <!-- SI NO está logueado -->
                        <?php if(isset($_SESSION['user_login']['idLogin'])): ?>
                            
                            <!-- SI el usuario tiene rol 'admin' se muestan: -->
                            <?php if($_SESSION['user_login']['rol'] == "admin"): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="./views/views_admins/usuarios_admin.php"><i class="fa-solid fa-pen-to-square"></i>Usuarios</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="./views/views_admins/citas_admin.php"><i class="fa-solid fa-pen-to-square"></i>Citas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="./views/views_admins/noticias_admin.php"><i class="fa-solid fa-pen-to-square"></i>Noticias</a>
                                </li>
                            <!-- SI el usuario tiene rol 'user' se muestran: -->
                            <?php elseif($_SESSION['user_login']['rol'] == 'user'): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="./views/views_users/citas.php"><i class="fa-regular fa-calendar-days"></i>Citas</a>
                                </li>
                            <?php endif; ?>

                            <!-- Opciones compartidas (para rol admin y user) -->
                            <li class="nav-item">
                                <a class="nav-link" href="./views/views_compartidas/profile.php"><i class="fa-solid fa-user"></i>Perfil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./controllers/users/cerrar_sesion.php"><i class="fa-solid fa-right-from-bracket"></i></a>
                            </li>
                        
                        <!-- Si NO ha iniciado sesión -->
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="./views/registro.php"><i class="fa-solid fa-user"></i>Registro</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./views/login.php"><i class="fa-solid fa-right-from-bracket"></i>Login</a>
                            </li>              
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <!-- CONTENIDO PÁGINA DE INICIO -->
        <div class="container-fluid p-0 m-0">

            <!-- SECCIÓN 3 CAROUSEL -->
            <section class="m-5 text-center d-block">
                <div class="container w-75">
                    <div id="carouselExampleIndicators" class="carousel slide">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="5" aria-label="Slide 6"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="6" aria-label="Slide 7"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="7" aria-label="Slide 8"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="8" aria-label="Slide 9"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="9" aria-label="Slide 10"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="10" aria-label="Slide 11"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="./assets/images/1.JPG" class="d-block w-100" alt="foto1">
                            </div>
                            <div class="carousel-item">
                                <img src="./assets/images/2.JPG" class="d-block w-100" alt="foto2">
                            </div>
                            <div class="carousel-item">
                                <img src="./assets/images/3.JPG" class="d-block w-100" alt="foto3">
                            </div>
                            <div class="carousel-item">
                                <img src="./assets/images/4.JPG" class="d-block w-100" alt="foto4">
                            </div>
                            <div class="carousel-item">
                                <img src="./assets/images/5.JPG" class="d-block w-100" alt="foto5">
                            </div>
                            <div class="carousel-item">
                                <img src="./assets/images/6.JPG" class="d-block w-100" alt="foto6">
                            </div>
                            <div class="carousel-item">
                                <img src="./assets/images/7.JPG" class="d-block w-100" alt="foto7">
                            </div>
                            <div class="carousel-item">
                                <img src="./assets/images/8.JPG" class="d-block w-100" alt="foto8">
                            </div>
                            <div class="carousel-item">
                                <img src="./assets/images/9.JPG" class="d-block w-100" alt="foto9">
                            </div>
                            <div class="carousel-item">
                                <img src="./assets/images/10.JPG" class="d-block w-100" alt="foto10">
                            </div>
                            <div class="carousel-item">
                                <img src="./assets/images/11.JPG" class="d-block w-100" alt="foto11">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="container w-75 py-5 justify-content-center">
                    <h1 class="pb-4">VEGANAS IMPERFECTAS</h1>
                    <div>
                        <p>Hola querida vegana imperfecta, así es como nos definimos nosotras. Somos dos chicas de 29 y 31 años, de Cantabria y Asturias, amantes de la naturaleza, los animales y la buena comida sin maltrato. Hace unos meses nos creamos nuestro Instagram que, para nosotras, sería un diario y una forma de enseñar a nuestros amigues y familiares lo que comíamos. Nos gusta que quien nos rodea y quien entre a nuestro perfil vea que se puede comer rico, variado y sobre todo sin hacer daño a ningún animal. Esperamos que puedas encontrar aquí lo que estés buscando, recetas, comunidad, concejos que, como veganas imperfectas, podemos darte para que no cometas los mismos errores que todes cometemos cuando no estamos informades. Gracias por leernos y por seguirnos.</p>
                    </div>
                </div>
            </section>
        
            <!-- SECCIÓN 1 PRESENTACIÓN-->
            <section class="p-5 text-center bg-warning-subtle">
                <h2 class="">COOKING</h2>
                <div class="d-flex flex-wrap flex-lg-nowrap gap-5 w-75 m-auto justify-content-center">
                    <div class="align-self-center">
                        <p class="text-center">No somos chefs ni mucho menos, lo que subimos en nuestro perfil son recetas fáciles, del día a día para mostrar que la alimentación basada en plantar puede ser versatil, divertida y deliciosa. Una de las cosas que más nos gusta es, veganizar recetas tradicionales, hacer que esos platos de toda la vida sigan en la mesa sin necesidad de ningún maltrato animal.</p>
                    </div>
                    <div>
                        <img id="ig" src="./assets/images/IG.jpg" alt="instagram">
                    </div>
                </div>
            </section>

            <!-- SECCIÓN 2 EL VEGANISMO-->
            <section class="p-5 text-center">
                <h2 class="pb-5">EL VEGANISMO</h2>
                <div class="d-flex flex-column gap-5">
                    <div>
                        <img id="veganismo" src="./assets/images/veganismo.png" alt="foto de animales">
                    </div>
                    <div class="container justify-content-center">
                        <p>Para nosotras el veganismo, lejos de ser una dieta, es una forma de vida, coherente y honesta con el planeta y los seres que contiene. Luchamos por los derechos de los animales, porque creemos que también los merecen, abogamos porque tengan derecho a no ser tratados como seres inferiores, que sepamos que no nacen para ser propiedad nuestra, ni ser utilizados para nuestro disfrute. Los animales son seres sintientes, al igual que los humanos, pueden sentir felicidad o dolor, son capaces de sentir tanto como nosotros. No hay que olvidar, que también somos animales y que no nos gustaría estar en su situación ¿verdad?<br>
                        El veganismo es una postura donde rechazamos y nos oponemos totalmente al maltrato de cualquier animal y por ello no consumimos alimentos (carnes, pescados, lácteos, huevos, etc.) o cualquier producto de origen¡ animal (prendas de vestir, cosméticos, etc.). Mucha gente tras leer esto se pregunta, ¿qué come un vegano? porque existe un desconocimiento, aunque cada vez más reducido, de este tema. Esto nos motivó para crear este perfil y mostrar la variedad de alimentos que podemos consumir, sin dejar de lado la parte nutricional, ni dejar consumir alimentos o platos tradicionales y sin maltratar a ningún ser.<br>
                        Los veganos partimos de la base de la curiosidad y las ganas de aprender, por ello, en la mayoría de los casos, somos personas que desde la inquietud queremos investigar lo que comemos y el porqué. Esta curiosidad llega a crecer tanto que a día de hoy nosotras conocemos más verduras y cereales de los que creíamos que existían, y muchos de ellos super nutritivos.</p>
                    </div>
                </div>
            </section>

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



    
    <!-- JAVASCRIPT DE BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"></script>
</body>

</html>