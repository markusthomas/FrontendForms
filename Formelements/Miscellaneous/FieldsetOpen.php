<?php
namespace FrontendForms;

/**
* Class for creating fieldsets opener
*/

class FieldsetOpen extends Tag  {

  protected $legend;

  public function __construct()
  {
    parent::__construct();
    $this->setTag('fieldset');
    $this->setCSSClass('fieldsetClass');
  }

  /**
  * Set the text for the legend
  * @param string $legendText
  * @return Legend
  */
  public function setLegend(string $legendText): Legend
  {
    $this->legend = new Legend();// instantiate legend object
    $this->legend->setText($legendText);
    return $this->legend;
  }

  /**
  * Render the fieldset open tag
  * @return string
  */
  public function ___render()
  {
    $this->append($this->legend->render());
    return $this->renderSelfclosingTag($this->getTag());
  }

  public function __toString()
  {
    return $this->render();
  }

}
