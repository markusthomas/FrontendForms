<?php
namespace FrontendForms;

/**
* Base class for creating checkbox and radio button elements
*/

class InputRadioCheckbox extends Input  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->removeAttribute('class');
  }

  /**
  * Method to set a single checkbox checked by default (on page load)
  * Independent if radio button has a value or not
  * @return $this;
  */
  public function setChecked()
  {
    if($this->notSubmitted())
      $this->setAttribute('checked');
    return $this;
  }

}
