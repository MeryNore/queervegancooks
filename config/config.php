<!-- AVISO DE ERRORES/CONTROL DE ERRORES
 Si trabajamos con otro servidor que no sea Apache utilizaremos estas sentencias para que nos muestre el error -->
<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  
  error_reporting(E_ALL);
?>
<!-- Podemos ponerlo antes de la etiqueta HTML en cada view -->

<!-- Otra opción, LA MÁS RECOMENDADA, es añadir el código en un archivo de configuración.
 La carpeta config suele ir fuera del proyecto -->