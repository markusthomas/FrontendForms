<?php
namespace FrontendForms;

/**
* This is the base class for creating HTML input elements for collecting user input
* It contains general methods which can be used for all input elements such as input, textarea, radio,...
*/

/**
* Class and Function List:
* Function list:
* - __construct()
* - getInputWrapper()
* - getFieldWrapper()
* - removeInputWrapper()
* - addInputWrapper()
* - removeFieldWrapper()
* - addFieldWrapper()
* - setDefaultValue()
* - getDefaultValue()
* - setLabel()
* - getLabel()
* - setNotes()
* - getNotes()
* - setDescription()
* - getDescription()
* - setErrorMessage()
* - getErrorMessage()
* - setSanitizer()
* - getSanitizer()
* - removeSanitizers()
* - setRule()
* - removeRule()
* - setCustomMessage()
* - setCustomFieldname()
* - getRules()
* - hasRule()
* - getinputErrorClass()
* - hasPostValue()
* - getPostValue()
* - render()
* - __toString()
* Classes list:
* - Inputfields extends Tag
*/

abstract class Inputfields extends Element  {

  // Define all objects
  protected $label; // Object of class Label
  protected $notes; // Object of class Notes
  protected $description; // Object of class Description
  protected $errormessage; // Object of class error message
  protected $fieldWrapper; // the wrapper object for the complete form input
  protected $inputWrapper; // the wrapper object for the input element
  protected $validator; // the valitron validation object (instantiated via setRule() method)
  protected $api;

  // Default values
  protected $error_msg = ''; // error message for inputfield
  protected $sanitizer = []; // array to hold all sanitizer methods for this inputfield
  protected $validatonRules = []; // array to hold all validation rules for one inputfield (can be none or multiple)
  protected $removeInputWrapper = null; // show or hide the wrapper for the input element
  protected $removeFieldWrapper = null; // show or hide the wrapper for the complete field element including label
  protected $markupType = ''; // the selected markup type (fe UiKit, none, Bootstrap,... whatever)
  protected $defaultValue = []; // array of all default values


  /**
  * Every inputfield must have a name, so the name is required as parameter in the constructor
  * The id will be created out of the name of the inputfield and the id of the form - can be overwritten
  */
  public function __construct(string $name)
  {
    parent::__construct($name);
    //$this->setAttribute('id', $name);// set ID if inputfield will be rendered manually without the form class
    $this->setAttribute('name', $name);// set name attribute
    // instantiate all objects
    $this->fieldWrapper = new FieldWrapper(); // instantiate the field wrapper object
    $this->inputWrapper = new InputWrapper(); // instantiate the input wrapper object
    $this->label = new Label(); // instantiate the label object
    $this->errormessage = new Errormessage(); // instantiate the error message object
    $this->notes = new Notes(); // instantiate the notes object
    $this->description = new Description(); // instantiate the description object
    $this->validator = new \Valitron\Validator(array()); // instantiate the valitron object
    //grab the markup type (fe uikit, none, bootstrap,...) and save it to a variable
    $this->markupType = $this->moduleConfig['input_framework'];
    // set sanitizer text to all inputfields by default
    $this->setSanitizer('text');
  }



  /** Wrappers **/

  /**
  * Get the input wrapper object for further manipulation on per field base
  * Use this method if you want to add custom attributes or remove attributes to and from the inputfield wrapper
  * @return InputWrapper
  */
  public function getInputWrapper(): InputWrapper
  {
    return $this->inputWrapper;
  }

  /**
  * Get the field wrapper object for further manipulation on per field base
  * Use this method if you want to add custom attributes or remove attributes to and from the field wrapper
  * @return FieldWrapper
  */
  public function getFieldWrapper(): FieldWrapper
  {
    return $this->fieldWrapper;
  }

  /**
  * Remove the input wrapper
  */
  protected function removeInputWrapper()
  {
    $this->removeInputWrapper = true;
  }

  /**
  * Add the input wrapper
  */
  protected function addInputWrapper()
  {
    $this->removeInputWrapper = false;
  }


  /**
  * Remove the field wrapper
  */
  protected function removeFieldWrapper()
  {
    $this->removeFieldWrapper = true;
  }

  /**
  * Add the field wrapper
  */
  protected function addFieldWrapper()
  {
    $this->removeFieldWrapper = false;
  }

  /**
  * Set (a) default value(s) for an inputfield (fe firstname and lastname if user is logged in)
  * Each value has to be separated by a comma (defaultvalue1, defautlvalue2)
  * @param string|NULL $default - default value(s)
  */
  public function setDefaultValue($default = NULL)
  {
    if($default !== NULL){
        // get all values as an array
        $default = func_get_args();
        //sanitize array values
        array_walk($default, function(&$item) {
          $item = (string) trim($item);
        });
        // only on checkbox multiple and select multiple are more than 1 parameter allowed
        if(($this->className() == 'InputCheckboxMultiple') || ($this->className() == 'InputSelectMultiple')){
          $value = $default;
        } else {
          // take only the first $parameters
          $value = $default[0];
        }
      // run only on inputfields that can only have 1 value (not more)
      if(($this->className() != 'InputCheckboxMultiple') || ($this->className() != 'InputSelectMultiple')){
        $this->setAttribute('value', $value); // set only default value and value if a value attribute is present or it is a select input field
        $this->defaultValue = $default;
      }

    }
  }

