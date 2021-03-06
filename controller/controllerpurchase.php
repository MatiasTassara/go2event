<?php
namespace controller;

use Model\Seat as M_Seat;
use Dao\db\SeatDb as D_Seat;
use Model\Purchase as M_Purchase;
use Dao\db\PurchaseDb as D_Purchase;
use Model\PurchaseItem as M_PurchaseItem;
use Dao\db\PurchaseItemDb as D_PurchaseItem;
use Controller\ControllerHome as C_Home;
use Model\Ticket as M_Ticket;
use Dao\db\TicketDb as D_Ticket;
use Controller\ControllerProfile as C_Profile;



class ControllerPurchase{
    private $daoSeat;
    private $daoPurchase;
    private $daoPurchaseItem;
    private $controllerHome;
    private $daoTicket;
    private $controllerProfile;

    public function __construct(){
        $this->daoSeat = D_Seat::getInstance();
        $this->daoPurchase = D_Purchase::getInstance();
        $this->daoPurchaseItem = D_PurchaseItem::getInstance();
        $this->daoTicket = D_Ticket::getInstance();
        $this->controllerHome = new C_Home();
        $this->controllerProfile = new C_Profile();
    }

    public function index($alert = null){
        include(ROOT.'views/cart.php');
    }

/*
    public function addToCart($arrQuant,$arrIdsSeats){
        $i = 0;
        $everythingOk = true;
        // Chequea que la cantidad total de asientos este disponible para ese Calendar
        while ($i < count($arrIdsSeats) && $everythingOk) {
            $seat = $this->daoSeat->retrieveById($arrIdsSeats[$i]);
            if($arrQuant[$i] > $seat->getRemaining()){
                $everythingOk = false;
            }
        }
        if($everythingOk){
            // Creamos la compra y luego las lineas de compra

            $purchase = $_SESSION['purchase'];
            while ($i < count($arrIdsSeats)) {
                $seat = $this->daoSeat->retrieveById($arrIdsSeats[i]);
                $purchaseItem = new M_PurchaseItem($value,$seat->getPrice(),$purchase);
                $_SESSION['purchaseItem'][] = $purchaseItem;
                $i++;
            }

        }
        if(!$everythingOk){
            $errorMessage = "No hay disponibilidad para la cantidad entradas ingresada";
        }else{
            var_dump($_SESSION);
            $this->index();
        }
    }*/

    public function addToCart($idSeat,$quant){
         // Chequea que la cantidad total de asientos este disponible para ese Calendar

            $seat = $this->daoSeat->retrieveById($idSeat);
            if ($key = $this->existsInCart($seat)) {
              $remaining = $seat->getRemaining();
              $remaining = $remaining - $_SESSION['purchaseItems'][$key-1]->getQuantity();
              if($quant <= $remaining){
                  $total = $_SESSION['purchaseItems'][$key-1]->getQuantity() + $quant;
                  $_SESSION['purchaseItems'][$key-1]->setQuantity($total);
                  $this->index();
              }
              else {
                $alert = "No hay disponibilidad para la cantidad entradas ingresada";
                $this->controllerHome->eventInfo($seat->getCalendar()->getEvent()->getId(),$alert);
              }

            }

          else {
            if($quant <= $seat->getRemaining()){
                $purchase = $_SESSION['purchase'];
                $purchaseItem = new M_PurchaseItem($quant,$seat->getPrice(),$purchase,$seat);
                $_SESSION['purchaseItems'][] = $purchaseItem;
                $this->index();
            }
            else{
                $alert = "No hay disponibilidad para la cantidad entradas ingresada";
                $this->controllerHome->index($alert);
            }
          }
  }

    public function removeFromCart($itemKey){
        $itemKey = $itemKey - 1;
        unset($_SESSION['purchaseItems'][$itemKey]);
        $this->index();
    }


