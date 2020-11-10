<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>

<?php
 // Auto suggetion
    $html = '';
   if(isset($_POST['vehiculo_name']) && strlen($_POST['vehiculo_name']))
   {
     $vehiculos = find_vehicle_by_title($_POST['vehiculo_name']);
     if($vehiculos){
        foreach ($vehiculos as $vehiculo):
           $html .= "<li class=\"list-group-item\">";
           $html .= $vehiculo['name'];
           $html .= "</li>";
         endforeach;
      } else {

        $html .= '<li onClick=\"fill(\''.addslashes().'\')\" class=\"list-group-item\">';
        $html .= 'No encontrado';
        $html .= "</li>";

      }

      echo json_encode($html);
   }
 ?>
 <?php
 // find all vehiculo
  if(isset($_POST['p_name']) && strlen($_POST['p_name']))
  {
    $vehiculo_title = remove_junk($db->escape($_POST['p_name']));
    if($results = find_all_vehicle_info_by_title($vehiculo_title)){
        foreach ($results as $result) {

          $html .= "<tr>";

          $html .= "<td id=\"s_name\">".$result['name']."</td>";
          $html .= "<input type=\"hidden\" name=\"s_id\" value=\"{$result['id']}\">";
          $html  .= "<td>";
          $html  .= "<input type=\"text\" class=\"form-control\" name=\"price\" value=\"{$result['viaje_price']}\">";
          $html  .= "</td>";
          $html .= "<td id=\"s_qty\">";
          $html .= "<input type=\"text\" class=\"form-control\" name=\"quantity\" value=\"1\">";
          $html  .= "</td>";
          $html  .= "<td>";
          $html  .= "<input type=\"text\" class=\"form-control\" name=\"total\" value=\"{$result['viaje_price']}\">";
          $html  .= "</td>";
          $html  .= "<td>";
          $html  .= "<input type=\"date\" class=\"form-control datePicker\" name=\"date\" data-date data-date-format=\"yyyy-mm-dd\">";
          $html  .= "</td>";
          $html  .= "<td>";
          $html  .= "<button type=\"submit\" name=\"add_viaje\" class=\"btn btn-primary\">Agregar</button>";
          $html  .= "</td>";
          $html  .= "</tr>";

        }
    } else {
        $html ='<tr><td>El vehiculoo no se encuentra registrado en la base de datos</td></tr>';
    }

    echo json_encode($html);
  }
 ?>
