<?php
      namespace Icinga\Module\Directorextension;
      use Icinga\Application\Modules\Module;
      $this->provideHook('director/PropertyModifier', Icinga\Module\Directorextension\ProvidedHook\Director\PropertyModifier\PropertyModifierRegexSearch);
?>
