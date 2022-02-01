<?php
namespace FrontendForms;

/**
* Class for creating an input radio multiple element
*/

class InputRadioMultiple extends Input  {

  protected $radios = []; // array to hold all InputRadio objects
  protected $directionHorizontal = true; // default radio button orientation


  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type','radio');
    $this->removeAttribute('class');
    $this->setCSSClass('radioClass');
  }


  /**
  * Add a radio input as an option to a radio multiple input element
  * @param string $label - the text label for the radio button
  * @param string $value -> the value of the radio button
  * @return InputRadio
  */
  public function addOption(string $label, string $value): InputRadio
  {
    $radio = new InputRadio($this->getAttribute('name'));
    $radio->setLabel($label)->removeAttribute('class');
    $radio->setAttribute('value', $value);
    $this->radios = array_merge($this->radios, [$radio]);
    return $radio;
  }

  /**
  * Add this method to the InputRadioMultiple object to display the radio buttions vertically
  */
  public function alignVertical()
  {
      $this->directionHorizontal = false;
  }


  /**
  * Render multiple radion buttons in a group
  * Only one can be selected
  * @return string
  */
  public function ___renderInputRadioMultiple(): string
  {

    $out = '';

    if($this->radios){

      $checked = []; //array to hold checked radios
      foreach($this->radios as $key => &$radio){

        //Set unique ID for each radio button
        $radio->setAttribute('id', $this->getAttribute('name').'-'.$key);
        $radio->removeInputWrapper();
        $radio->removeFieldWrapper();
        $radio->getLabel()->disableAsterisk();

        if(!$this->directionHorizontal){
          switch($this->markupType){
            case('bootstrap5.json'):
              $radio->prepend('<div class="'.$this->getCSSClass('checkinputClass').'">');
              $radio->getLabel()->append('</div>');
            break;
            default:
              // add br tag if checkboxes should be aligned vertically
              $radio->getLabel()->append('<br>');
          }
        } else {
          switch($this->markupType){
            case('bootstrap5.json'):
              $radio->prepend('<div class="'.$this->getCSSClass('checkbox_horizontalClass').'">');
              $radio->getLabel()->append('</div>');
            break;
            default:

          }
        }

        if($this->notSubmitted()){
          // set checked if default value was set
          if(in_array($radio->getAttribute('value'), $this->getDefaultValue()))
            $radio->setAttribute('checked');
        } else {
          // set checked if post value is equal to the radio button value
          if($this->getPostValue() === $radio->getAttribute('value'))
            $radio->setAttribute('checked');
        }
        // if you use the setChecked() method a checked attribute will be added each time, so there can be more checked attributes than allowed
        // remove multiple checked attributes if present - only 1 checked attribute is allowed
        // the first checked attribute will be accepted - all others will be removed
        if(empty($checked)){
          if($radio->hasAttribute('checked'))
            $checked[] = 1;
        } else {
          $radio->removeAttribute('checked');
        }

        $out .= $radio->render();
      }

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
