<?php
namespace FrontendForms;

/**
* This is the class for creating notes under an input element
* Will be instantiated in the setNotes() method of the Inputfields class
*/

class Notes extends TextElements {

  public function __construct()
  {
    parent::__construct();
    $this->setCSSClass('notesClass');
  }

}
