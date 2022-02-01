<?php
namespace FrontendForms;

use \ProcessWire\Wire as Wire;

/**
* This is the base class for creating HTML elements
* Extends from Wire to be able to make some methods hook-able
*/

/**
* Class and Function List:
* Function list:
* - __construct()
* - getCSSClass()
* - setCSSClass()
* - removeCSSClass()
* - sanitizeAttributeName()
* - sanitizeTagName()
* - setTag()
* - getTag()
* - getAttributes()
* - hasAttribute()
* - setAttribute()
* - getAttribute()
* - setAttributes()
* - getID()
* - removeAttribute()
* - removeAttributeValue()
* - isAssoc()
* - attributesToString()
* - setContent()
* - getContent()
* - prepend()
* - removePrepend()
* - append()
* - removeAppend()
* - renderSelfclosingTag()
* - renderNonSelfclosingTag()
* - pre()
* - getServerMethod()
* - getServerMethodName()
* - notSubmitted()
* - isSubmitted()
* Classes list:
* - Tag extends Wire
*/

abstract class Tag extends Wire  {

  const MULTIVALUEATTR = ['class', 'rel', 'style']; // array of all attributes that can have more than 1 value
  const BOOLEANATTR = [   // array of all boolean attributes
    'allowfullscreen',
    'allowpaymentrequest',
    'async',
    'autofocus',
    'autoplay',
    'checked',
    'controls',
    'default',
    'disabled',
    'formnovalidate',
    'hidden',
    'ismap',
    'itemscope',
    'loop',
    'multiple',
    'muted',
    'nomodule',
    'novalidate',
    'open',
    'playsinline',
    'readonly',
    'required',
    'reversed',
    'selected',
    'truespeed'
  ];

  /**
  * Array of class names of classes, where the value attribute can have multiple values and is not insed the MULTIVALUEATTR array
  */
  const MULTIVALCLASSES = [
    'SelectMultiple',
    'InputCheckboxMultiple'
  ];


  protected $attributes = []; // array that holds all attributes as a multilevel array
  protected $tag = 'div'; // default type of tag
  protected $content = ''; // the content between open and closing tag
  protected $moduleConfig = []; // array that holds all the configuration settings of this module
  protected $valitron; // the valitron class object
  protected $classes; // all pre-defined css-classes as stdClass object
  protected $prepend = ''; // markup before the tag
  protected $append = ''; // markup after the tag

  public function __construct()
  {
      // Make module config data reachable inside the classes
      if(empty($this->wire('modules')->getConfig('FrontendForms'))){
        // use the default data
        $this->moduleConfig = $this->wire('modules')->get('FrontendForms')->getDefaultData();
      } else {
        // use the config data stored in the DB
        $this->moduleConfig = $this->wire('modules')->getConfig('FrontendForms');
      }

      // load the json file from CSSClass directory
      //wire('config')->paths->FrontendForms . 'CSSClasses' . DIRECTORY_SEPARATOR . $this->moduleConfig['input_framework']
      //$this->classes = json_decode(file_get_contents('../modules/FrontendForms/CSSClasses/'.$this->moduleConfig['input_framework']));
      $this->classes = json_decode(file_get_contents($this->wire('config')->paths->FrontendForms . 'CSSClasses' . DIRECTORY_SEPARATOR . $this->moduleConfig['input_framework']));

  }

  /**
  * Get the value of a CSS class as defined in the json file inside the CSS class directory (if present)
  * @param string $className
  * @return string|none
  */

  protected function getCSSClass(string $className)
  {
    if(isset($this->classes->$className)){
        // if a default class was overwritten - use it instead
        if(isset($this->moduleConfig['input_'.$className]) && (!empty($this->moduleConfig['input_'.$className]))){
          return $this->moduleConfig['input_'.$className];
        } else {
          return $this->classes->$className;
        }
    }
  }


