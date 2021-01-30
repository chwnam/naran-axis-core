<?php


namespace Naran\Axis\Core\Scheme\Registerers;

use Closure;
use Naran\Axis\Core\Layout\LayoutInterface;
use Naran\Axis\Core\Scheme\Registrables\Cron;

class CronRegisterer implements RegistererInterface
{
    private LayoutInterface $layout;

    /** @var ?Closure */
    private ?Closure $registrables;

    public function __construct(LayoutInterface $layout,?Closure  $registrables)
    {
        $this->layout       = $layout;
        $this->registrables = $registrables;

        add_action('naran_axis_activation', [$this, 'activationSetup']);
        add_action('naran_axis_deactivation', [$this, 'deactivationCleanup']);
    }

    public function registerItems()
    {
        foreach ($this->getItems() as $item) {
            if ($item instanceof Cron) {
                $item->register();
            }
        }
    }

    public function unregisterItems()
    {
        foreach ($this->getItems() as $item) {
            if ($item instanceof Cron) {
                $item->unregister();
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

        return apply_filters('naran_axis_cron_registrables', $items, $this->layout->getSlug());
    }

    public function activationSetup()
    {
        $this->registerItems();
    }

    public function deactivationCleanup()
    {
        $this->unregisterItems();
    }
}
