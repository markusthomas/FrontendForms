<?php
namespace FrontendForms;

/**
* Class for creating a select element
*/

class Select extends Inputfields  {

  use TraitOption;

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setTag('select');
    $this->setCSSClass('selectClass');
  }


  /**
  * Render the select input
  * @return string
  */
  public function ___renderSelect(): string
  {
    $out = '';
    if($this->options){

      $options = '';

      foreach($this->options as $key=>&$option){

          if($this->notSubmitted()){
            // pre-defined with setDefaultValue() method
            if(in_array($option->getAttribute('value'), $this->getDefaultValue()))
              $option->setAttribute('selected');
          } else {
            // set selected if post value contains the option value
            if(in_array($option->getAttribute('value'), (array) $this->getPostValue()))
              $option->setAttribute('selected');
          }

        $options .= $option->render();
      }
      $this->setContent($options);
      $out =  $this->renderNonSelfclosingTag($this->getTag());
    }
    return $out;
  }

}