  /**
  * Add the pre-defined css class to an element (object) if present
  * @param string $className
  * @return $this;
  */
  protected function setCSSClass(string $className)
  {
    $class = $this->getCSSClass($className);
    if(($class != null) && ($class != '')){
      $this->setAttribute('class', $class);
    }
    return $this;
  }

  /**
  * Remove the pre-defined css class from an element (object) if present
  * @param string $className
  * @return $this;
  */
  protected function removeCSSClass(string $className)
  {
    $class = $this->getCSSClass($className);
    if(($class != null) && ($class != '')){
      $this->removeAttributeValue('class', $class);
    }
    return $this;
  }


  /**
  * Sanitize the attribute name
  * Must be all lowercase
  * @param string $name - the name of the attribute (fe class, href,...)
  * @return string - return the sanitized name
  */
  protected function sanitizeAttributeName(string $name): string
  {
    return strtolower(trim($name));
  }

  /**
  * Sanitize the tag name
  * Must be all lowercase
  * @param string $name - the name of the tag (fe div, span,...)
  * @return string - return the sanitized tag name
  */
  protected function sanitizeTagName(string $name): string
  {
    return strtolower(trim($name));
  }

  /**
  * Set the tag of an HTML element
  * @param string $tag (fe. a, input, ...)
  * @return $this
  */
  public function setTag(string $tag): self
  {
    $this->tag = $this->sanitizeTagName($tag);
    return $this;
  }

  /**
  * Get the tag of an HTML element
  * @return string (fe. a, input, ...)
  */
  public function getTag(): string
  {
    return $this->tag;
  }


  /**
  * Get all attributes of an Tag object
  * @return array
  */
  protected function getAttributes(): array
  {
      return $this->attributes;
  }

  /**
  * Check if an element has a specific attribute (fe href, class,...)
  * @param string $attributeName
  * @return boolean
  */
  protected function hasAttribute(string $attributeName): bool
  {
    return (array_key_exists($this->sanitizeAttributeName($attributeName), $this->getAttributes()));
  }


  /**
  * Set an attribute
  * @param string $key - the attribute name (fe. href, name, id,..)
  * @param string|array|null $value - the value as single value (fe. href) or if multiple values are allowed as an string separated by whitespace (fe class values)
  * value can also be null if attribute can have no value (fe checked, selected, multiple,..) so you can write setAttribute('multiple') or setAttribute('multiple', 'multiple')
  * @return $this
  */
  public function setAttribute(string $key, $value = null): self
  {
    $key = $this->sanitizeAttributeName($key);

    // value must be string, array or null
    if($value != null){
      if((!is_string($value)) && (!is_array($value)))
        return $this;

      if(in_array($this->className(), self::MULTIVALCLASSES) && (is_string($value)))

        if(in_array($key, self::MULTIVALUEATTR)){
          if(is_string($value)){

            // check if string contains whitespace between the words (fe class1 class2)
            if ($value == trim($value) && strpos($value, ' ') !== false) {
              $value = explode(' ', $value); // create array of string separated by whitespace
            }
            // check if string contains semicolon between the words (fe color:yellow;font-weight:bold)
            else if(strpos($value, ';') == true){
              $assocArray = array_filter(explode(';', $value)); // create array of string separated by semicolon
              //create an assoc array
              $value = [];
              foreach($assocArray as $v){
                $attr = explode(':', $v);
                $value[$attr[0]] = $attr[1];
              }
              $value = array_filter($value);
            }
          } else {

          $value = array_map('trim', $value);// trim all array values
        }
      } else {
        if(is_string($value)){
          $value = trim($value);
        } else {
          $value = array_map('trim', $value);// trim all array values
        }

      }
    if(in_array($key, self::MULTIVALUEATTR)){
      //get all values from this attributes
      $oldValues = isset($this->getAttributes()[$key]) ? $this->getAttributes()[$key] : [];
      if(is_string($value))
        $value = [$value];
      $multiValues = array_unique(array_merge($oldValues, $value));

      $this->attributes = array_merge($this->getAttributes(), [$key => $multiValues]);
    } else {

      $this->attributes = array_merge($this->getAttributes(), [$key => $value]);
    }
  } else {
    // boolean attributes
    if((substr($key, 0, 8) === 'data-uk-') || (substr($key, 0, 3) === 'uk-') || (in_array($key, self::BOOLEANATTR)))
        $this->attributes = array_merge($this->getAttributes(), [$key => $key]);

  }
    return $this;
  }


