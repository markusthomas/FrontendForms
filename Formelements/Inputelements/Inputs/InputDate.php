<?php
namespace FrontendForms;

/**
* Class for creating an input date element
*/

class InputDate extends Input  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type', 'date');
  }

  /**
  * Render the input element
  * @return string
  */
  public function ___renderInputDate(): string
  {
    return $this->renderInput();
  }

}
