<?php
namespace FrontendForms;

use \ProcessWire\Wire as Wire;

/**
* Class for creating an input password element
*/

class InputPassword extends Input  {

  protected $passwordField;
  protected $passwordFieldName = 'pass';
  protected $showPasswordRequirements = false;
  protected $passwordToggle; // checkbox object for show/hide password


  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type', 'password');
    $this->passwordField = $this->wire('fields')->get($this->passwordFieldName);

  }

  /**
  * Show a hint for requirements for the password as set in the backend
  * @return string|null - returns a comma separated list of all conditions or null if no conditions were set
  */
  protected function getPasswordConditions(): ?string
  {
    $passwordModule = $this->wire('modules')->get('InputfieldPassword');

    if($this->passwordField){
      $requirements = $this->passwordField->requirements;
      if(!$requirements)
      // if no requirements are stored in the database, take the default data from the inputfield default configuration
        $requirements = $this->wire('modules')->get('InputfieldPassword')->requirements;
      }
      if(in_array('none', $requirements))
        return null;
      $conditions = [];
      foreach($requirements as $name){
        $conditions[] = $passwordModule->requirementsLabels[$name];
      }
      return implode(', ', $conditions);

    return null;
  }


  /**
  * Render a text which informs about the requirements of a password as set in the backend
  */
  public function renderPasswordRequirements(): ?string
  {
    // check if minlength is stored inside the db otherwise take it from default data
    $passLength = (!$this->passwordField->minlength) ? $this->wire('modules')->get('InputfieldPassword')->minlength : $this->passwordField->minlength;

    if($this->getPasswordConditions()){
      return sprintf($this->_('The password must be at least %s characters and must contain characters of the following categories: %s.'), $passLength, $this->getPasswordConditions());
    } else {
        if($passLength > 0)
          return sprintf($this->_('The password must be at least %s characters.'), $passLength);
        return null;
    }
    return null;
  }


  /**
  * Show the password requirements at the password field
  */
  public function showPasswordRequirements($fieldName = null)
  {
    if($fieldName)
      $this->passwordFieldName = trim($fieldName);
    $this->showPasswordRequirements = true;
  }

  /**
  * Hide the password requirements at the password field
  */
  public function hidePasswordRequirements()
  {
    $this->showPasswordRequirements = false;
  }


  /**
  * Add a password toggle checkbox to the input element
  * @return InputCheckbox
  */
  public function showPasswordToggle(): InputCheckbox
  {
    $toggle = new InputCheckbox('pwtoggle');
    $toggle->setLabel($this->_('Show password'))->setAttribute('class', 'pwtoggleLabel');
    $toggle->setAttribute('class', 'pwtoggle');
    $toggle->removeInputWrapper();
    $toggle->removeFieldWrapper();
    $toggle->removeAttribute('id');
    $this->showPasswordToggle = $toggle;
    return $toggle;
  }


  /**
  * Render the input element
  * @return string
  */
  public function ___renderInputPassword(): string
  {
    if($this->showPasswordRequirements){
      if($this->getDescription()){
        $this->setDescription($this->renderPasswordRequirements().'<br>'.$this->getDescription()->getText());
      } else {
        $this->setDescription($this->renderPasswordRequirements());
      }
    }
    if($this->showPasswordToggle)
      $this->append($this->showPasswordToggle->render());
    return $this->renderInput();
  }

}
