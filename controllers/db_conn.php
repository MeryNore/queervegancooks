<?php
# Conexión a la base de datos

# Incluir/vincular los parámetros de conexión
require_once '.env.php';

# Vinculamos la ruta absoluta al directorio config.php desde db_conn.php
require_once __DIR__ . '/../config/config.php';

# Definimos una función para realiza la conexión a la BBDD
function connetToDatabase(){
    # Crear una variable estática de conexión (static quiere decir que ese variable no varía cuando sale de la función, se queda con el último valor asignado a esa variable)
    static $mysqli_conn = null;

    if($mysqli_conn === null){
        try{
            # Crear la conexión a la BBDD
            $mysqli_conn = new mysqli(SERVER_HOST, USER, PASSWORD, DATABASE_NAME);

            # Comprobar que la conexión se haya realizado correctamente
            if ($mysqli_conn -> connect_error) {
                # Registrar el error en un archivo log
                error_log('Fallo a conectar a la base de datos: ' . $mysqli_conn -> connect_error);
                # La función devolverá un NULL para que finalice y no envíe ningún tipo de código despues
                return null;
            }
        } catch(Exception $e){
            # Registramos la excepción en un archivo log
            error_log('Error de conexión a la base de datos: ' . $e -> getMessage());
            # Para que la función muera y no ejecute absolutamente NADA MAS
            return null;
        }
    }

    # Si no es la primera vez que se conecta a la BBDD devolverá el valor que tenga de la última conexión para así evitar realizar multiples conexiones, inecesarias, a la BBDD
    return $mysqli_conn;

}

# Llamar a la función de conexión a la BBDD. ES LA QUE VAMOS A LLAMAR DESDE OTRAS UBICACIONES, se suele mantener el mismo nombre de la variable de conexión para que sea entendible y corto como para usarlo en otras vistas. NO CONFUNDIR, SON DISTINTOS ELEMENTOS aunque se reutilice el mismo nombre.
$mysqli_connection = connetToDatabase(); // Los dos posibles valores que tendríamos en esta variable serían: NULL o un objeto de conexión a la BBDD ($mysqli_conn)

# Si por cualquier motivo no se realiza la conexión, redirigiremos al usuario a una página de error
if($mysqli_connection === null){
   header('Location: ../views/errors/error500.html');
}

?>