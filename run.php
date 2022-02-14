<?php

use Icinga\Module\Directorextension\ProvidedHook\Director\PropertyModifier\PropertyModifierRegexSearch;
$this->provideHook('director/PropertyModifier', PropertyModifierRegexSearch::class);
