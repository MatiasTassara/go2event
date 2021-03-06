<?php include("header.php") ?>

<div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky" >
  <nav class="uk-navbar uk-navbar uk-navbar-container" uk-navbar="dropbar: true;">
    <div class="uk-navbar-left">
      <a class="uk-navbar-toggle" uk-toggle="target: #offcanvas-nav">
        <span uk-navbar-toggle-icon class="icon-index" uk-icon-navbar></span>
      </a>
      <span class="uk-margin-small-left"><a href="<?=FRONT_ROOT?>"><img class="img-responisve" src="<?=FRONT_ROOT?>img/Go2EventLogo.png"></a></span>
    </div>
    <div class="uk-navbar-center">
      <ul class="uk-navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="<?=FRONT_ROOT?>"><button class="uk-button uk-button-text">Inicio</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?=FRONT_ROOT?>Home/UpcomingEvents"><button class="uk-button uk-button-text">Proximos</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?=FRONT_ROOT?>Home/mostSoldEvents"><button class="uk-button uk-button-text">Mas Vendidos</button></a>
        </li>

      </ul>
    </div>
    <div class="uk-navbar-right">
      <?php if(!isset($_SESSION["user"])){?>
        <li class="nav-item">
          <a href="<?=FRONT_ROOT?>Home/Login"><button class="uk-button uk-button-text  mr-3 ">Iniciar Sesion</button></a>
        </li>
      <?php }else{ ?>
        <li class = "nav-item">
          <a href="<?=FRONT_ROOT?>Profile"><button class="mr-3 nav-link uk-button uk-button-text" uk-tooltip = "title:<?= $_SESSION["user"]->getName().' '.$_SESSION["user"]->getSurname()?>;pos: bottom">Perfil</button></a>
        </li>
        <li class = "nav-item">
          <a href="<?=FRONT_ROOT?>Purchase" ><span class="mr-3" uk-icon="icon: cart; ratio: 1.5" uk-tooltip="title: Elementos en el carrito: <?= count($_SESSION["purchaseItems"]); ?>; pos: bottom"></span></a>
        </li>
        <li class="nav-item">
          <a href="<?=FRONT_ROOT?>Login/Logout"><button class="uk-button uk-button-text mr-3">Cerrar Sesion</button></a>
        </li>
      </div>
    <?php } ?>
  </nav>

</div>
<div id="offcanvas-nav" uk-offcanvas="overlay: true">
  <div class="uk-offcanvas-bar">

    <div class="">
      <ul class="uk-nav uk-nav-primary">
        <li class="<?php if(ACTIVE_METHOD == ''){echo "uk-active";}?>"><a href="<?=FRONT_ROOT?>">Inicio</a></li>
        <li class="<?php if(ACTIVE_METHOD == 'UpcomingEvents'){echo "uk-active";}?>"><a href="<?=FRONT_ROOT?>Home/UpcomingEvents">Próximos</a></li>
        <li class="<?php if(ACTIVE_METHOD == 'MostSoldEvents'){echo "uk-active";}?>"><a href="<?=FRONT_ROOT?>Home/MostSoldEvents">Más Vendidos</a></li>
        <?php if (!isset($_SESSION["user"])) { ?>
          <li class="<?php if(ACTIVE_METHOD == 'Login'){echo "uk-active";}?>"><a href="<?=FRONT_ROOT?>Home/Login">Iniciar Sesion</a></li>
        <?php  }else{ ?>
          <?php if(($_SESSION["user"]->isAdmin() == 1)){ ?>
            <li><a href="<?=FRONT_ROOT?>Artist">Menu Admin</a></li>
          <?php } ?>
          <li><a href="<?=FRONT_ROOT?>Login/logOut">Cerrar Sesion</a></li>
        <?php }?>

        </ul>
      </div>

    </div>
  </div>
