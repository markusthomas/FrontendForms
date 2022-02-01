<?php
namespace FrontendForms;

/**
* Class with pre-defined values for creating a subject input field
*/

class Subject extends InputText  {


  public function __construct(?string $id='subject')
  {
    parent::__construct($id);
    $this->setLabel($this->_('Subject'));
    $this->setRule('required')->setCustomFieldName($this->_('Subject'));
  }

  public function renderSubject(){
    return parent::renderInputText();
  }

}
