<?php
namespace FrontendForms;

/**
* Class with pre-defined values for creating an email input field
*/

class Email extends InputEmail {


  public function __construct(string $id='email')
  {
    parent::__construct($id);
    $this->setLabel($this->_('Email'));
    $this->setRule('required')->setCustomFieldName($this->_('Email'));
    $this->setRule('email');
    $this->setRule('emailDNS');
  }

  public function renderEmail(): string
  {
    return parent::renderInputEmail();
  }

}
