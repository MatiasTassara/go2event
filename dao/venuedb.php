<?php
namespace dao;

use Model\Venue as M_Venue;

/**
 *
 */
class VenueDb extends SingletonDAO implements \interfaces\Idao
{

  private $connection;
  function __construct(){

  }
  public function add($obj){

    $sql ="INSERT INTO venues (name_venue, address, city, capacityLimit) VALUES (:name_venue, :address, :city, :capacityLimit)";

    $parameters['name_venue'] = $obj->getName();
    $parameters['address'] =$obj->getAddress();
    $parameters['city'] = $obj->getCity();
    $parameters['capacityLimit'] =$obj->getCapacityLimit();

    try{
      $this->connection = Connection::getInstance();

      return $this->connection->executeNonQuery($sql, $parameters);
    }catch(\PDOException $ex){
      throw $ex;

    }

  }

  public function retrieveByName($name){

    $sql = "SELECT * FROM venues WHERE name_venue LIKE %:name_venue%";
    $parameters['name_venue'] = $name;

    try{
      $this->connection = Connection::getInstance();
      $response = $this->connection->execute($sql, $parameters);
    }catch(Exception $ex){
      throw $ex;
      
    }
    if(!empty($response)){
      $result = $this->map($response);
      return array_shift($result);
    }
    else
      return null;

  }
  public function retrieveById($id){

    $sql = "SELECT * FROM venues WHERE id_venue =:id_venue";
    $parameters['id_venue'] = $id;
    try{
      $this->connection = Connection::getInstance();
      $response = $this->connection->execute($sql, $parameters);

    }catch(Exception $ex){
      throw $ex;
    }
    if(!empty($response)){
      $result = $this->map($response);
      return array_shift($result);
    }
    else
      return null;

  }

  

  public function getAll(){

    $sql = "SELECT * FROM venues order by name_venue";
    try{
      $this->connection = Connection::getInstance();
      $response = $this->connection->execute($sql);
    }catch(Exception $ex){
      throw $ex;
    }
    if(!empty($response)){
      
      return $this->map($response);
    }
    else
      return null;

  }

  public function update($obj){

    $sql = "UPDATE venues SET name_venue = :name_venue, address = :address, city = :city, capacityLimit = :capacityLimit WHERE id_venue = :id_venue";
    $parameters['id_venue'] = $obj->getId();
    $parameters['name_venue'] = $obj->getName();
    $parameters['address'] = $obj->getAddress();
    $parameters['city'] = $obj->getCity();
    $parameters['capacityLimit'] = $obj->getCapacityLimit();

    try{
      $this->connection = Connection::getInstance();
      $response = $this->connection->executeNonQuery($sql, $parameters);
    }catch(Exception $ex){
      throw $ex;
    }

  }

  public function delete($id){

    $sql = "DELETE FROM venues WHERE id_venue = :id_venue";
    $parameters['id_venue'] = $id;
    try{
      $this->connection = Connection::getInstance();
      $response = $this->connection->executeNonQuery($sql, $parameters);
    }catch(Exception $ex){
      throw $ex;
    }

  }

  
  protected function map($value) {

      $value = is_array($value) ? $value : [];

      $resp = array_map(function($p){
        return new M_Venue($p['name_venue'], $p['address'], $p['city'], $p['capacityLimit'], $p['id_venue']);}, $value);

               return count($resp) >= 1 ? $resp : $arrayResponse[] = $resp['0'];

  
}
}


 ?>