  /**
  * Get the value of an attribute by its name
  * @param string $attributeName - the name of the attribute (fe href)
  * @return string|array|NULL
  */
  public function getAttribute(string $attributeName)
  {
    $key = $this->sanitizeAttributeName($attributeName);
    if(array_key_exists($key, $this->getAttributes()))
      return $this->getAttributes()[$key];
  }

  /**
  * Set multiple attributes at once as an assoc. array
  * @param array $attributes - fe (['class' => 'myClass', 'id' => 'myId'])
  * @return $this;
  */
  public function setAttributes(array $attributes)
  {
    foreach($attributes as $key => $value){
      if($key){
        $this->setAttribute($key, $value);
      } else {
        $this->setAttribute($value);
      }
    }
  }

  /*******
  * ALIAS *
  *********/

  /**
  * Get the id of the element
  * @return string|null
  */
  public function getID()
  {
    return $this->getAttribute('id');
  }

  /**
  * Remove attribute with a specific name
  * @param string $attributeName (fe href, id,...)
  * @return $this
  */
  public function removeAttribute(string $attributeName)
  {
    $name = $this->sanitizeAttributeName($attributeName);
    if(array_key_exists($name, $this->getAttributes()))
      unset($this->attributes[$name]);
    return $this;
  }


  /**
  * Remove a specific value of a specific attribute
  * If it is an attribute where only 1 value is allowed (fe. id), than the complete attribute will be removed
  * @param string $attributeName -> the attribute name (fe class)
  * @param string $attributeValue (fe my class)
  * @return $this
  */
  public function removeAttributeValue(string $attributeName, $attributeValue = null)
  {
    $key = $this->sanitizeAttributeName($attributeName);

    if($attributeValue){
      $value = trim($attributeValue);
      // remove values form assoc. arrays like style attribute
      if($this->isAssoc($this->getAttributes()[$key])){
        if(array_key_exists($attributeValue, $this->attributes[$key])){
          if(in_array($key, self::MULTIVALUEATTR)){
            unset($this->attributes[$key][$value]);
          }
        }
      } else {
        // remove values from non assoc. arrays like class, rel, id,...
        if(array_key_exists($key, $this->getAttributes())){
          if(in_array($value, $this->attributes[$key]))
            if(in_array($key, self::MULTIVALUEATTR)){
              if(count($this->attributes[$key]) > 1){
                $this->attributes[$key] = array_diff($this->attributes[$key], array($value));
              } else {
                unset($this->attributes[$key]);
              }
            } else {
              unset($this->attributes[$key]);
            }
        }
      }
    }
    return $this;
  }

  /**
  * Check if array is assoc.
  * @param array $array - the array to check
  * @return boolean - true if it is assoc. array
  */
  protected function isAssoc(array $array): bool
  {
    return (count(array_filter(array_keys($array), 'is_string')) > 0) ? true : false;
  }

  /**
  * Renders all attributes as a string
  * @param boolean $selfClosing - if true the value attribute will be not rendered as an attribute.
  * @return string
  */
  protected function attributesToString($selfClosing = true): string
  {
    $allAttributes = $this->getAttributes();
    //remove value attribute from attributes array if selfclosing tag
    if((!$selfClosing) && ($this->getTag() != 'option'))
      unset($allAttributes['value']);

    $out = '';
    $attributes = [];

    if(count($allAttributes)){
      foreach($allAttributes as $name => $value){
        if(is_array($value)){

        // if value is assoc array than chain the values without whitespace as separator
         if($this->isAssoc($value)){
           $newArray = [];
           foreach($value as $key => $val){
             $newArray[] = $key.':'.$val;
           }
           $value = implode(';', $newArray);
         } else {
           // if numeric add a whitespace as separator between the attribute values ( fe class, rel,..)
           $value = implode(' ', $value);
         }
       }
         if(in_array($value, self::BOOLEANATTR)){
           $attributes[] = $value;
         } else {
           $attributes[] = $name.'="'.$value.'"';
         }
      }

    $out = ' '.implode(' ', $attributes);
    }
    return $out;
  }

