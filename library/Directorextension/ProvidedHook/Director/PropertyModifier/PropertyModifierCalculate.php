<?php

namespace Icinga\Module\Directorextension\ProvidedHook\Director\PropertyModifier;

use Icinga\Module\Director\Hook\PropertyModifierHook;
use Icinga\Exception\ConfigurationError;
use Icinga\Module\Director\Web\Form\QuickForm;
use DateTime;
use DateTimeZone;

class PropertyModifierCalculate extends PropertyModifierHook
{
    public static function addSettingsFormFields(QuickForm $form)
    {
        $form->addElement('text', 'operand', array(
            'label'       => 'Operand',
            'description' => $form->translate('Insert the value which should be used to calculate'),
	    'value'	  => '0',
            'required'    => true,
        ));

        $form->addElement('text', 'round', array(
            'label'       => 'Round',
            'description' => $form->translate('Round result to digits'),
	    'value'	  => '1',
            'required'    => false,
        ));

	$form->addElement('select', 'operator', [
            'label'       => $form->translate('Operator'),
            'required'    => true,
            'description' => $form->translate(
                'Operator which should be used to calculate'
            ),
            'value'        => 'null',
            'multiOptions' => $form->optionalEnum([
                '*' => $form->translate('multiplication'),
                '+' => $form->translate('addition'),
                '-' => $form->translate('subtraction'),
                '/' => $form->translate('division'),
            ])
        ]);

    }

    public function transform($value)
    {
       if (isset($value)) {
	   $value = $this->tofloat($value);
           $operand = $this->tofloat($this->getSetting('operand'));
           $round = intval($this->getSetting('round'));
           switch($this->getSetting('operator')) {
               case "*":
                   return round($value*$operand, $round);
               case "+":
                   return round($value+$operand, $round);
               case "-":
                   return round($value-$operand, $round);
               case "/";
                   return round($value/$operand, $round);
           }
       }
    }

    private function tofloat($num)
    {
	    $dotPos = strrpos($num, '.');
	    $commaPos = strrpos($num, ',');
	    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos : 
	        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
	   
	    if (!$sep) {
	        return floatval(preg_replace("/[^0-9]/", "", $num));
	    } 
	
	    return floatval(
	        preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
	        preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
	    );
    }
}
