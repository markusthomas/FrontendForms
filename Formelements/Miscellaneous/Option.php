<?php
namespace FrontendForms;

/**
* Class for creating an option element for select inputs and Datalists
*/

class Option extends Tag  {

  public function __construct()
  {
    parent::__construct();
    $this->setTag('option');
  }

  /**
  * Add the selected attribute to an element by default
  */
  public function setSelected()
  {
    // run only if form was not submitted
    if($this->notSubmitted())
      $this->setAttribute('selected');
  }


  /**
  * Render an option tag
  * @return string
  */
  public function render(): string
  {
    return $this->renderNonSelfclosingTag($this->getTag());
  }

}
