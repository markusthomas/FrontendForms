<?php
namespace FrontendForms;

/**
* Class with pre-defined values for creating a gender select field
*/

class Gender extends Select  {


  public function __construct(string $id='gender')
  {
    parent::__construct($id);
    $this->setLabel($this->_('Gender'));
    $this->addOption($this->_('Mister'), $this->_('Mister'));
    $this->addOption($this->_('Miss'), $this->_('Miss'));
    $this->setRule('required')->setCustomFieldName($this->_('Gender'));
  }

  public function renderGender(){
    return parent::renderSelect();
  }

}