  /**
  * Return the default value
  * @return array
  */
  protected function getDefaultValue(): array
  {
    return $this->defaultValue;
  }

  /** Label **/
  /**
  * Set the label text
  * @param string $label
  * @return Label
  */
  public function setLabel(string $label): Label
  {
    $this->label->setText($label);
    return $this->label;
  }


  /**
  * Get the label
  * @return Label
  */
  protected function getLabel(): Label
  {
      return $this->label;
  }


  /** Notes **/
  /**
  * Set the notes text
  * @param string $notes
  * @return Notes
  */
  public function setNotes(string $notes): Notes
  {
    $this->notes->setText($notes);
    return $this->notes;
  }


  /**
  * Get the Notes object
  * @return Notes
  */
  protected function getNotes(): Notes
  {
      return $this->notes;
  }


  /** Description **/
  /**
  * Set the description text
  * @param string $description
  * @return Description
  */
  public function setDescription(string $description): Description
  {
    $this->description->setText($description);
    return $this->description;
  }


  /**
  * Get the Description object
  * @return Description
  */
  protected function getDescription(): Description
  {
      return $this->description;
  }

  /** Error message **/
  /**
  * Set the error message text
  * Will be set during processing of the form, not by the user
  * @param string $errorMessage
  * @return Errormessage
  */
  protected function setErrorMessage(string $errorMessage): Errormessage
  {
    $this->errormessage->setText($errorMessage);
    return $this->errormessage;
  }


  /**
  * Get the Errormessage object
  * You can use this to manipulate attributes of the error message on per field base
  * Example $field->getErrorMessage()->setAttribute('class', 'myErrorClass');
  * @return Errormessage
  */
  public function getErrorMessage(): Errormessage
  {
      return $this->errormessage;
  }


  /**
  * Set a sanitizer from ProcessWire sanitizer methods to sanitize the input value
  * Checks first if the entered sanitizer method exists, otherwise informs you that this method does not exist
  * Check https://processwire.com/api/ref/sanitizer/ for all sanitizer methods
  * @param string $sanitizer - the name of the sanitizer
  */
  public function setSanitizer(string $sanitizer)
  {
    $sanitizer = trim(strtolower($sanitizer));
    //if sanitizer method exist add the name of the sanitizer to the sanitizer property
    if(method_exists($this->wire('sanitizer'), $sanitizer)){
      $this->sanitizer = array_merge($this->sanitizer, [$sanitizer]);
    } else {
      throw new \Exception('This sanitizer method does not exist in ProcessWire.');
    }
  }

  /**
  * Return all names of the sanitizer methods
  * @return array
  */
  protected function getSanitizer()
  {
    return $this->sanitizer;
  }

  /**
  * Remove all sanitizers if necessary from the inputfield
  * If you need to disable it - for whatever reason - you can use this method to remove all sanitizers from an inputfield
  */
  public function removeSanitizers()
  {
    $this->sanitizer = [];
  }



  /** Valitron validation methods **/

  /**
  * Set a validator rule to validate the input value
  * Checks first if the validator method exists, otherwise does nothing
  * Check https://processwire.com/api/ref/sanitizer/ for all sanitizer methods
  * @param string $validator - the name of the validator
  * @return $this
  */
  public function setRule()
  {
    $args = func_get_args();

    if(count($args)>1){

      $validator = $args[0]; // first argument is the validator name
      array_shift($args);// remove the first element
      $variables = $args;
    } else {
      $validator = $args[0]; // first argument is the validator name
      $variables = [];
    }

    $this->api = new ValitronAPI();
    $this->api->setValidator($validator);
    $result = $this->api->setRule($validator, $variables);
    $this->validatonRules[$result['name']] = ['options' =>$variables];
    return $this;
  }

  /**
  * Remove a validator which was set before
  * @param string $rule;
  * @return $this;
  */
  public function removeRule(string $rule)
  {
    $rules = $this->validatonRules;
    unset($rules[$rule]);
    $this->validatonRules = $rules;
    return $this;
  }
  /**
  * Method to overwrite default error message with a custom error message
  * Use the syntax {field} to output the Name of the field inside your custom message
  * @param string $msg - your custom error message text (fe {field} needs to be filled out)
  * @return $this
  */
  public function setCustomMessage(string $msg)
  {
    $result = $this->api->setCustomMessage($msg);
    $old = $this->validatonRules[$this->api->getValidator()];
    $new = ['customMsg' => $msg];
    // add the new value to the validationRules array
    $this->validatonRules[$this->api->getValidator()] = array_merge($old, $new);
    return $this;
  }

