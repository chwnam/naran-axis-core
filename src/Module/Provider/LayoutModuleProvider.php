<?php


namespace Naran\Axis\Core\Module\Provider;

use Naran\Axis\Core\Layout\LayoutInterface;
use Naran\Axis\Core\Module\SubmoduleImpl;

class LayoutModuleProvider implements ModuleProviderInterface
{
    use SubmoduleImpl;

    protected LayoutInterface $layout;

    public function __construct(LayoutInterface $layout)
    {
        $this->layout = $layout;
    }

    public static function factory(LayoutInterface $layout, $arg): LayoutModuleProvider
    {
        if (is_string($arg) && class_exists($arg) && is_subclass_of($arg, static::class)) {
            /** @var LayoutModuleProvider $provider */
            $provider = new $arg($layout);
            $layout->setProvider($provider);
            $provider->initSubmodules($provider->provide());
        } elseif (is_array($arg)) {
            $provider = new static($layout);
            $layout->setProvider($provider);
            $provider->initSubmodules($arg);
        } elseif (is_object($arg) && is_subclass_of($arg, static::class)) {
            $provider = $arg;
            $layout->setProvider($provider);
            $provider->initSubmodules($provider->provide());
        } else {
            // Fallback. No submodules.
            $provider = new static($layout);
        }

        return $provider;
    }

    protected function provide(): array
    {
        return [];
    }
}
