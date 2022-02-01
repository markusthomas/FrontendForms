<?php
namespace FrontendForms;

/**
* Create and render a button for the form
*/

class Button extends Element {

  public function __construct($name = 'submit')
  {
    parent::__construct($name);
    $this->setTag('button');
    $this->setAttribute('name', $name);
    $this->setAttribute('type','submit'); // default is submit
    $this->setCSSClass('buttonClass');
    $this->setAttribute('value', _('Send')); // default is "Send"
  }


  /**
  * Render the button
  * Use the value attribute as button text
  * @return string|null
  */
  public function ___render()
  {
    $this->setContent($this->getAttribute('value'));
    return $this->renderNonSelfclosingTag($this->getTag());
  }

  public function __toString()
  {
    return $this->render();
  }

}
