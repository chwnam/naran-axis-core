<?php


namespace Naran\Axis\Core\Scheme\Registrables;


class Shortcode implements RegistrableInterface
{
    public string $shortcode;

    /** @var string|array|callable */
    public $callback;

    /** @var false|string|array|callable */
    public $checkPostContent;

    public function __construct(string $shorcode, $callback, $checkPostContent = false)
    {
        $this->shortcode        = $shorcode;
        $this->callback         = $callback;
        $this->checkPostContent = $checkPostContent;
    }

    public function register()
    {
        if ( ! shortcode_exists($this->shortcode)) {
            add_shortcode($this->shortcode, $this->callback);
        }
    }

    public function unregister()
    {
        if (shortcode_exists($this->shortcode)) {
            remove_shortcode($this->shortcode);
        }
    }
}