  /**
  * Method to change the field name inside the error message
  * If you need you can change fe 'Surname' to 'This field'
  * This only affects the fieldname inside the error message and not beside the inputfield
  * @param string $fielname
  * @return $this
  */
  public function setCustomFieldname(string $fieldname)
  {
    $result = $this->api->setCustomFieldName($fieldname);
    $old = $this->validatonRules[$this->api->getValidator()];
    $new = ['customFieldName' => $fieldname];
    // add the new value to the validationRules array
    $this->validatonRules[$this->api->getValidator()] = array_merge($old, $new);
    return $this;
  }


  /**
  * Get all validation rules for an inputfield
  * @return array
  */
  protected function getRules(): array
  {
    return $this->validatonRules;
  }

  /**
  * Check if element has a specific validator
  * @param string $ruleName ->fe required
  * @return boolean
  */
  protected function hasRule(string $ruleName): bool
  {
    if(array_key_exists(trim($ruleName), $this->getRules()))
      return true;
    return false;
  }


  /**
  * Get the error class for inputfields
  * @return string|null
  */
  protected function getinputErrorClass()
  {
    return $this->getCSSClass('input_errorClass');
  }

  /**
  * Check if post value of the inputfield is present
  * @return bool -> true: the post value is present, false: post value is not there
  */
  protected function hasPostValue(): bool
  {
    $name = str_replace('[]', '', $this->getAttribute('name')); // remove brackets from attribute name of multivalue input fields
    if(isset($this->getServerMethod()[$name]))
      return true;
    return false;
  }

  /**
  * Get the post value of the inputfield is present
  * @return string|null
  */
  protected function getPostValue()
  {
    if($this->hasPostValue()){
      $name = str_replace('[]', '', $this->getAttribute('name')); // remove brackets from attribute name of multivalue input fields
      return $this->getServerMethod()[$name];
    }
    return []; // return empty array to prevent error on in_array function

  }

  /**
  * Render the inputfield including wrappers, notes, description, prepend markup, appendmarkup and error message
  * @return string
  */
  public function render(): string
  {
    if($this->hasRule('required')){
      $this->label->setRequired();
      if($this->moduleConfig['input_addHTML5req'])
        $this->setAttribute('required');
    }
    $out = $content = '';

    $className = $this->className();

    $inputfield = 'render'.$className;
    $input = $this->$inputfield();

    switch($className){
      case('InputHidden'):
        $this->removeAttribute('class'); // we need no class attribute for styling on hidden fields
        $out .= $this->renderInputHidden(); // we do not need label, wrapper divs,... only the input element
      case('InputCheckbox'):
      case('InputRadio'):
        switch($this->markupType){
          case('bootstrap5.json'):
            $this->label->setCSSClass('checklabelClass');
            $label = $input.$this->label->render();
          break;
          default: // uikit3.json, none
          // render label and input different on single checkbox and single radio
           $this->label->removeAttributeValue('class', $this->getCSSClass('checklabel'));
           $this->label->setContent($input.$this->getLabel()->getText());
           $label = $this->label->render();
        }
        // error message and error class
        if($this->getErrormessage()->getText()){
          $errormsg = $this->errormessage->render(); //add error message for validation
          $this->fieldWrapper->setAttribute('class', $this->fieldWrapper->getErrorClass()); // add error class to the wrapper container
        } else {
          $errormsg = '';
        }

        if($this->removeInputWrapper){
          $content .= $label.$errormsg;
        } else {
          $this->inputWrapper->setContent($label.$errormsg);
          // set class to inputwrapper
          switch($this->moduleConfig['input_framework']){
            case('bootstrap5.json'):
              $this->inputWrapper->removeAttribute('class');
              $this->inputWrapper->setAttribute('class', 'form-check');
            break;
          }
          $content .= $this->inputWrapper->render();
        }
      break;
      default:
        if($this->getLabel()->getText())
         $content .= $this->label->render(); // add label

         if($className != 'InputHidden'){
           // Error message
           if($this->getErrormessage()->getText()){
             $errormsg = $this->errormessage->render(); //add error message for validation
             $this->fieldWrapper->setAttribute('class', $this->fieldWrapper->getErrorClass()); // add error class to the wrapper container
           } else {
             $errormsg = '';
           }
         }
         // add inputwrapper
         if(!$this->removeInputWrapper){
           $this->inputWrapper->setContent($input.$errormsg);
           $content .= $this->inputWrapper->render();
         } else {
           $content .= $input.$errormsg;
         }

    }

    // Add label and wrapper divs, error messages,... to all elements except hidden inputs
    if($className != 'InputHidden'){

      // Description
      if($this->getDescription()->getText())
        $content .= $this->description->render();
      // Notes
      if($this->getNotes()->getText())
        $content .= $this->notes->render();
      if($this->removeFieldWrapper){
        $out .= $content;
      } else {
        $this->fieldWrapper->setContent($content);
        $out .= $this->fieldWrapper->render();
      }

    }

    return $out;
  }


    public function __toString()
    {
      return $this->render();
    }

}
