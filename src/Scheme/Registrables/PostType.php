<?php


namespace Naran\Axis\Core\Scheme\Registrables;


class PostType implements RegistrableInterface
{
    public string $postType;

    public array $args;

    public function __construct(string $postType, array $args = [])
    {
        $this->postType = $postType;
        $this->args     = $args;
    }

    public function register()
    {
        if ( ! post_type_exists($this->postType)) {
            register_post_type($this->postType, $this->args);
        }
    }

    public function unregister()
    {
        if (post_type_exists($this->postType)) {
            unregister_post_type($this->postType);
        }
    }
}