<?php


namespace Naran\Axis\Core\Scheme\Registrables;


/**
 * Class Block
 *
 * @package Naran\Axis\Core\Scheme\Registrables
 *
 * @property-read string                      $editor_script
 * @property-read callable|array|string|false $render_callback
 */
class Block implements RegistrableInterface
{
    public string $blockType;

    public array $args;

    public function __construct(string $blockType, array $args = [])
    {
        $this->blockType = $blockType;
        $this->args      = $args;
    }

    public function __get($name)
    {
        return $this->args[$name] ?? null;
    }

    public function register()
    {
        if (function_exists('register_block_type')) {
            register_block_type($this->blockType, $this->args);
        }
    }

    public function unregister()
    {
        if (function_exists('unregister_block_type')) {
            unregister_block_type($this->blockType);
        }
    }
}
