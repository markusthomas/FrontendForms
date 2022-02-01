<?php
namespace FrontendForms;

/**
* Class for creating an input email element
*/

class InputEmail extends Input  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type', 'email');
  }

  /**
  * Render the input element
  * @return string
  */
  public function ___renderInputEmail(): string
  {
    return $this->renderInput();
  }

}
