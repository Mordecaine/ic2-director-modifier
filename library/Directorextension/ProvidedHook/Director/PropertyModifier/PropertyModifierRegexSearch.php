<?php

namespace Icinga\Module\Directorextension\ProvidedHook\Director\PropertyModifier;

use Icinga\Module\Director\Hook\PropertyModifierHook;
use Icinga\Exception\ConfigurationError;
use Icinga\Module\Director\Web\Form\QuickForm;

class PropertyModifierRegexSearch extends PropertyModifierHook
{
    public static function addSettingsFormFields(QuickForm $form)
    {
        $form->addElement('text', 'pattern', array(
            'label'       => 'Regex pattern',
            'description' => $form->translate(
                'The pattern you want to search for. This can be a regular expression like /^www\d+\./'
            ),
            'required'    => true,
        ));

        $form->addElement('text', 'pattern', array(
            'label'       => 'Regex pattern',
            'description' => $form->translate(
                'The pattern you want to search for. This can be a regular expression like /^www\d+\./'
            ),
            'required'    => true,
        ));


	$form->addElement('select', 'when_missing', [
            'label'       => $form->translate('When not available'),
            'required'    => true,
            'description' => $form->translate(
                'What should happen when the specified element is not available?'
            ),
            'value'        => 'null',
            'multiOptions' => $form->optionalEnum([
                'fail' => $form->translate('Let the whole Import Run fail'),
                'null' => $form->translate('return NULL'),
                'empty_string' => $form->translate('return emtpy String'),
            ])
        ]);

    }

    public function getName()
    {
        return 'Regex Search';
    }

    public function transform($value)
    {
       preg_match(
	   $this->getSetting('pattern'),
	   $value,
	   $regex_match
       );

       if ($regex_match === array()) {
	   switch($this->getSetting('when_missing')) {
	       case "null":
                   return null;
               case "empty_string":
                   return "";
	       default:
                   throw new ConfigurationError(
                       '"%s" do not match with "%s"',
		       $this->getSetting('pattern'),
		       $value
	           );
           }
       } else {
           return $regex_match[0];
       }
    }
}
