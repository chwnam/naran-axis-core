<?php


namespace Naran\Axis\Core\Module;

use BadMethodCallException;

/**
 * Trait SubmoduleImpl
 *
 * @package Naran\Axis\Core\Module
 */
trait SubmoduleImpl
{
    protected array $submodules = [];

    public function __get(string $name)
    {
        if (is_null($name)) {
            return null;
        }

        $module = $this->submodules[$name] ?? null;

        if ( ! ($module instanceof ModuleInterface) && is_callable($module)) {
            $module = $this->submodules[$name] = call_user_func($module, $this->layout);
            if ($module instanceof ModuleInterface) {
                $module->initialize();
                $module->ready();
            }
        }

        return $module;
    }

    public function __set(string $name, $value)
    {
        throw new BadMethodCallException(__('Submodules do not allow module assignment from outside.', 'axis'));
    }

    public function __isset(string $name): bool
    {
        return isset($this->submodules[$name]);
    }

    public function __unset(string $name)
    {
        throw new BadMethodCallException(__('Submodules do not allow module removal.', 'axis'));
    }

    protected function initSubmodules(array $submodules)
    {
        $this->submodules = $submodules;

        foreach ($this->submodules as $key => &$submodule) {
            if (is_subclass_of($submodule, ModuleInterface::class)) {
                if (is_string($submodule)) {
                    $submodule = new $submodule($this->layout);
                }
                $submodule->initialize();
            }
        }

        unset($submodule);

        foreach ($this->submodules as $submodule) {
            if (is_subclass_of($submodule, ModuleInterface::class)) {
                $submodule->ready();
            }
        }
    }
}
