<?php


namespace Naran\Axis\Core\Scheme\Registerers;

use Closure;
use Naran\Axis\Core\Layout\LayoutInterface;
use Naran\Axis\Core\Scheme\Registrables\Block;

class BlockRegisterer implements RegistererInterface
{
    private LayoutInterface $layout;

    /** @var ?Closure */
    private ?Closure $registrables;

    public function __construct(LayoutInterface $layout, ?Closure $registrables)
    {
        $this->layout       = $layout;
        $this->registrables = $registrables;

        add_action('enqueue_block_editor_assets', [$this, 'registerItems']);
    }

    public function registerItems()
    {
        foreach ($this->getItems() as $item) {
            if ($item instanceof Block) {
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

        return apply_filters('naran_axis_block_registrables', $items, $this->layout->getSlug());
    }

    public function unregisterItems()
    {
        foreach ($this->getItems() as $item) {
            if ($item instanceof Block) {
                $item->unregister();
            }
        }
    }
}
