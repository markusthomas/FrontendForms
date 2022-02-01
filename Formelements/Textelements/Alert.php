<?php
namespace FrontendForms;

/**
* A class for creating alert boxes
* Will be instantiated in the Form class
*/

class Alert extends TextElements  {

  public function __construct()
  {
    parent::__construct();
    $this->setTag('div');
    $this->setCSSClass('alertClass');
  }

}
