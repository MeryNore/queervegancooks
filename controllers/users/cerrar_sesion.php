<?php
    require_once __DIR__ . '/../../config/config.php';

    # Iniciamos la sesión para acceder a las variables de sesión
    session_start();

    # Limpiear TODAS las variables de sesión
    $_SESSION = array();

    # SI destruimos la sesión completa, se destruirán las variables de sesión y la cookie de sesión
    # NOTA: DESTRUIMOS LA SESIÓN JUNTO CON SU INFORMACIÓN
    if(ini_get("session.use_cookies")){
        $params = session_get_cookie_params();
        
        setcookie(session_name(), '', time() - 42000, 
            $params['path'], $params['domain'], 
            $params['secure'], $params['httponly']
        );
    }

    // Finalmente destruimos la sesión
    session_destroy();

    // Redirigimos al usuario a la página de inicio
    header("Location: ../../index.php");
    exit();
?>