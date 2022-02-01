<?php
namespace FrontendForms;

/**
* Class for creating a select multiple element
*/

class SelectMultiple extends Select  {

  protected $selectValues = []; // array to hold all InputCheckbox objects


  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('multiple');
    $this->setAttribute('name', $this->getAttribute('name').'[]');// add brackets to the name for multiple values array
  }

  /**
  * Render the select input
  * @return string
  */
  public function renderSelectMultiple(): string
  {
    return $this->renderSelect();
  }

}
