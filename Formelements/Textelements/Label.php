<?php
namespace FrontendForms;

/**
* This is the class for creating labels for form inputs
* Will be instantiated in the setLabel() method of the Inputfields class
*/

class Label extends TextElements  {

  protected $enableAsterisk = true;
  protected $required = false;

  public function __construct()
  {
    parent::__construct();
    $this->setTag('label');
    $this->setCSSClass('labelClass');
  }

  /**
  * Render the markup of the asterisk
  * Method is Hook-able
  * @return string
  */
  protected function ___renderAsterisk(): string
  {
    return '<span class="asterisk">*</span>';
  }


  /**
  * Disable the markup of Asterisk
  * Needed for Checkbox multiple and Radio Multipe to prevent the asterisk beeing shown on every option if field is required
  */
  public function disableAsterisk()
  {
    $this->enableAsterisk = false;
  }

  /**
  * Set the required status
  * @return boolean
  */
  public function setRequired()
  {
    $this->required = true;
  }

  /**
  * Get the required status
  * @return boolean
  */
  public function getRequired()
  {
    return $this->required;
  }

  /**
  * Render the label element
  * @return string
  */
  public function render(): string
  {
    if($this->enableAsterisk){
      if($this->getRequired()){
        $this->setCSSClass('label_requiredClass');
        $span = ($this->moduleConfig['input_showasterisk']) ? $this->renderAsterisk() : '';
      } else {
        $span = '';
      }
    } else {
      $span = '';
    }
    $this->setContent($this->getText().$span);
    return parent::render();
  }


}
