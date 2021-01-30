<?php


namespace Naran\Axis\Core\Scheme\Registrables;


class Taxonomy implements RegistrableInterface
{
    public string $taxonomy;

    public array $objectType;

    public array $args;

    public function __construct(string $taxonomy, $objectType, array $args = [])
    {
        $this->taxonomy   = $taxonomy;
        $this->objectType = (array)$objectType;
        $this->args       = $args;
    }

    public function register()
    {
        if ( ! taxonomy_exists($this->taxonomy)) {
            register_taxonomy($this->taxonomy, $this->objectType, $this->args);
        }
    }

    public function unregister()
    {
        if (taxonomy_exists($this->taxonomy)) {
            unregister_taxonomy($this->taxonomy);
        }
    }
}
