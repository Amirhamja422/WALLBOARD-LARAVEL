<?php

namespace App\View\Components\Widgets;

use Illuminate\View\Component;

class ListCountComponent extends Component
{
    public $list_count_widgets;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->list_count_widgets = [
            ['listCountTitle' => 'Jeans', 'listCount' => '25', 'badgeBg' => 'primary'],
            ['listCountTitle' => 'T-Shirts', 'listCount' => '10', 'badgeBg' => 'success'],
            ['listCountTitle' => 'Shoes', 'listCount' => '65', 'badgeBg' => 'info'],
            ['listCountTitle' => 'Lingerie', 'listCount' => '14', 'badgeBg' => 'warning'],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.widgets.list-count-component');
    }
}
