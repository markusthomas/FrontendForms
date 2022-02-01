<?php
namespace FrontendForms;

/**
* Class for creating a datalist element
*/

class Datalist extends InputText  {

  use TraitOption;

  protected $listID = '';

  public function __construct(string $id)
  {
    parent::__construct($id);
    $this->listID = $id;
    $this->setAttribute('list', 'datalist-'.$id);
  }


  /**
  * Render the datalist input
  * @return string
  */
  public function ___renderDatalist(): string
  {
    $out = '';
    $method =  $this->getServerMethod();
    //set post value as value if present
    $this->setAttribute('value', $this->getPostValue());

    if($this->options){

      $options = '';

      foreach($this->options as $key=> &$option){

        if($option->hasAttribute('selected') && (!$this->hasAttribute('value'))){
          $this->setAttribute('value', $option->getAttribute('value'));
          $option->removeAttribute('selected'); //data list option has no selected attribute
        }
        $options .= $option->render();

      }
      // create and append datalist container
      $this->append('<datalist id="'.'datalist-'.$this->listID.'">'.$options.'</datalist>');
      $out = $this->renderInput();
    }
    return $out;
  }

}
