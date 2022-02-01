<?php
namespace FrontendForms;

/**
* This is the base class for creating input elements
*/

class Input extends Inputfields  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setTag('input');
    $this->setAttribute('type','text');// set text as default type if no type was set
    $this->setCSSClass('inputClass');
  }

  /**
  *  Render the input tag
  * @return string
  */
  public function renderInput(): string
  {
    return $this->renderSelfclosingTag($this->getTag());
  }

}
