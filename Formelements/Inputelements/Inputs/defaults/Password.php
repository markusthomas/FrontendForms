<?php
namespace FrontendForms;



/**
* Class with pre-defined values for creating an email input field
*/

class Password extends InputPassword {


  public function __construct(string $id='pass')
  {
    parent::__construct($id);
    $this->setLabel($this->_('Password')); // set default label
    $this->setRule('required')->setCustomFieldName($this->_('Password'));
  }

  public function renderPassword(): string
  {
    return parent::renderInputPassword();
  }

}
