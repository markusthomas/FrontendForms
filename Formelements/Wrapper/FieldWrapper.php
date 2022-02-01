<?php
namespace FrontendForms;

/**
* A class for creating a wrapper over a complete form input including label, error message, notes and description
*/

class FieldWrapper extends Wrapper  {

  public function __construct()
  {
    parent::__construct();
    $this->setCSSClass('field_wrapperClass');
  }

  protected function getErrorClass()
  {
    return $this->getCSSClass('field_wrapper_errorClass');
  }

}
