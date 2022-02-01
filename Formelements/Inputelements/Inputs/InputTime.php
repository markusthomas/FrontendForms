<?php
namespace FrontendForms;

/**
* Class for creating an input time element
*/

class InputTime extends Input  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type', 'time');
  }

  /**
  * Render the input element
  * @return string
  */
  public function ___renderInputTime(): string
  {
    return $this->renderInput();
  }

}
