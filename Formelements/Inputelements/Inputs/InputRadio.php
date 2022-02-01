<?php
namespace FrontendForms;

/**
* Class for creating a radio element
*/

class InputRadio extends InputRadioCheckbox  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type','radio');
    $this->setCSSClass('radioClass');
  }

  /**
  * Render the input element
  * @return string
  */

  public function renderInputRadio(): string
  {

    if($this->notSubmitted()){
      // set checked on page load if default value was set
      if(in_array( $this->getAttribute('value'),$this->getDefaultValue()))
        $this->setAttribute('checked');
    } else {
      // mark checkbox as checked after submission
      if(($this->hasAttribute('value')) && ($this->getPostValue() === $this->getAttribute('value')))
        $this->setAttribute('checked');
    }

    return $this->renderInput();
  }

}
