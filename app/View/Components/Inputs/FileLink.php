<?php

namespace App\View\Components\Inputs;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FileLink extends Component
{
    public string $label;
    public string $name;
    public string $value;

    public function __construct($label, $name, $value = null)
    {
        $this->label = $label;
        $this->name = $name;
        $this->value = $value != null ? $value : "";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // Trỏ đến view mới 'components.inputs.file-link'
        return view('components.inputs.file-link');
    }
}