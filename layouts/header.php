<?php $user = current_user(); ?>
<!DOCTYPE html>
  <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title><?php if (!empty($page_title))
           echo remove_junk($page_title);
            elseif(!empty($user))
           echo ucfirst($user['name']);
            else echo "Quick Greenhouse";?>
    </title>
    <link href="./libs/images/2.png" type="image/x-icon" rel="shortcut icon" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="libs/css/main.css" />
    <script src="https://kit.fontawesome.com/6ac7fbefb6.js" crossorigin="anonymous"></script>

  </head>
  <body>
  <?php  if ($session->isUserLoggedIn(true)): ?>
    <header id="header">
      <div class="logo pull-left"> 
      
      <img style="width:70%; heigth:90%;" src="./libs/images/1.png" alt="">
      </div>
      <div class="header-content">
      <div class="header-date pull-left">
        <strong><?php echo date("d/m/Y  g:i a");?></strong>
      </div>
      <div class="pull-right clearfix">
        <ul class="info-menu list-inline list-unstyled">
          <li class="profile">
            <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
              <img src="uploads/users/<?php echo $user['image'];?>" alt="user-image" class="img-circle img-inline">
              <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
            </a>
            <ul class="dropdown-menu">
              <li>
                  <a href="profile.php?id=<?php echo (int)$user['id'];?>">
                  <i class="fas fa-address-card"></i>
                  Perfil
                  </a>
              </li>
             <li>
                 <a href="edit_account.php" title="edit account">
                 <i class="fas fa-cogs"></i>
                 Configuración
                 </a>
             </li>
             <li class="last">
                 <a href="logout.php">
                 <i class="fas fa-sign-out-alt"></i>
                 Salir
                 </a>
             </li>
           </ul>
          </li>
        </ul>
      </div>
     </div>
    </header>
    <div class="sidebar">
      <?php if($user['user_level'] === '1'): ?>
        <!-- admin menu -->
      <?php include_once('admin_menu.php');?>


      <?php elseif($user['user_level'] === '2'): ?>
        <!-- User menu -->
      <?php include_once('user_menu.php');?>

      <?php endif;?>

   </div>
<?php endif;?>

<div class="page">
  <div class="container-fluid">
