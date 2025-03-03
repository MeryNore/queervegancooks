<!-- Controlador que gestiona la eliminación de Citas -->

<?php
# Vincular los archivos necearios
require_once '../db_conn.php';
require_once __DIR__ . '/../../config/config.php';

# Comprobar si existe una sesión activa, si no existe, la vamos a crear/inciar una nueva sesión
if(session_status() == PHP_SESSION_NONE){
    session_start();
}


# ELIMINAR citas
if(isset($_GET['borrar_cita'])){
    
    # Recuperamos los datos de la base de datos
    $idCita = $_GET['borrar_cita'];


    # Recuperar el idUser del usuario de la sesión
    if (!isset($_SESSION['user_login']['idUser'])) {
        $_SESSION['mensaje_error'] = "Error de sesión. Inicia sesión nuevamente.";
        header('Location: ../../views/views_users/citas.php');
        exit();
    }
    # Añadimos el idUser en una variable
    $idUser = $_SESSION['user_login']['idUser'];


    # OBTENEMOS los datos de la cita desde la bbdd
    # Preparar la consulta
    $select_stmt = $mysqli_connection->prepare("SELECT fecha_cita FROM citas WHERE idCita = ?");
    # Comprobar si la consulta se preparó correctamente
    if (!$select_stmt) {
        die("Error en la consulta: " . $mysqli_connection->error);
    }
    # Vincular el parámetro (idCita)
    $select_stmt->bind_param('i', $idCita);
    # Ejecutar la consulta
    $select_stmt->execute();
    # Vincular la variable donde se guardará el resultado
    $select_stmt->bind_result($fecha_cita);
    # Obtener el resultado (fetch debe ejecutarse una vez porque solo hay un resultado)
    $select_stmt->fetch();
    # Cerrar la consulta
    $select_stmt->close();


    # Comprobamos que la cita no se ha realizado
    $fecha = new DateTime($fecha_cita);
    $hoy = new DateTime();
    if($fecha < $hoy){
        $_SESSION['mensaje_error'] = "No puede eliminar citas ya realizadas";
        header('location: ../../views/views_users/citas.php');
        exit();
    }
 
    # Intentamos realizar la ELIMINACIÓN de los datos
    try{
        $exception_error = false;
       
        # eliminar los datos en la base de datos
        $delete_stmt = $mysqli_connection->prepare('DELETE FROM citas WHERE idCita = ? AND idUser = ?');

        $delete_stmt->bind_param('ii', $idCita, $idUser);

        if($delete_stmt->execute()){
            $_SESSION['mensaje_exito'] = "Los datos se han eliminado correctamente";
            header('location: ../../views/views_users/citas.php');
            exit();
        }else{
            $_SESSION['mensaje_error'] = "Hubo un error al eliminar los datos";
            header('location: ../../views/views_users/citas.php');
            exit();
        }

        $delete_stmt->close();
        header('location: ../../views/views_users/citas.php');
        exit();
    
    }catch(Exception $e){
        error_log("Error durante el proceso de eliminación de datos: " . $e -> getMessage());
        header("Location: ../../views/errors/error500.html");
        exit();
    
    }finally{
        # Cerrar la conexión a la base de datos si aún sigue abierta.
        if(isset($mysqli_connection) && ($mysqli_connection)){
            $mysqli_connection -> close();
        }
    }

}


?>