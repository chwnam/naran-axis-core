<?php


namespace Naran\Axis\Core\Scheme\Registrables;


/**
 * Class CronRecurrence
 *
 * Add cron schedule.
 * Built-in scheduels: 'hourly', 'twicedaily', 'and 'daily'.
 *
 * @package Naran\Axis\Core\Scheme\Registrables
 *
 * @link    https://developer.wordpress.org/reference/hooks/cron_schedules/
 */
class CronRecurrence implements RegistrableInterface
{
    /**
     * Schedule identifier slug.
     *
     * @var string
     */
    public string $identifier;

    /** Schedule interval
     *
     * @var int
     */
    public int $interval;

    /**
     * Human-friendly string.
     *
     * @var string
     */
    public string $display;

    public function __construct(string $identifier, int $interval, string $display)
    {
        $this->identifier = $identifier;
        $this->interval   = absint($interval);
        $this->display    = $display;
    }

    public function register()
    {
        // Cron recurrence is registered via apply_filters().
    }

    public function unregister()
    {
        // Cron recurrence does not need to unregister.
    }
}
