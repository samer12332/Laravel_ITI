<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    public string $buttonClasses;

    public function __construct(
        public string $type = 'primary',
        public string $buttonType = 'button',
        public ?string $href = null,
    ) {
        $styles = [
            'primary' => 'bg-cyan-500 hover:bg-cyan-600',
            'secondary' => 'bg-blue-500 hover:bg-blue-600',
            'danger' => 'bg-rose-500 hover:bg-rose-600',
        ];

        $this->buttonClasses = $styles[$type] ?? $styles['primary'];
    }

    public function render(): View|Closure|string
    {
        return view('components.button');
    }
}
