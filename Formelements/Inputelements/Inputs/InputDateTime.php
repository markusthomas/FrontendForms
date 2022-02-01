<?php
namespace FrontendForms;

/**
* Class for creating an input datetime element
*/

class InputDateTime extends Input  {

  protected $inputDateTimeFormat = 'Y-m-d H:i:s'; // default datetime format

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setAttribute('type', 'datetime-local');
  }

  /**
  * Set the format of the input datetime for manually entered value
  * This format has to be used if you enter date and time manually
  * @param string $inputDateTimeFormat - fe 'Y-m-d H:i:s'
  */
  public function setInputFormat(string $inputDateTimeFormat)
  {
    $this->inputDateTimeFormat = trim($inputDateTimeFormat);
  }

  /**
  * Get the format of the input datetime for manually entered value
  * @return string - fe 'Y-m-d H:i:s'
  */
  public function getInputFormat(): string
  {
    return $this->inputDateTimeFormat;
  }

  /**
  * Create a date time string in the format to a RFC339 date time string
  * @param string $inputDate - the datetime string in the previous mentioned format
  * @return string
  */
  private function createDateTime(string $inputDate): string
  {
    // if date time is not in RFC3339 format convert it
    if(\DateTime::createFromFormat(\DateTime::RFC3339, $inputDate) === FALSE) {
      $datetime = \DateTime::createFromFormat($this->getInputFormat(), $inputDate);
      return $datetime->format(\DateTime::RFC3339);
    }
    return $inputDate;
  }

  /**
  * Render the input element
  * @return string
  */
  public function ___renderInputDateTime(): string
  {
    if($this->getAttribute('value'))
      $this->setAttribute('value', $this->createDateTime($this->getAttribute('value')));
    return $this->renderInput();
  }

}
