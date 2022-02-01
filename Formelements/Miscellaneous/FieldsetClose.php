<?php
namespace FrontendForms;

/**
* Class for creating a fieldset close tag
*/

class FieldsetClose extends Element  {


  public function __construct()
  {
    $this->setTag('fieldset');
  }

  /**
  * Render the fieldset close tag
  * @return string
  */
  public function render()
  {
    return '</'.$this->getTag().'>';
  }

  public function __toString()
  {
    return $this->render();
  }


}
