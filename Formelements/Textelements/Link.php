<?php
namespace FrontendForms;

/**
* A class for creating links
*/

class Link extends TextElements  {

  protected $url = '';
  protected $linkText = '';

  public function __construct($id = null)
  {
    parent::__construct($id);
    $this->setTag('a');
  }

  public function setUrl(string $url)
  {
    $this->setAttribute('href', $url);
    return $this;
  }

  public function getUrl()
  {
    return $this->getAttribute('href');
  }

  public function setLinkText(string $linktext)
  {
    $this->setText($linktext);
    return $this;
  }

  public function getLinkText()
  {
    return $this->getText();
  }

}
