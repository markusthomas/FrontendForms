<?php
namespace FrontendForms;

/**
* Class for creating an input hidden element
*/

class InputHidden extends Input  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type','hidden');
  }

  /**
  * Render the input element
  * @return string
  */
  public function renderInputHidden(): string
  {
    return $this->renderInput();
  }

}
