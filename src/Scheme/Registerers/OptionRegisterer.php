<?php


namespace Naran\Axis\Core\Scheme\Registerers;

use Closure;
use Naran\Axis\Core\Layout\LayoutInterface;
use Naran\Axis\Core\Scheme\Registrables\Option;

class OptionRegisterer implements RegistererInterface
{
    private LayoutInterface $layout;

    /** @var ?Closure */
    private ?Closure $registrables;

    public function __construct(LayoutInterface $layout,?Closure  $registrables)
    {
        $this->layout       = $layout;
        $this->registrables = $registrables;

        add_action('init', [$this, 'registerItems']);
    }

    public function registerItems()
    {
        foreach ($this->getItems() as $key => $item) {
            if ($item instanceof Option) {
                $item->register();
            }
        }
    }

    public function unregisterItems()
    {
        foreach ($this->getItems() as $key => $item) {
            if ($item instanceof Option) {
                $item->register();
            }
        }
    }

    public function getItems(): array
    {
        if (is_callable($this->registrables)) {
            $items = call_user_func($this->registrables);
        } else {
            $items = [];
        }

        return apply_filters('naran_axis_ajax_registrable', $items, $this->layout->getSlug());
    }
}