<?php

namespace Icinga\Module\Directorextension\ProvidedHook\Director\PropertyModifier;

use Icinga\Module\Director\Hook\PropertyModifierHook;
use Icinga\Exception\ConfigurationError;
use Icinga\Module\Director\Web\Form\QuickForm;

class PropertyModifierChangeHashElement extends PropertyModifierHook
{
    public static function addSettingsFormFields(QuickForm $form)
    {
        $form->addElement('text', 'hashkey', array(
            'label'       => 'Hash Key',
            'description' => $form->translate(
                'Hash Key to identify and change the hash value'
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

        $form->addElement('text', 'new_value', array(
            'label'       => 'New Value',
            'description' => $form->translate(
                'The new value which should replace the regex matched element'
            ),
            'required'    => true,
        ));

	$form->addElement('select', 'when_missing', [
            'label'       => $form->translate('When not available'),
            'required'    => true,
            'description' => $form->translate(
                'What should happen when the specified element is not available?'
            ),
            'value'        => 'keep_value',
            'multiOptions' => $form->optionalEnum([
                'fail' => $form->translate('Let the whole Import Run fail'),
                'null' => $form->translate('return NULL'),
                'empty_string' => $form->translate('return emtpy String'),
                'keep_value' => $form->translate('Keep the original Value'),
            ])
        ]);

    }

    public function hasArraySupport()
    {
        return true;
    }

    public function getName()
    {
        return 'Change Hash value';
    }

    public function transform($arr)
    {
       foreach ($arr as $key => &$value) {
           # if the key exist, check the value
           if ($key == $this->getSetting('hashkey')) {
               # Check that key matches with regex pattern
               $new_value = preg_replace(
                   $this->getSetting('pattern'),
                   $this->getSetting('new_value'),
                   $value,
               );
               
               if ($new_value === $value) {
                   switch($this->getSetting('when_missing')) {
                       case "null":
                           $value = null;
                           break;
                       case "empty_string":
                           $value = "";
                           break;
                       case "keep_value":
                           break;
                       default:
                           throw new ConfigurationError(
                               '"%s" do not match with "%s"',
                	       $this->getSetting('pattern'),
                	       $value
                           );
                   }
               } else {
                   $value = $new_value;
               }
         } 
         else {
           continue;
         }
      }
        return $arr;
   }
}
