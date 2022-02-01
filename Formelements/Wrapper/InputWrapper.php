<?php
namespace FrontendForms;

/**
* A class for creating a wrapper element for form inputs
*/

class InputWrapper extends Wrapper  {

  public function __construct()
  {
    parent::__construct();
    $this->setCSSClass('input_wrapperClass');
  }

}
