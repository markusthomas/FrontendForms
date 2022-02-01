<?php
namespace FrontendForms;

/**
* This is the class for creating legends for fieldsets
* Will be instantiated in the setLegend() method of the Fieldset class
*/

class Legend extends TextElements  {

  public function __construct()
  {
    parent::__construct();
    $this->setTag('legend');
    $this->setCSSClass('legendClass');
  }


}
