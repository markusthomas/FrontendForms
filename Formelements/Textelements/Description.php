<?php
namespace FrontendForms;

/**
* This is the class for creating a description under an input element
* Will be instantiated in the setDescription() method of the Inputfields class
*/

class Description extends TextElements {

  public function __construct()
  {
    parent::__construct();
    $this->setCSSClass('descriptionClass');
  }

}
