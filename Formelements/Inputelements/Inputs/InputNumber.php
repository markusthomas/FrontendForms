<?php
namespace FrontendForms;

/**
* Class for creating an input number element
*/

class InputNumber extends Input  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type', 'number');
  }

  /**
  * Render the input element
  * @return string
  */
  public function ___renderInputNumber(): string
  {
    return $this->renderInput();
  }

}
