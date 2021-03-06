<?php
namespace controller;

use Controller\ControllerHome as C_Home;
use Model\Category as M_Category;
//use Dao\db\ListCategory as D_Category;
use Dao\db\CategoryDb as D_Category;

class ControllerCategory{
  private $daoCategory;
  private $cHome;

  public function __construct(){

    $this->daoCategory = D_Category::getInstance();
    $this->cHome = new C_Home();

  }

  public function index($alert = null){
    if(isset($_SESSION["user"]) && $_SESSION["user"]->isAdmin() == 1)
    {
      $categories = $this->daoCategory->getAll();
      include(ROOT.'views/categories.php');
    }
      else {
        $this->cHome->index($this->cHome->index("Usted no es un administrador"));
      }
  }

  public function addCategory($name){
    $objCategory = new M_Category($name);
    $this->daoCategory->add($objCategory);
    $this->index();
  }

  function modifyCategory($id,$name) {
    $obj = $this->daoCategory->retrieveById($id);
    $obj->setName($name);
    $this->daoCategory->update($obj);
    $this->index();
  }

  function deleteCategory($idCategory) {
    if (!$this->daoCategory->beingUsed($idCategory)) {
      $this->daoCategory->delete($idCategory);
      $this->index();
    }
    else
    {
      $this->index("<strong>¡ERROR!</strong> La categoría que está queriendo eliminar esta en uso");
    }
  }
}
