<?php
namespace FrontendForms;

/**
* Class with pre-defined values for creating a message input field
*/

class Message extends Textarea  {


  public function __construct(?string $id='message')
  {
    parent::__construct($id);
    $this->setLabel($this->_('Message'));
    $this->setRule('required')->setCustomFieldName($this->_('Message'));
  }

  public function renderMessage(){
    return parent::renderTextarea();
  }

}