  /**
  * Set the value of the content between to open and closing tag
  * @param string|null $content
  */
  public function setContent($content)
  {
    $this->content = $content;
  }

  /**
  * Get the value of a content if present
  * @return string|null
  */
  public function getContent()
  {
    return $this->content;
  }

  /**
  * Add a markup before this tag (fe div tag for special grid etc.)
  * @param string|null $markup - fe <div class="grid">
  * @return $this
  */
  public function prepend(?string $markup)
  {
    $this->prepend = $markup;
    return $this;
  }

  /**
  * Remove markup from prepend position
  * @return $this;
  */
  public function removePrepend()
  {
    if($this->prepend)
      $this->prepend = null;
    return $this;
  }

  /**
  * Add a markup after the tag
  * @param string|null $markup - fe </div>
  * @return $this
  */
  public function append(?string $markup)
  {
    $this->append = $markup;
    return $this;
  }

  /**
  * Remove markup from append position
  * @return $this;
  */
  public function removeAppend()
  {
    if($this->append)
      $this->append = null;
    return $this;
  }


  /**
  * Base render method for selfclosing HTML tags
  * @param string $tag - the selfclosing tag itself (fe input, hr,...)
  * @return string
  */
  protected function renderSelfclosingTag(string $tag): string
  {
      $out = '';
      if($this->prepend)
        $out .= $this->prepend;
      $out .= '<'.$tag.$this->attributesToString(true).'>';
      if($this->append)
        $out .= $this->append;
      return $out;
  }

  /**
  * Base render method for nonselfclosing HTML tags
  * @param string $tag - the non selfclosing tag itself (fe div, form,...)
  * @param boolean $showNoContent - tag should be displayed if there is no content (true) or not (false)
  * @return string
  */
  protected function renderNonSelfclosingTag(string $tag,  $showNoContent = false): string
  {
    $out = '';
    if($this->getContent() == null){
      $show = ($showNoContent) ? true : false;
    } else {
      switch($this->getContent()){
        case(''):
          $show = ($showNoContent) ? true : false;
        break;
        default:
          $show = true;
      }
    }
    if($show){
      if($this->prepend)
        $out .= $this->prepend;
      $out .= '<'.$tag.$this->attributesToString(false).'>'.$this->getContent().'</'.$tag.'>';
      if($this->append)
        $out .= $this->append;
    }
    return $out;
  }

  /**
  * An internal method only to output print_r in formatted way for better readability
  * Only for dev purposes
  * @param string|array|object $str
  * @return string
  */
  public static function pre($str)
  {
    echo "<pre>";
    print_r($str);
    echo "</pre>";
  }

  /**
  * Get the server method after form was submitted
  * @return array $_GET or $_Post
  */
  protected function getServerMethod(): array
  {
    return ($_SERVER['REQUEST_METHOD'] === 'POST') ? $_POST : $_GET;
  }

  /**
  * Check if form was submitted or not
  * @return bool -> true: the form was not submitted, false: the form was submitted
  */
  protected function notSubmitted(): bool
  {
    return (empty($this->getServerMethod())) ? true : false;
  }

  /**
  * Check if form was submitted or not
  * @return bool -> true: the form was submitted, false: the form was not submitted
  * It is the opposition of notSubmitted() method
  */
  protected function isSubmitted(): bool
  {
    return $this->notSubmitted();
  }


}