    public function placeOrder(/*$nameAsOnCard,*/$cardNumber,$securityCode,$expirationDate){
        //parametros extra para a futuro usar api de tarjeta de credito
        $purchase = $_SESSION['purchase'];
        $purchaseItems = $_SESSION['purchaseItems'];
        if($this->is_valid_luhn($cardNumber) == true){
            $this->daoPurchase->add($purchase);
            $purchaseFromDb = $this->daoPurchase->getLastPurchase();// para poder mandarle el obj completo al
            // para cada linea de compra
            foreach ($purchaseItems as $key => $value) {
                $value->setPurchase($purchaseFromDb);
                $this->daoPurchaseItem->add($value);
                $valueWithId = $this->daoPurchaseItem->getLastPurchaseItems();// para poder mandarle el obj completo (con id) al new ticket

                $seat = $this->daoSeat->retrieveById($value->getSeat()->getId());
                $cantItems = $value->getQuantity();
                // se crea un ticket para cada elemento en esa linea de compra
                while($cantItems > 0){
                    $qrCode = openssl_random_pseudo_bytes(10);
                    $ticketNumber = $seat->getQuantity() - $seat->getRemaining() + $cantItems;
                    $ticket = new M_Ticket($ticketNumber,$qrCode,$valueWithId);
                    $this->daoTicket->add($ticket);
                    $cantItems--;
                }
               //actualizamos el remaining del seat para cada plaza segun cantidad de entradas por linea
                $seat->setRemaining($seat->getRemaining() - $value->getQuantity());
                $this->daoSeat->update($seat);
            }
            $_SESSION['purchaseItems'] = [];

            $this->controllerProfile->index('&#x2714 Compra exitosa');
        }else{
            $this->index('&#x2718 <strong>ATENCION</strong> La tarjeta ingresada no es válida. Contacte a su banco o intente nuevamente.');
        }
  }



  private function is_valid_luhn($number, $mod5 = false) {
        $parity = strlen($number) % 2;
        $total = 0;
        $digits = str_split($number);
        foreach($digits as $key => $digit) { // Foreach digit
            // for every second digit from the right most, we must multiply * 2
            if (($key % 2) == $parity)
                $digit = ($digit * 2);
            // each digit place is it's own number (11 is really 1 + 1)
            if ($digit >= 10) {
                // split the digits
                $digit_parts = str_split($digit);
                // add them together
                $digit = $digit_parts[0]+$digit_parts[1];
            }
            // add them to the total
            $total += $digit;
        }
        return ($total % ($mod5 ? 5 : 10) == 0 ? true : false); // If the mod 10 or mod 5 value is equal to zero (0), then it is valid
    }

    private function existsInCart($seat)
    {
      foreach ($_SESSION['purchaseItems'] as $key => $value) {
        if ($value->getSeat()->getId() == $seat->getId()) {
          return $key+1;
        }
      }
      return false;
    }

/*    private function sendMailApi(){
        # Include the Autoloader (see "Libraries" for install instructions)

        # Instantiate the client.
        $mgClient = new Mailgun('f4f783358d14dfca4d7da1b9eb296867-1053eade-0fb4efda');
        $domain = "sandbox8a6eadd7f5364ce5bc60e8a2e8128a87.mailgun.org";

        # Make the call to the client.
        $result = $mgClient->sendMessage($domain, array(
            'from'    => 'Excited User <Lisandro@sandbox8a6eadd7f5364ce5bc60e8a2e8128a87.mailgun.org>',
            'to'      => 'licanueto@hotmail.com',
            'subject' => 'Entradas GoToEvent',
            'text'    => 'Acá están tus entradas!',
            'html'    => '<html>Inline image: <img src="cid:tempQR-0.png"></html>'
        ), array('inline' => array(ROOT.'images/tempQR/tempQR-0.png')
        ));
    }

    private function sendMailSmtp($mail){
        $from = '<matiastassara59@gmail.com>';
        $to      = '<licanueto@hotmail.com>'; //$mail;
        $subject = '¡Gracias por comprar en GoToEvent!';
        $msg = "Hola, como estas? aca estan el/los codigos QR";

        $headers = array(
            'From' => $from,
            'To' => $to,
            'Subject' => $subject
        );

        $smtp = Mail::factory('smtp', array(
                'host' => 'ssl://smtp.mailgun.org',
                'port' => '587',
                'auth' => true,
                'username' => 'postmaster@sandbox8a6eadd7f5364ce5bc60e8a2e8128a87.mailgun.org',
                //'password' => 'iuy34WEFsef_=-'
                'password' => 'd80b9d7908cd2bf2741417882c45f4a9-1053eade-eae58232'
            ));

        $mail = $smtp->send($to, $headers, $msg);

        if (PEAR::isError($mail)) {
            echo('<p>' . $mail->getMessage() . '</p>');
        } else {
            echo('<p>Message successfully sent!</p>');
        }
    }
*/



}
