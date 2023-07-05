<?php

namespace App\View\Components\Widgets;

use Illuminate\View\Component;

class CounterComponent extends Component
{
    public $counter_widgets;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->counter_widgets = [
            ['counterTitle' => 'Total Calls', 'counter' => number_format($data['total']), 'counterSummery' => '+2.5% from last week', 'counterIcon' => 'bi-telephone-plus', 'counterColorClass' => 'info', 'bgGradient' => 'scooter'],
            ['counterTitle' => 'Total Outbound', 'counter' => number_format($data['outbound']), 'counterSummery' => '+5.4% from last week', 'counterIcon' => 'bi-telephone-outbound', 'counterColorClass' => 'warning', 'bgGradient' => 'blooker'],
            ['counterTitle' => 'Total Inbound', 'counter' => number_format($data['inbound']), 'counterSummery' => '-4.5% from last week', 'counterIcon' => 'bi-telephone-inbound', 'counterColorClass' => 'success', 'bgGradient' => 'ohhappiness'],
            ['counterTitle' => 'Total Drop', 'counter' => number_format($data['drop']), 'counterSummery' => '+8.4% from last week', 'counterIcon' => 'bi-telephone-x', 'counterColorClass' => 'danger', 'bgGradient' => 'bloody'],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.widgets.counter-component');
    }
}
