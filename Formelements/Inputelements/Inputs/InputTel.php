<?php
namespace FrontendForms;

/**
* Class for creating an input tel element
*/

class InputTel extends Input  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type', 'tel');
  }

  /**
  * Render the input element
  * @return string
  */
  public function ___renderInputTel(): string
  {
    return $this->renderInput();
  }

}
