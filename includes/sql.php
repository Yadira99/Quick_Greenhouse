<?php
  require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* VALIDA SI ENCUENTRA EL CONTENIDO DE LA BASE DE DATOS 
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}
/*--------------------------------------------------------------*/
/* ENCONTRAR LAS CONSULTAS
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}
/*--------------------------------------------------------------*/
/*  EL BUSCADOR 
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/* ELIMINA SEGUN EL ID
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*--------------------------------------------------------------*/
/* CONTADOR DE ID
/*--------------------------------------------------------------*/

function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* VALIDA SI LA BASE DE DATOS EXISTE
/*--------------------------------------------------------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
 /*--------------------------------------------------------------*/
 /* AUTENTIFICA CON POST,
/*--------------------------------------------------------------*/
  function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
      $user = $db->fetch_assoc($result);
      $password_request = sha1($password);
      if($password_request === $user['password'] ){
        return $user['id'];
      }
    }
   return false;
  }
  /*--------------------------------------------------------------*/
  /* AUTENTIFICA MAS CHIDO 
 /*--------------------------------------------------------------*/
   function authenticate_v2($username='', $password='') {
     global $db;
     $username = $db->escape($username);
     $password = $db->escape($password);
     $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
     $result = $db->query($sql);
     if($db->num_rows($result)){
       $user = $db->fetch_assoc($result);
       $password_request = sha1($password);
       if($password_request === $user['password'] ){
         return $user;
       }
     }
    return false;
   }


  /*--------------------------------------------------------------*/
  /* IDENTIFICA EL NIVEL DE LA SESION
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* ENCUENTRA TODOS LOS USUARIOS PARA LA CONSULTA
  /*--------------------------------------------------------------*/
  function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
      $sql .="g.group_name ";
      $sql .="FROM users u ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level ORDER BY u.name ASC";
      $result = find_by_sql($sql);
      return $result;
  }
  /*--------------------------------------------------------------*/
  /* ACTUALIZAR EL USUARIO 
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

  /*--------------------------------------------------------------*/
  /* CONSULTA EL GRUPO DE VEHICULOS 
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* CONSULTA LOS GRUPOS DE VEHICULOS
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* NIVEL DE ACCESO 
  /*--------------------------------------------------------------*/
   function page_require_level($require_level){
     global $session;
     $current_user = current_user();
     $login_level = find_by_groupLevel($current_user['user_level']);
     //if user not login
     if (!$session->isUserLoggedIn(true)):
            $session->msg('d','Por favor Iniciar sesión...');
            redirect('index.php', false);
      //if Group status Deactive
     elseif($login_level['group_status'] === '0'):
           $session->msg('d','Este nivel de usuario esta inactivo!');
           redirect('home.php',false);
      //cheackin log in User level and Require level is Less than or equal to
     elseif($current_user['user_level'] <= (int)$require_level):
              return true;
      else:
            $session->msg("d", "¡Lo siento!  no tienes permiso para ver la página.");
            redirect('home.php', false);
        endif;

     }
   /*--------------------------------------------------------------*/
   /* CONSULTA DE VEHICULOS
   /*--------------------------------------------------------------*/
  function join_travel_table(){
     global $db;
     $sql  =" SELECT p.id,p.modelo,p.matricula,c.name";
    $sql  .=" AS categorie";
    $sql  .=" FROM vehiculos p";
    $sql  .=" LEFT JOIN categories c ON c.id = p.categorie_id";
    $sql  .=" ORDER BY p.id ASC";
    return find_by_sql($sql);

   }
  /*--------------------------------------------------------------*/
  /* CONSULTA DEL PANEL 
  /*--------------------------------------------------------------*/

   function find_vehicle_by_title($vehiculo_name){
     global $db;
     $p_name = remove_junk($db->escape($vehiculo_name));
     $sql = "SELECT name FROM vehiculos WHERE name like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   }

  /*--------------------------------------------------------------*/
  /* CONSULTA DE INSERCION 
  /*--------------------------------------------------------------*/
  function find_all_vehicle_info_by_title($title){
    global $db;
    $sql  = "SELECT * FROM vehiculos ";
    $sql .= " WHERE modelo ='{$title}'";
    $sql .=" LIMIT 1";
    return find_by_sql($sql);
  }

  /*--------------------------------------------------------------*/
  /* ACTUALIZA EL VEHICULO 
  /*--------------------------------------------------------------*/
  function update_vehicle_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE vehiculos SET matricula=matricula -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }
  /*--------------------------------------------------------------*/
  /* CONSULTA LA CATEGORIA EN VIAJES
  /*--------------------------------------------------------------*/
 function find_recent_vehicle_added($limit){
   global $db;
   $sql   = " SELECT p.id,p.modelo,c.name AS categorie";
   $sql  .= " FROM vehiculos p";
   $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* CONSULTA VEHICULOS 
 /*--------------------------------------------------------------*/
 function find_higest_using_vehicle($limit){
   global $db;
   $sql  = "SELECT p.modelo, COUNT(s.vehiculo_id) AS totalSold";
   $sql .= " FROM viajes s";
   $sql .= " LEFT JOIN vehiculos p ON p.id = s.vehiculo_id ";
   $sql .= " GROUP BY s.vehiculo_id";
   $sql .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return $db->query($sql);
 }
 /*--------------------------------------------------------------*/
 /* CONSULTA VIAJES 
 /*--------------------------------------------------------------*/
 function find_all_travel(){
   global $db;
   $sql  = "SELECT s.id,s.destino,s.guia,s.date,s.status,p.modelo,u.name";
   $sql .= " FROM viajes s";
   $sql .= " LEFT JOIN vehiculos p ON s.vehiculo_id = p.id";
   $sql .= " LEFT JOIN users u ON s.conductor_id = u.id";
   $sql .= " ORDER BY s.date DESC";
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* CONSUTA VIAJES DEL PANEL 
 /*--------------------------------------------------------------*/
function find_recent_travel_added($limit){
  global $db;
  $sql  = "SELECT s.id,s.destino,s.guia,s.date,p.modelo";
  $sql .= " FROM viajes s";
  $sql .= " LEFT JOIN vehiculos p ON s.vehiculo_id = p.id";
  $sql .= " ORDER BY s.date DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* CONSULTA EL REPORTE DE VIEJES 
/*--------------------------------------------------------------*/
function find_travel_by_dates($start_date,$end_date){
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  $sql  = "SELECT s.date,s.guia,s.destino, p.modelo, u.name, s.vehiculo_id ,s.id ";
  $sql .= "FROM viajes s ";
  $sql .= "LEFT JOIN vehiculos p ON s.vehiculo_id = p.id ";
  $sql .= "LEFT JOIN users u ON s.conductor_id = u.id ";
  $sql .= " WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}' ";
  $sql .= " GROUP BY DATE(s.date),p.modelo ";
  $sql .= " ORDER BY DATE(s.date) DESC";
  return $db->query($sql);
}
/*--------------------------------------------------------------*/
/* GENERA EL REPORTE DIARIO 
/*--------------------------------------------------------------*/
function  dailytravels($year,$month){
  global $db;
  $sql  = "SELECT s.date,s.guia,s.destino, p.modelo, u.name, s.vehiculo_id ,s.id ";
  $sql .= "FROM viajes s ";
  $sql .= "LEFT JOIN vehiculos p ON s.vehiculo_id = p.id ";
  $sql .= "LEFT JOIN users u ON s.conductor_id = u.id ";
  $sql .= " WHERE DATE_FORMAT(s.date, '%d-%m' ) = '{$year}-{$month}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%e' ),s.vehiculo_id";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* GENERA REPORTE MENSUAL 
/*--------------------------------------------------------------*/
function  monthlytravels($year){
  global $db;
  $sql  = "SELECT s.date,s.guia,s.destino, p.modelo, u.name, s.vehiculo_id ,s.id ";
  $sql .= "FROM viajes s ";
  $sql .= "LEFT JOIN vehiculos p ON s.vehiculo_id = p.id ";
  $sql .= "LEFT JOIN users u ON s.conductor_id = u.id ";
  $sql .= " WHERE DATE_FORMAT(s.date, '%m' ) = '{$year}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%c' ),s.vehiculo_id";
  $sql .= " ORDER BY date_format(s.date, '%c' ) ASC";
  return find_by_sql($sql);
}

?>
