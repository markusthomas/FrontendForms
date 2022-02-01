<?php
namespace FrontendForms;

/**
* Class for creating an input text element
*/

class InputText extends Input  {

  public function __construct(string $id)
  {
    parent::__construct($id);
  }

  /**
  * Render the input element
  * @return string
  */
  public function ___renderInputText(): string
  {
    return $this->renderInput();
  }


}
