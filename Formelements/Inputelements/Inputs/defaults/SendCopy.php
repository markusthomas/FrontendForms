<?php
namespace FrontendForms;

/**
* Class with pre-defined values for creating a send copy of my message to me input field
*/

class SendCopy extends InputCheckbox  {


  public function __construct(string $id='sendcopy')
  {
    parent::__construct($id);
    $this->setLabel($this->_('Send a copy of my message to me'));
  }

  public function renderSendCopy(){
    return parent::renderInputCheckbox();
  }

}
