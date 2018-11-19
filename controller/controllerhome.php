<?php
namespace controller;

use Model\Calendar as M_Calendar;
use Model\Seat as M_Seat;
use Dao\db\ArtistsPerCalendarDb as D_Artist_Calendar;
use Dao\db\CalendarDb as D_Calendar;
use Dao\db\VenueDb as D_Venue;
use Dao\db\EventDb as D_Event;
use Dao\db\ArtistDb as D_Artist;
use Dao\db\SeattypeDb as D_SeatType;
use Dao\db\SeatDb as D_Seat;

class  ControllerHome{
  private $daoCalendar;
  private $daoEvent;
  private $daoArtist;
  private $daoVenue;
  private $daoArtistPerCalendar;
  private $daoSeat;
  private $daoSeatType;

  public function __construct()
  {
    $this->daoCalendar = D_Calendar::getInstance();
    $this->daoEvent = D_Event::getInstance();
    $this->daoArtist = D_Artist::getInstance();
    $this->daoVenue = D_Venue::getInstance();
    $this->daoArtistPerCalendar = D_Artist_Calendar::getInstance();
    $this->daoSeatType = D_SeatType::getInstance();
    $this->daoSeat = D_Seat::getInstance();
  }
  public function index(){
    $calendars = $this->daoCalendar->getAll();
    $events = $this->daoEvent->getAll();
    $artists = $this->daoArtist->getAll();
    $venues = $this->daoVenue->getAll();
    $seattypes = $this->daoSeatType->getAll();
    include(ROOT.'views/index.php');
  }
  public function login(){
    include(ROOT.'views/login-register.php');

  }


}


?>
