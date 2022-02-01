<?php
namespace FrontendForms;

/**
* This is the base class for creating text elements inside the form (fe. labels, description, notes,..)
*/

/**
* Class and Function List:
* Function list:
* - __construct()
* - setText()
* - getText()
* - ___render()
* - __toString()
* Classes list:
* - TextElements extends Tag
*/

class TextElements extends Element  {


  public function __construct($id = null)
  {
    parent::__construct($id);
    $this->setTag('p'); // default tag is paragraph - can be overwritten

  }

  /**
  * Set the text between the opening and closing tag
  * @param string $text
  */
  public function setText(string $text)
  {
    $this->setContent($text);
  }

  /**
  * Get the text for the text element
  * @return string
  */
  public function getText(): string
  {
    return $this->getContent();
  }


  /**
  * Render the text element
  * @return string
  */
  public function ___render(): string
  {
    if($this->wrapper){
      $this->wrapper->setContent($this->renderNonSelfclosingTag($this->getTag()));
      return $this->wrapper->render();
    }
    return $this->renderNonSelfclosingTag($this->getTag());
  }

  public function __toString()
  {
    return $this->render();
  }

}
