<?php
namespace Frontendforms;

echo '<h1>Complex form</h1>';
echo '<p>Please take the following example on how to write a complex form.<br>
Use UiKit to display the form properly.</p>';

$form = new Form('registration');
$form->setMinTime(8);
$form->setMaxTime(3600);
//$form->setMaxAttempts(5);
$form->setErrorMsg('Ouups! There are some errors.');
$form->setSuccessMsg('Congratulation! There are no errors.');


$userdata = new FieldsetOpen();
$userdata->setLegend('User data')->append('<p>Please fill out all required fields.</p>');
$form->add($userdata);

$singleRadio = new InputRadio('single');
$singleRadio->setLabel('Single radio button');
$singleRadio->setAttribute('value', 'single');
$singleRadio->setRule('required');
$singleRadio->setNotes('This field makes no sense');
$form->add($singleRadio);

$gender = new InputRadioMultiple('gender');
$gender->setLabel('Gender')->setAttribute('class', 'myextralabelclass');
$gender->setDefaultValue('Male');
$gender->addOption('Male', 'Male')->setAttribute('class','male');
$gender->addOption('Female', 'Female')->setAttribute('class','female');
$gender->addOption('Diverse', 'Diverse')->setAttribute('class','diverse');
$gender->getFieldWrapper()->setAttribute('class', 'uk-width-1-1')->removeAttributeValue('class', 'uk-margin');
$form->add($gender);

$firstname = new InputText('firstname');
$firstname->setLabel('Firstname');
$firstname->setRule('required');
$firstname->getFieldWrapper()->prepend('<div class="uk-child-width-1-2" uk-grid>')->removeAttributeValue('class', 'uk-margin');
$form->add($firstname);

$lastname = new InputText('lastname');
$lastname->setLabel('Lastname');
$lastname->setRule('required');
$lastname->getFieldWrapper()->append('</div>')->removeAttributeValue('class', 'uk-margin');
$form->add($lastname);

$street = new InputText('street');
$street->setLabel('Street');
$street->setRule('required');
$street->getFieldWrapper()->setAttribute('class', 'uk-width-3-4')->prepend('<div uk-grid>')->removeAttributeValue('class', 'uk-margin');
$form->add($street);

$number = new InputText('number');
$number->setLabel('Number');
$number->setRule('required');
$number->setRule('integer');
$number->getFieldWrapper()->setAttribute('class', 'uk-width-expand')->append('</div>')->removeAttributeValue('class', 'uk-margin');
$form->add($number);

$email = new InputEmail('email');
$email->setLabel('Email address');
$email->setSanitizer('email');
$email->setRule('required');
$email->setRule('email');
$email->setRule('emailDNS');
$email->getFieldWrapper()->prepend('<div class="uk-child-width-1-3" uk-grid>')->removeAttributeValue('class', 'uk-margin');
$form->add($email);

$phone = new InputTel('phone');
$phone->setLabel('Phone');
$phone->setRule('integer');
$phone->getFieldWrapper()->removeAttributeValue('class', 'uk-margin');
$form->add($phone);

$fax = new InputText('fax');
$fax->setLabel('Fax');
$fax->setRule('required')->setCustomFieldName('Fax number');
$fax->getFieldWrapper()->append('</div>')->removeAttributeValue('class', 'uk-margin');
$form->add($fax);

$birthday = new InputDate('birthday');
$birthday->setLabel('My birthday');
$birthday->setRule('required')->setCustomFieldName('The day of my birth');
$birthday->setRule('date');
$form->add($birthday);

$children = new InputNumber('children');
$children->setLabel('Number of children');
$children->setAttribute('min', '0');
$children->setAttribute('max', '15');
$children->setRule('required')->setCustomMessage('Please enter how much children do you have');
$form->add($children);

$userdataClose = new FieldsetClose();
$form->add($userdataClose);

$interestsOpen = new FieldsetOpen();
$interestsOpen->setLegend('My interest');
$form->add($interestsOpen);

$interests = new InputCheckboxMultiple('interest');
$interests->setLabel('I am interested in');
$interests->setDefaultValue('Webdesign');
$interests->addOption('Music', 'Music')->setChecked();
$interests->addOption('Webdesign', 'Webdesign');
$interests->addOption('Sports', 'Sports')->setChecked();
$interests->addOption('Photography', 'Photography');
$firstname->setRule('required');
$interests->alignVertical();
$form->add($interests);


$php = new Select('php');
$php->setLabel('My prefered PHP version is');
$php->setDefaultValue('PHP 8');
$php->addOption('PHP 6', 'PHP 6');
$php->addOption('PHP 7', 'PHP 7');
$php->addOption('PHP 8', 'PHP 8');
$form->add($php);

$css = new SelectMultiple('css');
$css->setLabel('I have knowledge in');
$css->setDefaultValue('Less', 'CSS 1');
$css->addOption('CSS 1', 'CSS 1');
$css->addOption('CSS 2', 'CSS 2');
$css->addOption('CSS 3', 'CSS 3');
$css->addOption('Less', 'Less');
$css->addOption('Sass', 'Sass');
$form->add($css);

$interestsClose = new FieldsetClose();
$form->add($interestsClose);

$accept = new InputCheckbox('accept');
$accept->setLabel('I accept the data privacy');
$accept->setRule('accepted')->setCustomMessage('You have to accept the data privacy');
$form->add($accept);

$newsletter = new InputCheckbox('newsletter');
$newsletter->setLabel('I want to register to the newsletter');
$newsletter->setChecked();
$form->add($newsletter);

$button = new Button('submit');
$button->setAttribute('value', 'Send');
$form->add($button);


if($form->isValid()){

  //print_r($form->getValues());
  // do what you want

}

// render the form
echo $form->render();
