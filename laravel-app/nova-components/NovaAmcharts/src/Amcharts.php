<?php

namespace Larabase\NovaAmcharts;

use Laravel\Nova\Fields\Field;

class Amcharts extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-amcharts';

    public function withTooltip($tooltip){
        return $this->withMeta(['tooltip' => $tooltip]);
    }
}
