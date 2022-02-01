<?php
namespace FrontendForms;

/**
* This is the class for creating an error message under an input element
* Will be instantiated inside the Form class
*/

class Errormessage extends TextElements {

  public function __construct()
  {
    parent::__construct();
    $this->setCSSClass('error_messageClass');
    if($this->moduleConfig['input_framework'] == 'bootstrap5.json')
      $this->setTag('div');
  }

}
