<?php


namespace Naran\Axis\Core\Scheme\Registrables;


class Cron implements RegistrableInterface
{
    /**
     * Hook name
     *
     * @var string
     */
    public string $hook = '';

    /**
     * Timestamp to run
     *
     * @var int
     */
    public int $timestamp;

    /**
     * Interval.
     *
     * hourly, twicedaily, daily, weekly
     *
     * @var string
     */
    public string $recurrence = '';

    /**
     * Argument
     *
     * @var array
     */
    public array $args = [];

    /**
     * Is this a single event
     *
     * @var bool
     */
    public bool $singleEvent = false;

    public function __construct(
        string $hook,
        ?int $timestamp,
        string $recurrence,
        array $args = [],
        bool $singleEvent = false
    ) {
        $this->hook        = $hook;
        $this->timestamp   = $timestamp ? absint($timestamp) : time();
        $this->recurrence  = $recurrence;
        $this->args        = $args;
        $this->singleEvent = $singleEvent;
    }

    public function register()
    {
        if ($this->hook) {
            if ( ! wp_next_scheduled($this->hook)) {
                if ($this->singleEvent) {
                    wp_schedule_single_event($this->timestamp, $this->hook, $this->args);
                } else {
                    wp_schedule_event($this->timestamp, $this->recurrence, $this->hook, $this->args);
                }
            }
        }
    }

    public function unregister()
    {
        if ($this->hook) {
            wp_unschedule_hook($this->hook);
        }
    }
}
