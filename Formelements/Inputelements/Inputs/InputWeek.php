<?php
namespace FrontendForms;

/**
* Class for creating an input week element
*/

class InputWeek extends Input  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type', 'week');
  }

  /**
  * Render the input element
  * @return string
  */
  public function ___renderInputWeek(): string
  {
    return $this->renderInput();
  }

}
