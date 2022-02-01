<?php
namespace FrontendForms;

/**
* Trait for adding option elements
* Will be used on select and datalist elements
*/

Trait TraitOption  {

  protected $options = [];

  /**
  * Set an option object with value and label
  * @param string $label - the label text
  * @param string $value - the value of the option element
  * @return Option
  */

  public function addOption(string $label, string $value): Option
  {
     $option = new Option();
     $option->setContent($label);
     $option->setAttribute('value', $value);
     $this->options = array_merge($this->options, [$option]);
     return $option;
  }

}
