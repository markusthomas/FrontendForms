<?php
namespace FrontendForms;

/**
* Class for creating an input month element
*/

class InputMonth extends Input  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type', 'month');
  }

  /**
  * Render the input element
  * @return string
  */
  public function ___renderInputMonth(): string
  {
    return $this->renderInput();
  }

}
