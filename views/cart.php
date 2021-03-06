<?php include(ROOT."views/navbar.php") ?>
<?php if(isset($alert)) { ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo $alert ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
<?php } ?>
<div id="accordion">
  <div class="card rounded-0">
    <div class="card-header p-0" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-lg btn-block btn-light rounded-0 p-3" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Carrito de compras
        </button>
      </h5>
    </div>
    <?php
    if(!empty($_SESSION['purchaseItems'])){ ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Evento</th>
            <th scope="col">Dia</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Precio unitario</th>
            <th scope="col">Sub total</th>
            <th></th>
          </tr>
        </thead>


        <tbody>
          <?php  $acumTotal = 0;
          foreach ($_SESSION['purchaseItems'] as $key => $value) {?>
            <tr>
              <td> <?php echo $value->getSeat()->getCalendar()->getEvent()->getName(); ?></td>
              <td> <?php echo $value->getSeat()->getCalendar()->getDateFront(); ?></td>
              <td> <?php echo $value->getQuantity(); ?></td>
              <td> <?php echo '$' .  $value->getSeat()->getPrice(); ?></td>
              <td> <?php echo '$' . ($value->getSeat()->getPrice()) * $value->getQuantity(); ?></td>
              <?php $acumTotal = $acumTotal + (($value->getSeat()->getPrice()) * $value->getQuantity());?>
              <td>
                <div class="col-auto ">
                </div>
              </td>
              <td>
                <div class="col-auto ">
                  <a href="<?= FRONT_ROOT?>Purchase/RemoveFromCart/<?= ($key + 1) ?>" class="uk-icon-button" uk-icon="trash"></a>
                </div>
              </td>
            </tr>
          <?php } ?>

          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td> <strong>Total</strong> $<?= $acumTotal ?></td>
          <td></td>
          <td></td>
        </tbody>
      </table>
    <?php } ?>


    <?php if (empty($_SESSION['purchaseItems'])){ ?>
      <h5 class="text-center"style="margin-top: 20px;">No hay tickets en el carrito</h5>
    <?php } ?>
  </div>
</div>
<?php if (!empty($_SESSION['purchaseItems'])){ ?>
  <div class="container col-5 p-2">
    <button type="button" class="btn btn-warning btn-lg btn-block " data-toggle="modal" data-target="#modalBuy">Comprar</button>
  </div>
<!--PURCHASE MODAL-->
  <div class="modal fade" id="modalBuy" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Comprar Tickets</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="<?= FRONT_ROOT ?>Purchase/placeOrder" method="post">
          <div class="modal-body">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="inputEmail4">Número de Tarjeta</label>
                <input type="number" min="0" class="form-control" name="cardNumber" placeholder="Número de Tarjeta" required>
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Clave de Segurirdad</label>
                <input type="password" class="form-control" name="CVV" placeholder="Clave de Segurirdad" maxlength="4" required>

              </div>
              <div class="form-group col-md-6">
                <label for="vencimiento">Vencimiento</label>
                <select class="form-control" name="expire" id="vencimiento" required>
                  <?php for ($i=1; $i <= 12 ; $i++) {?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                  <?php  } ?>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="vencimiento2"> </label>
                <select class="form-control" style="margin-bottom: 0px;margin-top: 8px;" id="vencimiento2">
                  <?php for ($i=2018; $i <= 2037 ; $i++) {?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                  <?php  } ?>
                </select>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="" checked>
                <label class="form-check-label" for="inlineRadio1"><span class="uk-icon uk-icon-image" style="background-image: url(<?= FRONT_ROOT ?>img/mc.png);"></span></label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="">
                <label class="form-check-label" for="inlineRadio2"><span class="uk-icon uk-icon-image" style="background-image: url(<?= FRONT_ROOT ?>img/am.png);"></span></label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="">
                <label class="form-check-label" for="inlineRadio3"><span class="uk-icon uk-icon-image" style="background-image: url(<?= FRONT_ROOT ?>img/visa.png);"></span></label>
              </div>
            </div>


            <div class="modal-footer">
              <button type="submit" class="btn btn-warning">Comprar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php } ?>


  <?php include(ROOT."views/footer.php"); ?>
