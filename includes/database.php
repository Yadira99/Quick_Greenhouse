<?php
require_once(LIB_PATH_INC.DS."config.php");

class MySqli_DB {

    private $con;
    public $query_id;

    function __construct() {
      $this->db_connect();
    }

/*--------------------------------------------------------------*/
/* FUNCIONES PARA HACER LA CONEXION CON LA BASE DE DATOS 
/*--------------------------------------------------------------*/
public function db_connect()
{
  $this->con = mysqli_connect(DB_HOST,DB_USER,DB_PASS);
  if(!$this->con)
         {
           die(" FALLO LA CONEXION:". mysqli_connect_error());
         } else {
           $select_db = $this->con->select_db(DB_NAME);
             if(!$select_db)
             {
               die("NO SE SELCCIONO LA BASE DE DATOS". mysqli_connect_error());
             }
         }
}
/*--------------------------------------------------------------*/
/* FUNCION PARA CERRAR LA CONEXION A LA BASE DE DATOS 
/*--------------------------------------------------------------*/

public function db_disconnect()
{
  if(isset($this->con))
  {
    mysqli_close($this->con);
    unset($this->con);
  }
}
/*--------------------------------------------------------------*/
/* VALIDACION DE CONSULTAS 
/*--------------------------------------------------------------*/
public function query($sql)
   {

      if (trim($sql != "")) {
          $this->query_id = $this->con->query($sql);
      }
      if (!$this->query_id)
        // only for Develope mode
              die("Error en esta consulta :<pre> " . $sql ."</pre>");
       // For production mode
        //  die("Error on Query");

       return $this->query_id;

   }

/*--------------------------------------------------------------*/
/* VALIDACION DE CONSULTAS POR TIPO
/*--------------------------------------------------------------*/
public function fetch_array($statement)
{
  return mysqli_fetch_array($statement);
}
public function fetch_object($statement)
{
  return mysqli_fetch_object($statement);
}
public function fetch_assoc($statement)
{
  return mysqli_fetch_assoc($statement);
}
public function num_rows($statement)
{
  return mysqli_num_rows($statement);
}
public function insert_id()
{
  return mysqli_insert_id($this->con);
}
public function affected_rows()
{
  return mysqli_affected_rows($this->con);
}
/*--------------------------------------------------------------*/
 /* FUNCION PARA ELIMINAR UN ELEMENTO DEL ARREGLO
 /*--------------------------------------------------------------*/
 public function escape($str){
   return $this->con->real_escape_string($str);
 }
/*--------------------------------------------------------------*/
/* CICLOS DE LECTURA
/*--------------------------------------------------------------*/
public function while_loop($loop){
 global $db;
   $results = array();
   while ($result = $this->fetch_array($loop)) {
      $results[] = $result;
   }
 return $results;
}

}

$db = new MySqli_DB();

?>
