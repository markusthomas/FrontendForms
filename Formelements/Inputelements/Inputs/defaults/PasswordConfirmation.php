<?php
namespace FrontendForms;

/**
* Class with pre-defined values for creating an email input field
*/

class PasswordConfirmation extends InputPassword {


  public function __construct(string $id='passconfirm')
  {
    parent::__construct($id);
    $this->setLabel($this->_('Password Confirmation')); // set default label
    $this->setRule('required')->setCustomFieldName($this->_('Password Confirmation'));
    $this->setRule('equals', 'pass');
  }

  public function renderPasswordConfirmation(): string
  {
    return parent::renderInputPassword();
  }

}
