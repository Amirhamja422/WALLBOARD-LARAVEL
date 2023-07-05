<?php

namespace App\View\Components\Widgets;

use Illuminate\View\Component;

class InputComponent extends Component
{
    public $inputColClass;
    public $inputLabel;
    public $inputId;
    public $inputType;
    public $required;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($inputColClass, $inputLabel, $inputId, $inputType, $required)
    {
        $this->inputColClass = $inputColClass;
        $this->inputLabel = $inputLabel;
        $this->inputId = $inputId;
        $this->inputType = $inputType;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.widgets.input-component');
    }
}
