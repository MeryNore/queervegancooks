<!-- Funciones más utilizadas de la base de datos y que nos permitan realizar ciertas gestiones.
 Por ejemlo cuando desde login queremos comprobar si funciona el inicio de sesion o llamarla desde varios
 controladores -->
<?php

# Vincular los archivos más importantes. Ruta absoluta del direcotrio config.php desde db_conn.php
require_once __DIR__ . '/../config/config.php';

# Función para COMPROBAR si el usuario existe en la base de datos
function check_user($email, $mysqli_connection, &$exception_error){
    
    # Inicializar la variable de la consulta/sentencia
    $select_stmt = null;

    try{
        # Preparar la consulta/sentencia para buscar el email en la BBDD
        $select_stmt = $mysqli_connection -> prepare('SELECT email FROM users_data WHERE email = ?');

        # Comprobamos si la sentencia se ha podido preparar correctamente
        if($select_stmt === false){
            error_log('No se pudo preparar la sentencia ' . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        # Vincular los parámetros (el email) de la sentencia
        $select_stmt -> bind_param('s', $email);

        # Comprobar si se puede ejecutar la sentencia una vez preparada y se ejecuta
        if(!$select_stmt -> execute()){
            error_log('No se pudo ejecutar la sentencia ' . $select_stmt -> error);
            $exception_error = true;
            return false;
        }

        # Guardamos los resultados de la consulta/sentencia tras su ejecución (Guardarlo dentro del servidor)
        $select_stmt -> store_result();

        # Comprobar el resultado generado para saber si el email existe en la BBDD (nº de filas del resultado mayor a 0)
        # Se devuelve como resultado de la función un valor booleano
        # true si se ha encontrado que el usuario existe
        # false si no se ha encontrado el usuario en la BBDD
        return $select_stmt -> num_rows > 0;

    }catch(Exception $e){
        # Registramos la excepción en un archivo log
        error_log("Error en la función check_user: " . $e -> getMessage());
        $exception_error = true;
        # Devolver un valor FALSE para indicar que ha habido un error
        return false;
    }finally{
        # Cerrar la sentencia
        if($select_stmt !== null){
            $select_stmt -> close();
        }
    }
}


# Función para OBTENER los datos del usuario a partir del EMAIL desde USERS_LOGIN
function getUserByEmail($login_mail, $mysqli_connection, &$exception_error){
    
    # Inicializar la variable de la consulta/sentencia
    $select_stmt = null;

    # Inicializamos la variable de error asumiendo que inicialmente no hay ningún error
    $exception_error = false;

    try{
        # Preparar la consulta/sentencia para buscar el USUARIO que tenga asociado ese EMAIL en la BBDD tabla USERS_LOGIN
        $select_stmt = $mysqli_connection -> prepare('SELECT * FROM users_login WHERE usuario = ? LIMIT 1');

        # Comprobamos si la sentencia se ha podido preparar correctamente
        if($select_stmt === false){
            error_log("No se pudo preparar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        # Vincular los parámetros (el email) de la sentencia
        $select_stmt -> bind_param('s', $login_mail);

        # Comprobar si se puede ejecutar la sentencia una vez preparada y se ejecuta
        if(!$select_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $select_stmt -> error);
            $exception_error = true;
            return false;
        }

        # Obtener el resultado de la consulta
        $result = $select_stmt -> get_result();

        if($result -> num_rows > 0){
            $user_login = $result -> fetch_assoc(); # fetch_assoc() nos permite obtener los datos del resultado como un array asociativo (clave: valor)
            return $user_login;
        }else{
            // Si no se encuentra el usuario o no existe
            return false;
        }
                
    }catch(Exception $e){
        error_log("Error al ejecutar la función getUserByEmail(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    
    }finally{
        // Nos aseguramos de cerrar la sentencia si existe
        if($select_stmt !== null){
            $select_stmt -> close();
        }
    }

}


# Función para OBTENER los datos del usuario a partir del IDUSER desde USERS_DATA
function getUserDatesById($idUser, $mysqli_connection, &$exception_error){
    
    # Inicializar la variable de la consulta/sentencia
    $select_stmt = null;

    try{
        # Preparar la SENTENCIA para BUSCAR el USUARIO que tenga asociado ese ID en la BBDD tabla USERS_DATA
        $select_stmt = $mysqli_connection -> prepare('SELECT * FROM users_data WHERE idUser = ? LIMIT 1');

        # Comprobamos si la sentencia se ha podido preparar correctamente
        if($select_stmt === false){
            error_log("No se pudo preparar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        # Vincular los parámetros (idUser) de la sentencia
        $select_stmt -> bind_param('i', $idUser);

        # Comprobar si se puede ejecutar la sentencia una vez preparada y se ejecuta
        if(!$select_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $select_stmt -> error);
            $exception_error = true;
            return false;
        }

        # Obtener el resultado de la consulta
        $result = $select_stmt -> get_result();

        # Añadimos los resultados a una variable
        if($result -> num_rows > 0){
            $user_data = $result -> fetch_assoc(); # fetch_assoc() nos permite obtener los datos del resultado como un array asociativo (clave: valor)
            return $user_data;
        }else{
            // Si no se encuentra el usuario o no existe
            return false;
        }

    }catch(Exception $e){
        error_log("Error al ejecutar la función getUserDatesById(): " . $e -> getMessage());
        $exception_error = true;
        return false;

    }finally{
        // Nos aseguramos de cerrar la sentencia si existe
        if($select_stmt !== null){
            $select_stmt -> close();
        }
    }
}


# Función que permite ACTUALIZAR los datos de los usuarios
function updateUserData($idUser, $nombre, $apellido, $email, $telefono, $fecha_nac, $direccion, $sexo, $hashed_password, $mysqli_connection, &$exception_error){

    # Evitar inyecciones SQL (Convierte todos los posibles caracteres especiales en una cadena de caracteres)
    $name = $mysqli_connection -> real_escape_string($nombre);
    $surname = $mysqli_connection -> real_escape_string($apellido);
    $email = $mysqli_connection -> real_escape_string($email);
    $phone = $mysqli_connection -> real_escape_string($telefono);
    $birth_date = $mysqli_connection -> real_escape_string($fecha_nac);
    $adress = $mysqli_connection -> real_escape_string($direccion);
    $gender = $mysqli_connection -> real_escape_string($sexo);
    $new_password = $mysqli_connection -> real_escape_string($hashed_password);


    # Iniciamos la sentencia de actualización como nula
    $update_stmt = null;

    try{
        # Preparar la SENTENCIA para ACTUALIZAR los datos del usuario a través del ID en la tabla USERS_DATA
        $update_stmt = $mysqli_connection -> prepare('UPDATE users_data SET nombre = ?, apellido = ?, email = ?, telefono = ?, fecha_nac = ?, direccion = ?, sexo = ? WHERE idUser = ?');

        if(!$update_stmt){
            error_log("No se pudo preparar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }else{
            # Vincular los parámetros
            $update_stmt -> bind_param("sssisssi", $name, $surname, $email, $phone, $birth_date, $adress, $gender, $idUser);

            $result_update = $update_stmt -> execute();

            # SI la sentencia se ha podido ejecutar
            if($result_update){

                #Cerramos la sentencia
                #$update_stmt -> close();

                ################################################################################################
                ################################################################################################
                ################################################################################################

                # Preparar la SENTENCIA para ACTUALIZAR los datos del usuario a través del ID en la tabla USERS_LOGIN
                $update_login_stmt = $mysqli_connection -> prepare('UPDATE users_login SET usuario = ?, user_password = ? WHERE idUser = ?');

                if(!$update_login_stmt){
                    error_log("No se pudo preparar la sentencia update_login_stmt" . $mysqli_connection -> error);
                    $exception_error = true;
                    return false;
                }else{
                    # Vincular los parámetros
                    $update_login_stmt -> bind_param("ssi", $email, $new_password, $idUser);
            
                    # SI la sentencia se ha podido ejecutar
                    if($update_login_stmt -> execute()){
                        #Cerramos la sentencia
                        $update_login_stmt -> close();
                        return true;
                    }else{
                        error_log("Error: No se puede ejecutar la sentencia update_login_stmt" . $update_login_stmt -> error);
                        $exception_error = true;
                        return false;
                    }
                }

                ################################################################################################
                ################################################################################################
                ################################################################################################

            return true;
            
            # SI no se ha podido ejecutar la sentencia
            }else{
                error_log("Error: No se puede ejecutar la sentencia " . $update_stmt -> error);
                $exception_error = true;
                return false;
            }
        }
    
    }catch(Exception $e){
        error_log("Error al ejecutar la función updateUserData(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }    

}


# Función para COMPROBAR si el usuario tiene una cita agendada ya ese día
function check_cita($idUser, $fecha_cita, $mysqli_connection, &$exception_error){
    
    # Inicializar la variable de sentencia
    $select_stmt = null;

    try{
        # Preparar la consulta/sentencia para buscar el idUser en la BBDD
        $select_stmt = $mysqli_connection -> prepare('SELECT idUser FROM citas WHERE idUser = ? AND fecha_cita = ?');

        # Comprobamos si la sentencia se ha podido preparar correctamente
        if($select_stmt === false){
            error_log('No se pudo preparar la sentencia ' . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        # Vincular los parámetros (el email) de la sentencia
        $select_stmt -> bind_param('is', $idUser, $fecha_cita);

        # Comprobar si se puede ejecutar la sentencia una vez preparada y se ejecuta
        if(!$select_stmt -> execute()){
            error_log('No se pudo ejecutar la sentencia ' . $select_stmt -> error);
            $exception_error = true;
            return false;
        }

        # Guardamos los resultados de la consulta/sentencia tras su ejecución (Guardarlo dentro del servidor)
        $select_stmt -> store_result();

        # Comprobar el resultado generado para saber si el email existe en la BBDD (nº de filas del resultado mayor a 0)
        # Se devuelve como resultado de la función un valor booleano
        # true si se ha encontrado que el usuario existe
        # false si no se ha encontrado el usuario en la BBDD
        return $select_stmt -> num_rows > 0;

    }catch(Exception $e){
        # Registramos la excepción en un archivo log
        error_log("Error en la función check_citas: " . $e -> getMessage());
        $exception_error = true;
        # Devolver un valor FALSE para indicar que ha habido un error
        return false;
    }finally{
        # Cerrar la sentencia
        if($select_stmt !== null){
            $select_stmt -> close();
        }
    }
}


# Función para MODIFICAR citas
function modificarCita($fecha_cita, $motivo_cita, $idCita, $mysqli_connection, &$exception_error) {

    # Evitar inyecciones SQL (Convierte todos los posibles caracteres especiales en una cadena de caracteres)
    $fecha_cita = $mysqli_connection -> real_escape_string($fecha_cita);
    $motivo_cita = $mysqli_connection -> real_escape_string($motivo_cita);

    # Inicializar la variable de sentencia
    $update_stmt = null;

    try{
        # Preparar la sentencía de actualización
        $update_stmt = $mysqli_connection->prepare('UPDATE citas SET fecha_cita = ?, motivo_cita = ? WHERE idCita = ?');

        if (!$update_stmt) {
            error_log('Error preparando actualización: ' . $mysqli_connection->error);
            return false;
        }else{
             # Vincular los parámetros
            $update_stmt->bind_param('ssi', $fecha_cita, $motivo_cita, $idCita);

            # Ejecutamos la sentencia
            $result_update = $update_stmt -> execute();

            # SI la sentencia se ha podido ejecutar
            if($result_update){

                return $result_update;

            # SI no se ha podido ejecutar la sentencia
            }else{
                error_log("Error: No se puede ejecutar la sentencia " . $update_stmt -> error);
                $exception_error = true;
                return false;
            }
        }

    }catch(Exception $e){
        error_log("Error al ejecutar la función modificarCita(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        # Cerrar la sentencia si no lo está
        if($update_stmt !== null){
            $update_stmt -> close();
        }
    }

}


# Función para COMPROBAR si el TITULO de la NOTICIA existe en la base de datos
function check_title($titulo, $mysqli_connection, &$exception_error){
    
    # Inicializar la variable de la consulta/sentencia
    $select_stmt = null;

    try{
        # Preparar la consulta/sentencia para buscar el email en la BBDD
        $select_stmt = $mysqli_connection -> prepare('SELECT titulo FROM noticias WHERE titulo = ?');

        # Comprobamos si la sentencia se ha podido preparar correctamente
        if($select_stmt === false){
            error_log('No se pudo preparar la sentencia ' . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        # Vincular los parámetros (el email) de la sentencia
        $select_stmt -> bind_param('s', $titulo);

        # Comprobar si se puede ejecutar la sentencia una vez preparada y se ejecuta
        if(!$select_stmt -> execute()){
            error_log('No se pudo ejecutar la sentencia ' . $select_stmt -> error);
            $exception_error = true;
            return false;
        }

        # Guardamos los resultados de la consulta/sentencia tras su ejecución (Guardarlo dentro del servidor)
        $select_stmt -> store_result();

        # Comprobar el resultado generado para saber si el email existe en la BBDD (nº de filas del resultado mayor a 0)
        # Se devuelve como resultado de la función un valor booleano
        # true si se ha encontrado que el usuario existe
        # false si no se ha encontrado el usuario en la BBDD
        return $select_stmt -> num_rows > 0;

    }catch(Exception $e){
        # Registramos la excepción en un archivo log
        error_log("Error en la función check_title(): " . $e -> getMessage());
        $exception_error = true;
        # Devolver un valor FALSE para indicar que ha habido un error
        return false;
    }finally{
        # Cerrar la sentencia
        if($select_stmt !== null){
            $select_stmt -> close();
        }
    }
}


# Función para MODIFICAR noticias
function modificarNoticia($titulo, $foto_name, $texto, $fecha, $idNoticia, $mysqli_connection, &$exception_error) {

    # Evitar inyecciones SQL (Convierte todos los posibles caracteres especiales en una cadena de caracteres)
    $titulo = $mysqli_connection -> real_escape_string($titulo);
    $texto = $mysqli_connection -> real_escape_string($texto);
    $fecha = $mysqli_connection -> real_escape_string($fecha);
    $foto = $mysqli_connection -> real_escape_string($foto_name);

    # Inicializar la variable de sentencia
    $update_stmt = null;

    try{
        # Preparar la sentencía de actualización
        $update_stmt = $mysqli_connection->prepare('UPDATE noticias SET titulo = ?, imagen = ?, texto = ?, fecha = ? WHERE idNoticia = ?');

        if (!$update_stmt) {
            error_log('Error preparando actualización: ' . $mysqli_connection->error);
            return false;
        }else{
             # Vincular los parámetros
            $update_stmt->bind_param('ssssi', $titulo, $foto, $texto, $fecha, $idNoticia);

            # Ejecutamos la sentencia
            $result_update = $update_stmt -> execute();

            # SI la sentencia se ha podido ejecutar
            if($result_update){

                return $result_update;

            # SI no se ha podido ejecutar la sentencia
            }else{
                error_log("Error: No se puede ejecutar la sentencia " . $update_stmt -> error);
                $exception_error = true;
                return false;
            }
        }

    }catch(Exception $e){
        error_log("Error al ejecutar la función modificarCita(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        # Cerrar la sentencia si no lo está
        if($update_stmt !== null){
            $update_stmt -> close();
        }
    }

}


?>