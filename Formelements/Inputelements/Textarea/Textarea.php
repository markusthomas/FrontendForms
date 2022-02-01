<?php
namespace FrontendForms;

/**
* Class to create textarea form elements
*/

class Textarea extends Inputfields  {

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->setTag('textarea');
    $this->setAttribute('rows', '5'); // default is 5
    $this->setCSSClass('textareaClass');
    $this->removeSanitizers();// remove all sanitizers by default
    $this->setSanitizer('textarea'); // add sanitizer textarea by default for security reasons
  }

  /**
  * Render the textarea input
  * @return string
  */
  public function ___renderTextarea(): string
  {
    $this->setContent($this->getAttribute('value'));
    return $this->renderNonSelfclosingTag($this->getTag(), true);
  }

}
