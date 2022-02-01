<?php
namespace FrontendForms;

/**
* Class with pre-defined values for creating a last name input field
*/

class Name extends InputText  {


  public function __construct(?string $id='name')
  {
    parent::__construct($id);
    $this->setLabel($this->_('Name'));
    $this->setRule('required')->setCustomFieldName($this->_('Name'));
  }

  public function renderName(){
    return parent::renderInputText();
  }

}
