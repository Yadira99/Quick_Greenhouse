<?php
  $page_title = 'Admin página de inicio';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
//    page_require_level(1);
?>
<?php
 $c_categorie     = count_by_id('categories');
 $c_vehiculo       = count_by_id('vehiculos');
 $c_viaje          = count_by_id('viajes');
 $c_user          = count_by_id('users');
 $vehiculos_sold   = find_higest_using_vehicle('10');
 $recent_vehiculos = find_recent_vehicle_added('5');
 $recent_viajes    = find_recent_travel_added('5')
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-6">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="panel panel-box clearfix">
            <div class="panel-icon pull-left bg-green">
            <i> <img style="width:60px; heigth:80px; " src="./libs/images/icons/png/037-gear.png" alt=""></i>

               </div>
            <div class="panel-value pull-right">
                <h2 class="margin-top"> <?php  echo $c_user['total']; ?> </h2>
                <p class="text-muted">Personal</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-box clearfix">
            <div class="panel-icon pull-left bg-red">
            <i> <img style="width:60px; heigth:80px; " src="./libs/images/icons/png/008-lista-de-verificacion.png" alt=""></i>
  
            </div>
            <div class="panel-value pull-right">
                <h2 class="margin-top"> <?php  echo $c_categorie['total']; ?> </h2>
                <p class="text-muted">Cultivos</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-box clearfix">
            <div class="panel-icon pull-left bg-blue">
            <i> <img style="width:60px; heigth:80px; " src="./libs/images/icons/png/004-caliente.png" alt=""></i>
            </div>
            <div class="panel-value pull-right">
                <h2 class="margin-top">28°</h2>
                <p class="text-muted">Temperatura</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-box clearfix">
            <div class="panel-icon pull-left bg-yellow">
            <i> <img style="width:60px; heigth:80px; " src="./libs/images/icons/png/006-metros.png" alt=""></i>
            </div>
            <div class="panel-value pull-right">
                <h2 class="margin-top">6,07</h2>
                <p class="text-muted">PH</p>
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span  class="glyphicon glyphicon-th"></span>
                    <span  style="width:600px; heigth:900px;" >Reparto de sensores</span>
                </strong>
            </div>
            <div class="panel-body">

               <img style="width:600px; heigth:900px;"src="./libs/images/cultivo.jpg" alt="">
            </div>
        </div>
    </div>
</div>


</div>



<?php include_once('layouts/footer.php'); ?>