<?php


namespace Naran\Axis\Core\Scheme\Registerers;

use Closure;
use Naran\Axis\Core\Layout\LayoutInterface;
use Naran\Axis\Core\Scheme\Registrables\Ajax;

class AjaxRegisterer implements RegistererInterface
{
    private LayoutInterface $layout;

    /** @var ?Closure */
    private ?Closure $registrables;

    public function __construct(LayoutInterface $layout, ?Closure $registrables)
    {
        $this->layout       = $layout;
        $this->registrables = $registrables;

        add_action('init', [$this, 'registerItems']);
    }

    public function registerItems()
    {
        foreach ($this->getItems() as $item) {
            if ($item instanceof Ajax) {
                // TODO: parse callback
                $item->register();
            }
        }
    }

    public function unregisterItems()
    {
        foreach ($this->getItems() as $item) {
            if ($item instanceof Ajax) {
                // TODO: parse callback
                $item->unregister();
            }
        }
    }

    public function getItems(): array
    {
        if ($this->registrables) {
            $items = call_user_func($this->registrables);
        } else {
            $items = [];
        }

        return apply_filters('naran_axis_ajax_registrables', $items, $this->layout->getSlug());
    }
}
