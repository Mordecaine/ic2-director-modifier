<?php
      namespace Icinga\Module\Directorextension;
      use Icinga\Application\Modules\Module;
      $this->provideHook('director/PropertyModifier', 'Icinga\Module\Directorextension\ProvidedHook\Director\PropertyModifier\PropertyModifierRegexSearch');
      $this->provideHook('director/PropertyModifier', 'Icinga\Module\Directorextension\ProvidedHook\Director\PropertyModifier\PropertyModifierConvertUnixTimestamp');
      $this->provideHook('director/PropertyModifier', 'Icinga\Module\Directorextension\ProvidedHook\Director\PropertyModifier\PropertyModifierCalculate');
      $this->provideHook('director/PropertyModifier', 'Icinga\Module\Directorextension\ProvidedHook\Director\PropertyModifier\PropertyModifierChangeHashElement');
?>
