<?php
namespace FrontendForms;

/**
* Class for linking Valitron library with Inputfield class and form class to set custom values at form validation
*/

/**
* Class and Function List:
* Function list:
* - __construct()
* - setValidator()
* - getValidator()
* - setRule()
* - setCustomMessage()
* - getCustomMessage()
* - setCustomFieldName()
* - getCustomFieldName()
* Classes list:
* - ValitronAPI
*/

class ValitronAPI  {

  protected $valitron; // the valitron object
  protected $validator = ''; // name of the validator
  protected $customMessage = ''; // the custom error message
  protected $customFieldname = ''; // the custom field name

  public function __construct()
  {
    $this->valitron = new \Valitron\Validator(array());

  }

  /**
  * Set the name of the field validator (fe required)
  * @param string $validator
  */
  public function setValidator(string $validator)
  {
    $this->validator = $validator;
  }

  /**
  * Get the name of the field validator (fe required)
  * @return string
  */
  public function getValidator()
  {
    return $this->validator;
  }


  /**
  * Set a validator rule to validate the input value
  * Checks first if the validator method exists, otherwise does nothing
  * Check https://processwire.com/api/ref/sanitizer/ for all sanitizer methods
  * @param string $validator - the name of the validator
  * @return Validator
  */
  public function setRule(string $validator, $options = [])
  {
    $v = $this->valitron; // get the valitron object
    $validator = trim($validator);
    //if validator method exist add the name of the validator to the validatorRules array
    //if((method_exists($v, 'validate'.ucfirst($validator))) || (method_exists($v, $validator))){

      return ['name' => $validator,'options' => $options];
    /*} else {
      $errorText = sprintf("The validation method %s does not exist", $validator);
      throw new \Exception($errorText, 1);
    }*/
  }

  

  /**
  * Set the custom error message of the field validator
  * @param string $msg
  */
  public function setCustomMessage(string $msg)
  {
    $this->customMessage = $msg;
    return $msg;
  }

  /**
  * Get the custom error message of the field validator
  * @return string
  */
  public function getCustomMessage()
  {
    return $this->customMessage;
  }

  /**
  * Set the custom field name for the error message
  * @param string $fieldname
  */
  public function setCustomFieldName(string $fieldname)
  {
    $this->customFieldname = $fieldname;
    return $fieldname;
  }

  /**
  * Get the custom field name for the error message
  * @return string
  */
  public function getCustomFieldName()
  {
    return $this->customFieldname;
  }

}
