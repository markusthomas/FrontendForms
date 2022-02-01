<?php
namespace FrontendForms;

/**
* Class for creating an input range element
*/

class InputRange extends Input  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type', 'range');
    $this->setCSSClass('input_rangeClass'); // add special range input class
    $this->removeAttributeValue('class', $this->getCSSClass('inputClass')); // remove default input class
  }

  /**
  * Render the input element
  * @return string
  */
  public function ___renderInputRange(): string
  {
    return $this->renderInput();
  }

}
