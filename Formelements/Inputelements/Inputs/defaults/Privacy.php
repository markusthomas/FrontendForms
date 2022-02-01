<?php
namespace FrontendForms;

/**
* Class with pre-defined values for creating a accept our data privacy input field
*/

class Privacy extends InputCheckbox  {


  public function __construct(?string $id='privacy')
  {
    parent::__construct($id);
    $this->setLabel($this->_('I accept the privacy policy'));
    $this->setRule('required')->setCustomMessage($this->_('You have to accept our privacy policy'));

  }

  public function renderPrivacy(){
    return parent::renderInputCheckbox();
  }

}
