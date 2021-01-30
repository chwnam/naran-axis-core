<?php


namespace Naran\Axis\Core\Scheme\Registerers;

use Closure;
use Naran\Axis\Core\Layout\LayoutInterface;
use Naran\Axis\Core\Scheme\Registrables\Style;

class StyleRegisterer implements RegistererInterface
{
    private LayoutInterface $layout;

    private ?Closure $commonRegistrables;

    private ?Closure $adminRegistrables;

    private ?Closure $frontRegistrables;

    public function __construct(
        LayoutInterface $layout,
        ?Closure $commonRegistrables,
        ?Closure $adminRegistrables,
        ?Closure $frontRegistrables
    ) {
        $this->layout = $layout;

        $this->commonRegistrables = $commonRegistrables;
        $this->adminRegistrables  = $adminRegistrables;
        $this->frontRegistrables  = $frontRegistrables;

        add_action('init', [$this, 'registerItems']);
    }

    public function registerItems()
    {
        foreach ($this->getItems() as $item) {
            if ($item instanceof Style) {
                $item->register();
            }
        }
    }

    public function unregisterItems()
    {
        foreach ($this->getItems() as $item) {
            if ($item instanceof Style) {
                $item->unregister();
            }
        }
    }

    public function getItems(): array
    {
        return array_merge($this->getCommonItems(), is_admin() ? $this->getAdminItems() : $this->getFrontItems());
    }

    private function getCommonItems(): array
    {
        $items = $this->commonRegistrables ? call_user_func($this->commonRegistrables) : [];

        return apply_filters('naran_axis_style_registrable/common', $items, $this->layout->getSlug());
    }

    private function getAdminItems(): array
    {
        $items = $this->adminRegistrables ? call_user_func($this->adminRegistrables) : [];

        return apply_filters('naran_axis_style_registrable/admin', $items, $this->layout->getSlug());
    }

    private function getFrontItems(): array
    {
        $items = $this->frontRegistrables ? call_user_func($this->frontRegistrables) : [];

        return apply_filters('naran_axis_style_registrable/front', $items, $this->layout->getSlug());
    }
}
