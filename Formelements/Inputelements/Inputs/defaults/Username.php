<?php
namespace FrontendForms;

use \ProcessWire\Wire as Wire;

/**
* This is the base class for creating input elements
*/

class Username extends InputText  {


  public function __construct(string $id='username')
  {
    parent::__construct($id);
    $this->setLabel($this->_('Username'));
    $this->setSanitizer('pageName');
    $this->setRule('required')->setCustomFieldName($this->_('Username'));
    $this->setRule('usernameSyntax');
    if($this->wire('user')->isLoggedIn())
      $this->setAttribute('value', $this->wire('user')->name);
  }

  public function renderUsername(){
    return parent::renderInputText();
  }

}
