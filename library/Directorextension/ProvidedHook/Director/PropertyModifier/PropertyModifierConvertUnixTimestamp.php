<?php

namespace Icinga\Module\Directorextension\ProvidedHook\Director\PropertyModifier;

use Icinga\Module\Director\Hook\PropertyModifierHook;
use Icinga\Exception\ConfigurationError;
use Icinga\Module\Director\Web\Form\QuickForm;
use DateTime;
use DateTimeZone;

class PropertyModifierConvertUnixTimestamp extends PropertyModifierHook
{
    public static function addSettingsFormFields(QuickForm $form)
    {
        $form->addElement('text', 'timestamp_format', array(
            'label'       => 'Format',
            'description' => $form->translate(
                'Define the syntax of the timestamp: https://www.php.net/manual/en/datetime.format.php. Default is d-m-Y H:i:s'
            ),
            'value'       => 'd-m-Y H:i:s',
            'required'    => false,
        ));

        $form->addElement('text', 'timezone', array(
            'label'       => 'GMT',
            'description' => $form->translate(
                'Define the Timezone. List of possible timezones https://www.w3schools.com/php/php_ref_timezones.asp. Default is Europe/Berlin'
            ),
	    'value'	  => 'Europe/Berlin',
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
       $gmtOffset = $this->getSetting('timezone');
       $timestamp_format = $this->getSetting('timestamp_format');

       if (!isset($timestamp_format) or $timestamp_format == '') {
           $timestamp_format = 'd-m-Y H:i:s';
       }

       if (!isset($gmtOffset) or $gmtOffset == '') {
           $gmtOffset = 'Europe/Berlin';
       }

       $timezone = new DateTimeZone($gmtOffset);
       $datetime = new DateTime();

       $datetime->setTimestamp($value);
       $datetime->setTimezone($timezone);

       $datetime_str = $datetime->format($timestamp_format);


       return $datetime_str;
    }
}
