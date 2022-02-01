<?php
namespace FrontendForms;

/**
* Class for creating an input url element
*/

class InputUrl extends Input  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type', 'url');
  }

  /**
  * Render the input element
  * @return string
  */
  public function ___renderInputUrl(): string
  {
    return $this->renderInput();
  }

}
