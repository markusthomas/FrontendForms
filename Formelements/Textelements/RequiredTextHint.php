<?php
namespace FrontendForms;

/**
* A class for creating the required text hint for the form
*/

class RequiredTextHint extends TextElements  {

  public function __construct()
  {
    parent::__construct();
    $this->setTag('p');
    $this->setCSSClass('requiredTextHintClass');
  }


}
