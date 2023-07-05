<?php

namespace App\View\Components\Widgets;

use Illuminate\View\Component;

class GeneralComponent extends Component
{
    public $general_widgets;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->general_widgets = [
            ['generalPercentageTitle' => 'Overall Visitor', 'generalCounter' => '24.15M', 'generalPercentage' => '2.43%'],
            ['generalPercentageTitle' => 'Visitor Duration', 'generalCounter' => '12:38', 'generalPercentage' => '12.65%'],
            ['generalPercentageTitle' => 'Pages/Visit', 'generalCounter' => '639.82', 'generalPercentage' => '5.62%'],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.widgets.general-component');
    }
}
