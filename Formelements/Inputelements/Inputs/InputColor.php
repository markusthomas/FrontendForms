<?php
namespace FrontendForms;

/**
* Class for creating an input color element
*/

class InputColor extends Input  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type', 'color');
    $this->setCSSClass('input_colorClass');
  }

  /**
  * Render the input element
  * @return string
  */
  public function ___renderInputColor(): string
  {
    return $this->renderInput();
  }

}
