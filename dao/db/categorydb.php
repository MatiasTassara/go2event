<?php
namespace dao\db;

use Model\Category as M_Category;
use Dao\singletondao as SingletonDAO;
/**
*
*/
class CategoryDb extends SingletonDAO implements \interfaces\Idao
{

  private $connection;
  function __construct(){

  }
  public function add($obj){

    $sql ="INSERT INTO categories (name_category, active) VALUES (:name_category, :active)";

    $parameters['name_category'] = $obj->getName();
    $parameters['active'] = 1;


    try{
      $this->connection = Connection::getInstance();

      return $this->connection->executeNonQuery($sql, $parameters);
    }catch(\PDOException $ex){
      throw $ex;

    }

  }



  public function retrieveByName($name){

    $sql = "SELECT * FROM categories where name_category = :name_category and active = 1";

    $parameters['name_category'] = $name;

    try {
      $this->connection = Connection::getInstance();
      $response = $this->connection->execute($sql, $parameters);
    } catch(Exception $ex) {
      throw $ex;
    }


    if(!empty($response)){

      $result =  $this->map($response);
      return array_shift($result);
    }

    else
    return null;

  }


  public function retrieveById($id){

    $sql = "SELECT * from categories where id_category = :id_category and active = 1";
    $parameters['id_category'] = $id;
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

  public function moneyEarnedPerCategory()
  {
      $sql = "SELECT e.id_category, ifnull(sum(pi.quantity * pi.price),0) as total
      FROM events e inner join calendars c on e.id_event = c.id_event
      inner join seats s on s.id_calendar = c.id_calendar left outer join purchase_items pi
      on s.id_seat = pi.id_seat
      group by e.id_category
      order by sum(pi.quantity * pi.price) desc";
      try{
        $this->connection = Connection::getInstance();
        $response = $this->connection->execute($sql);
      }catch(Exception $ex){
        $ex->getMessage();
      }
      if(!empty($response)){
        foreach ($response as $key => $value) {
          $arrayResponse['categories'][] = $this->retrieveById($value['id_category']);
          $arrayResponse['total'][] = $value['total'];
        }
        return $arrayResponse;
      }

      else
      return null;
    }
  public function getAll(){

    $sql = "SELECT * FROM categories WHERE active = 1 order by name_category";
    try{
      $this->connection = Connection::getInstance();
      $response =$this->connection->execute($sql);
    }catch(Exception $ex){
      throw $ex;
    }
    if(!empty($response))
    return $this->map($response);
    else
    return null;

  }

  public function getAllNonActive(){
    $sql = "SELECT * FROM categories WHERE active = 2 ORDER BY name_category";
    try{
      $this->connection = Connection::getInstance();
      $response =$this->connection->execute($sql);
    }catch(Exception $ex){
      throw $ex;
    }
    if(!empty($response))
    return $this->map($response);
    else
    return null;
  }

  public function getCategoriesActAndNonAct(){
    $sql = "SELECT * FROM categories ORDER BY name_category";
     try{
      $this->connection = Connection::getInstance();
      $response =$this->connection->execute($sql);
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

    $resp = array_map(function($p){
      return new M_Category($p['name_category'], $p['id_category']);}, $value);

      return count($resp) >= 1 ? $resp : $arrayResponse[] = $resp['0'];


    }

    public function update($obj){

      $sql = "UPDATE categories SET name_category = :name_category where id_category = :id_category";
      $parameters['id_category'] = $obj->getId();
      $parameters['name_category'] = $obj->getName();
      try{
        $this->connection = Connection::getInstance();
        return $this->connection->ExecuteNonQuery($sql, $parameters);
      }catch(\PDOException $ex){
        throw $ex;

      }

    }

    public function delete($id){

      $sql = "UPDATE  categories SET active = :active where id_category = :id_category";
      $parameters['id_category'] = $id;
      $parameters['active'] = 2;

      try{
        $this->connection = Connection::getInstance();
        $response = $this->connection->executeNonQuery($sql, $parameters);
      }catch(Exception $ex){
        throw $ex;
      }



    }

    public function beingUsed($id)
    {
      $sql = "SELECT * FROM events where id_category = :id_category AND active = 1";
      $parameters['id_category'] = $id;
       try{
        $this->connection = Connection::getInstance();
        $response =$this->connection->execute($sql,$parameters);
      }catch(Exception $ex){
        throw $ex;
      }
      if(!empty($response))
        return true;
      else{
        return false;
      }
    }


  }


  ?>
