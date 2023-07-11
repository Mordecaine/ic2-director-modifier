<?php

namespace Icinga\Module\Directorextension\ProvidedHook\Director\PropertyModifier;

use Icinga\Module\Director\Hook\PropertyModifierHook;
use Icinga\Exception\ConfigurationError;
use Icinga\Module\Director\Web\Form\QuickForm;
use DateTime;

class PropertyModifierConvertUnixTimestamp extends PropertyModifierHook
{
    public static function addSettingsFormFields(QuickForm $form)
    {
        $form->addElement('text', 'timestamp_format', array(
            'label'       => 'Format',
            'description' => $form->translate(
                'Define the syntax of the timestamp: https://www.php.net/manual/en/datetime.format.php. Default is d-m-Y H:i:s'
            ),
            'required'    => false,
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

    public function transform($value)
    {
       if (!isset($value)) {
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
       }
       $datetime = new DateTime();
       $datetime->setTimestamp($value);

       if (!isset($timestamp_format)) {
           $timestamp_format = 'd-m-Y H:i:s';
       }

       return $datetime->format($timestamp_format);
    }
}
