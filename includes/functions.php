<?php
 $errors = array();

 /*--------------------------------------------------------------*/
 /* REMUEVE CARACTERES DE UN STRING 
 /*--------------------------------------------------------------*/
function real_escape($str){
  global $con;
  $escape = mysqli_real_escape_string($con,$str);
  return $escape;
}
/*--------------------------------------------------------------*/
/* REMOVER CARACTERES HTML
/*--------------------------------------------------------------*/
function remove_junk($str){
  $str = nl2br($str);
  $str = htmlspecialchars(strip_tags($str, ENT_QUOTES));
  return $str;
}
/*--------------------------------------------------------------*/
/* MAYUSCULAS PRIMEROS CARACTERES DE LAS VARIABLES
/*--------------------------------------------------------------*/
function first_character($str){
  $val = str_replace('-'," ",$str);
  $val = ucfirst($val);
  return $val;
}
/*--------------------------------------------------------------*/
/* VALIDAR CAMPOS VACIOS 
/*--------------------------------------------------------------*/
function validate_fields($var){
  global $errors;
  foreach ($var as $field) {
    $val = remove_junk($_POST[$field]);
    if(isset($val) && $val==''){
      $errors = $field ." No puede estar en blanco.";
      return $errors;
    }
  }
}
/*--------------------------------------------------------------*/
/* MENSAJES DE SESION  
/*--------------------------------------------------------------*/
function display_msg($msg =''){
   $output = array();
   if(!empty($msg)) {
      foreach ($msg as $key => $value) {
         $output  = "<div class=\"alert alert-{$key}\">";
         $output .= "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>";
         $output .= remove_junk(first_character($value));
         $output .= "</div>";
      }
      return $output;
   } else {
     return "" ;
   }
}
/*--------------------------------------------------------------*/
/* REDIRECCIONES DE PAGINA EN CASO DE ERROR 
/*--------------------------------------------------------------*/
function redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
      header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}
/*--------------------------------------------------------------*/
/* Function for find out total viajeing price, buying price and profit
/*--------------------------------------------------------------*/
function total_price($totals){
   $sum = 0;
   $sub = 0;
   foreach($totals as $total ){
     $sum += $total['total_viajeing_price'];
     $sub += $total['total_buying_price'];
     $profit = $sum - $sub;
   }
   return array($sum,$profit);
}
/*--------------------------------------------------------------*/
/* HORA LOCAL (PENDIENTE)
/*--------------------------------------------------------------*/
function read_date($str){

     if($str)
      return date('d/m/Y g:i:s a', strtotime($str));
     else
      return null;
  }
/*--------------------------------------------------------------*/
/* HACE LA HORA DEL DIA 
/*--------------------------------------------------------------*/
function make_date(){
  return strftime("%Y-%m-%d %H:%M:%S", time());
}
/*--------------------------------------------------------------*/
/* INCREMENTA EL TIEMPO 
/*--------------------------------------------------------------*/
function count_id(){
  static $count = 1;
  return $count++;
}
/*--------------------------------------------------------------*/
/* SEGURIDAD DE CIFRADO DE CADENAS 
/*--------------------------------------------------------------*/
function randString($length = 5)
{
  $str='';
  $cha = "0123456789abcdefghijklmnopqrstuvwxyz";

  for($x=0; $x<$length; $x++)
   $str .= $cha[mt_rand(0,strlen($cha))];
  return $str;
}


?>
