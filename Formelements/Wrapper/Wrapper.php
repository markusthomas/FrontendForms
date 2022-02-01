<?php
namespace FrontendForms;

/**
* Base class for creating a wrapper
* fe <div>...</div>
*/

class Wrapper extends Tag  {


  public function __construct()
  {
    parent::__construct();
    $this->setTag('div');
  }

  /**
  * Render the wrapper
  * @return string|null
  */
  public function ___render()
  {
    return $this->renderNonSelfclosingTag($this->getTag());
  }

  public function __toString()
  {
    return $this->render();
  }

}
