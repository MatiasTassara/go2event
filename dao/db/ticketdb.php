<?php
namespace dao\db;

use Model\Ticket as M_Ticket;
use Dao\db\PurchaseItemDb as D_PurchaseItem;
use Dao\singletondao as SingletonDAO;
/**
*
*/
class TicketDb extends SingletonDAO implements \interfaces\Idao
{

  private $connection;
  private $daoPurchasesItem;
  function __construct(){

    $this->daoPurchasesItem = D_PurchaseItem::getInstance();

  }



  public function add($obj){

    $sql ="INSERT INTO tickets(number_ticket, qr, id_purchase_item) VALUES (:number_ticket, :qr, :id_purchase_item)";

    $parameters['number_ticket'] = $obj->getNumber();
    $parameters['qr'] = $obj->getQr();
    $parameters['id_purchase_item'] = $obj->getPurchaseItem()->getId();



    try{
      $this->connection = Connection::getInstance();

      return $this->connection->executeNonQuery($sql, $parameters);
    }catch(\PDOException $ex){
      throw $ex;

    }

  }

  public function retrieveByName($name){


  }

  public function getTotalTickets()
  {
    $sql = "SELECT count(*) as total from tickets;";
    try{
      $this->connection = Connection::getInstance();
      $response = $this->connection->execute($sql);

    }catch(Exception $ex){
      throw $ex;

    }if(!empty($response)){
      return $response[0]['total'];
    }

    else
    return null;

  }
  public function retrieveByPurchaseItemId($idPurchaseItem){

    $sql = "SELECT t.id_ticket FROM tickets t INNER JOIN purchase_items pi
          ON t.id_purchase_item = pi.id_purchase_item WHERE t.id_purchase_item = :id_purchase_item";
    $parameters['id_purchase_item'] = $idPurchaseItem;
    try{
      $this->connection = Connection::getInstance();
      $response = $this->connection->execute($sql, $parameters);

    }catch(Exception $ex){
      throw $ex;

    }
    $arrayTickets = array();
    if(isset($response)){
      foreach ($response as $key => $value) {
        $ticket = $this->retrieveById($value['id_purchase_item']);
        $arrayTickets[] = $ticket;
      }
      return $arrayTickets;
    }else{
      return null;
    }

  }
  public function retrieveTicketsByIdUser($idUser){
    $sql= "SELECT t.id_ticket FROM tickets t INNER JOIN purchase_items pi ON t.id_purchase_item = pi.id_purchase_item
           INNER JOIN purchases p ON p.id_purchase = pi.id_purchase WHERE p.id_user = :id_user ";
    $parameters['id_user'] = $idUser;
    try{
      $this->connection = Connection::getInstance();
      $response = $this->connection->execute($sql, $parameters);

    }catch(Exception $ex){
      throw $ex;

    }
    $arrayTickets = array();
    if(isset($response)){
      foreach ($response as $key => $value) {
        $ticket = $this->retrieveById($value['id_ticket']);
        $arrayTickets[] = $ticket;
      }
      return $arrayTickets;
    }else{
      return null;
    }
  }


  public function retrieveById($id){

    $sql = "SELECT * from tickets where id_ticket = :id_ticket";
    $parameters['id_ticket'] = $id;
    try{
      $this->connection = Connection::getInstance();
      $response = $this->connection->execute($sql, $parameters);

    }catch(Exception $ex){
      throw $ex;

    }if(!empty($response)){

      $result = $this->map($response);
      return array_shift($result);
    }

    else
    return null;



  }




  public function getAll(){

    $sql = "SELECT * FROM tickets order by number_ticket";
    try{
      $this->connection = Connection::getInstance();
      $response = $this->connection->execute($sql);
    }catch(Exception $ex){
      throw $ex;
    }
    if(!empty($response))
    return $this->map($response);
    else
    return null;

  }



  protected function map($value) {

    $value = is_array($value) ? $value : [];
    $arrayResponse = array();


    $resp = array_map(function($p){

      $purchaseItem = $this->daoPurchasesItem->retrieveById($p['id_purchase_item']);

      return new M_Ticket ($p['number_ticket'], $p['qr'], $purchaseItem, $p['id_ticket']);
    }, $value);

    return count($resp) >= 1 ? $resp : $arrayResponse[] = $resp['0'];


  }


  public function update($obj){
    $sql = "UPDATE tickets SET number_ticket = :number_ticket, qr = :qr, id_purchase_item = :id_purchase_item where id_ticket = :id_ticket";
    $parameters['id_ticket'] = $obj->getId();
    $parameters['number_ticket'] = $obj->getNumber();
    $parameters['qr'] = $obj->getQr();
    $parameters['id_purchase_item'] = $obj->getPurchaseItem()->getId();


    try{
      $this->connection = Connection::getInstance();
      return $this->connection->ExecuteNonQuery($sql, $parameters);
    }catch(\PDOException $ex){
      throw $ex;

    }

  }


  public function delete($id){

    $sql = "DELETE from tickets where id_ticket = :id_ticket";
    $parameters['id_ticket'] = $id;

    try{
      $this->connection = Connection::getInstance();
      $response = $this->connection->executeNonQuery($sql, $parameters);
    }catch(Exception $ex){
      throw $ex;
    }



  }


}


?>
