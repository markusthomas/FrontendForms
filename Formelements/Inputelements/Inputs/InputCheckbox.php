<?php
namespace FrontendForms;

/**
* Class for creating a checkbox element
*/

class InputCheckbox extends InputRadioCheckbox  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type','checkbox');
    $this->setCSSClass('checkboxClass');
  }


  /**
  * Render the input element
  * @return string
  */
  public function ___renderInputCheckbox(): string
  {
    if($this->notSubmitted()){
      // set checked if default value was set
      if(in_array($this->getAttribute('value'), $this->getDefaultValue()))
        $this->setAttribute('checked');
    } else {
      // post value is array -> multiple checkbox value
      if(is_array($this->getPostValue())){
        // set checked if post value is contains the checkbox value
        if(in_array($this->getAttribute('value'), $this->getPostValue()))
          $this->setAttribute('checked');
      } else {
        // set checked if post value is equal the checkbox value
        if($this->getPostValue() === $this->getAttribute('value'))
          $this->setAttribute('checked');
      }
    }

    return $this->renderInput();
  }

}
