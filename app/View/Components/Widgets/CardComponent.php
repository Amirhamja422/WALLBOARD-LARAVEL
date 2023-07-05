<?php

namespace App\View\Components\Widgets;

use Illuminate\View\Component;

class CardComponent extends Component
{
    public $cardTitle;
    public $cardHeaderVisible;
    public $btnVisible;
    public $btnName;
    public $btnClass;
    public $btnIcon;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($cardTitle, $cardHeaderVisible, $btnVisible, $btnName, $btnClass, $btnIcon)
    {
        $this->cardTitle = $cardTitle;
        $this->cardHeaderVisible = $cardHeaderVisible;
        $this->btnVisible = $btnVisible;
        $this->btnName = $btnName;
        $this->btnClass = $btnClass;
        $this->btnIcon = $btnIcon;
        
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.widgets.card-component');
    }
}
