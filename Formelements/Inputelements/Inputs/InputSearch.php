<?php
namespace FrontendForms;

/**
* Class for creating an input search element
*/

class InputSearch extends Input  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type', 'search');
  }

  /**
  * Render the input element
  * @return string
  */
  public function ___renderInputSearch(): string
  {
    return $this->renderInput();
  }

}
