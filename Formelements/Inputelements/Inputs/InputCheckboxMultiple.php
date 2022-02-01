<?php
namespace FrontendForms;

/**
* Class for creating an input checkbox multiple element
*/

class InputCheckboxMultiple extends Input  {

  protected $checkboxes = []; // array to hold all InputCheckbox objects
  protected $directionHorizontal = true; // default checkbox orientation

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type','checkbox');
    $this->removeAttribute('class');
    $this->setCSSClass('checkboxClass');
  }

  /**
  * Add this method to the InputCheckboxMultiple object to display the checkboxes vertically
  */
  public function alignVertical()
  {
      $this->directionHorizontal = false;
  }

  /**
  * Add a checkbox input as an option to a checkbox multiple input element
  * @param string $label - the text label for the checkbox
  * @param string $value -> the value of the checkbox
  * @return InputCheckbox
  */
  public function addOption(string $label, string $value): InputCheckbox
  {
    $checkbox = new InputCheckbox($this->getAttribute('name'));
    // add brackets to the name attribute
    $checkbox->setAttribute('name', $this->getAttribute('name').'[]');
    $checkbox->setLabel($label)->removeAttribute('class');
    $checkbox->setAttribute('value', $value);
    //remove all wrappers
    $checkbox->removeInputWrapper();
    $checkbox->removeFieldWrapper();
    // disabel the required signs if present
    $checkbox->getLabel()->disableAsterisk();
    $this->checkboxes = array_merge($this->checkboxes, [$checkbox]);
    return $checkbox;
  }


  /**
  * Render the multiple checkbox element
  * @return string
  */
  public function ___renderInputCheckboxMultiple(): string
  {

    $out = '';
    if($this->checkboxes){

      // set post value as value if present
      $this->setAttribute('value', $this->getPostValue());

      foreach($this->checkboxes as $key => $checkbox){
        //Set unique ID for each radio button
        $checkbox->setAttribute('id', $this->getAttribute('name').'-'.$key);
        if(!$this->directionHorizontal){
          switch($this->markupType){
            case('bootstrap5.json'):
              $checkbox->prepend('<div class="'.$this->getCSSClass('checkinputClass').'">');
              $checkbox->getLabel()->append('</div>');
            break;
            default:
              // add br tag if checkboxes should be aligned vertically
              $checkbox->getLabel()->append('<br>');
          }
        } else {
          switch($this->markupType){
            case('bootstrap5.json'):
              $checkbox->prepend('<div class="'.$this->getCSSClass('checkbox_horizontalClass').'">');
              $checkbox->getLabel()->append('</div>');
            break;
            default:

          }
        }


        // set checkbox checked if default value array contains the checkbox value
        if($this->notSubmitted()){
          if(in_array($checkbox->getAttribute('value'), $this->getDefaultValue()))
            $checkbox->setAttribute('checked');
        } else {
          // set checkbox checked if post value array contains the checkbox value
          if(in_array($checkbox->getAttribute('value'), $this->getPostValue()))
            $checkbox->setAttribute('checked');
        }

        $out .= $checkbox->render();
      }

      // if orientation is horizontal create and add and additional wrapper
      if($this->directionHorizontal){
        switch($this->markupType){
          case('bootstrap5.json'):

          break;
          default:
            $wrapper = new Wrapper();
            $wrapper->setCSSClass('checkbox_horizontalClass');
            $wrapper->setContent($out);
            $out = $wrapper->render();
        }
      }
    }

    return $out;
  }

}
