<?php
namespace FrontendForms;

/**
* This is the base class for creating input elements
*/

class Surname extends InputText  {


  public function __construct(string $id='surname')
  {
    parent::__construct($id);
    $this->setLabel($this->_('Surname'));
    $this->setRule('required')->setCustomFieldName($this->_('Surname'));
  }

  public function renderSurname(){
    return parent::renderInputText();
  }

}
