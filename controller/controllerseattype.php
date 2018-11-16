<?php
namespace controller;

use Controller\ControllerHome as C_Home;
use Model\SeatType as M_SeatType;
use DAO\SeatTypeDb as D_SeatType;


class ControllerSeatType
{

	private $daoSeattype;
	private $cHome;

	public function __construct()
	{
		$this->daoSeattype = D_SeatType::getInstance();
		$this->cHome = new C_Home();
	}

	public function index(){
		if(isset($_SESSION["Client"]) && $_SESSION["Client"]->getIsAdmin() == 1)
		{
			$seattypes = $this->daoSeattype->getAll();
			include(ROOT.'views/seattypes.php');
		}else {
			$this->cHome->index();
		}
	}

	public function addSeatType($name, $description){

		$newSeatType = new M_SeatType($name, $description);
		$this->daoSeattype->add($newSeatType);

		$this->index();

	}
	function modifySeatType($id,$name,$description) {

		$obj = $this->daoSeattype->retrieveById($id);

		$obj->setName($name);
		$obj->setDesc($description);


		$this->daoSeattype->update($obj);
		$this->index();
	}

	function deleteSeatType($idSeatType) {
		$this->daoSeattype->delete($idSeatType);
		$this->index();
	}
}
?>
