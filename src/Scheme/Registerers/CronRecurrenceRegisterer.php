<?php


namespace Naran\Axis\Core\Scheme\Registerers;


use Closure;
use Naran\Axis\Core\Layout\LayoutInterface;
use Naran\Axis\Core\Scheme\Registrables\CronRecurrence;

class CronRecurrenceRegisterer implements RegistererInterface
{
    private LayoutInterface $layout;

    private ?Closure $registrables;

    public function __construct(LayoutInterface $layout, ?Closure $registrables)
    {
        $this->layout       = $layout;
        $this->registrables = $registrables;

        add_filter('cron_schedules', [$this, 'registerItems']);
    }

    public function registerItems(): array
    {
        $schedules = func_get_arg(0);

        foreach ($this->getItems() as $item) {
            if ($item instanceof CronRecurrence && ! isset($schedules[$item->identifier])) {
                $schedules[$item->identifier] = [
                    'interval' => $item->interval,
                    'display'  => $item->display,
                ];
            }
        }

        return $schedules;
    }

    public function unregisterItems()
    {
        // No need to unregister.
    }

    public function getItems()
    {
        if (is_callable($this->registrables)) {
            $items = call_user_func($this->registrables);
        } else {
            $items = [];
        }

        return apply_filters('naran_axis_cron_recurrence_registrables', $items, $this->layout->getSlug());
    }
}