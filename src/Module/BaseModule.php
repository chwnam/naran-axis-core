<?php


namespace Naran\Axis\Core\Module;


use Naran\Axis\Core\Layout\LayoutInterface;

class BaseModule implements ModuleInterface
{
    protected LayoutInterface $layout;

    public function __construct(LayoutInterface $layout)
    {
        $this->layout = $layout;
    }

    public function initialize()
    {
    }

    public function ready()
    {
    }

    protected function action(string $tag, $callback, ?int $priority = null, int $arguments = 1): self
    {
        add_action(
            $tag,
            $callback,
            is_null($priority) ? $this->layout->getPriority() : $priority,
            $arguments
        );

        return $this;
    }

    protected function filter(string $tag, $callback, ?int $priority, int $arguments = 1): self
    {
        add_filter(
            $tag,
            $callback,
            is_null($priority) ? $this->layout->getPriority() : $priority,
            $arguments
        );

        return $this;
    }
}
