<?php


namespace Naran\Axis\Core\Scheme\Registerers;

use Closure;
use Naran\Axis\Core\Layout\LayoutInterface;
use Naran\Axis\Core\Scheme\Registrables\Shortcode;

class ShortcodeRegisterer implements RegistererInterface
{
    private LayoutInterface $layout;

    /** @var ?Closure */
    private ?Closure $registrables;

    /** @var array<string, string|array|callable> */
    private array $postContentChecks = [];

    public function __construct(LayoutInterface $layout, ?Closure $registrables)
    {
        $this->layout       = $layout;
        $this->registrables = $registrables;

        add_action('init', [$this, 'registerItems']);
        add_action('wp', [$this, 'checkShortcodeInPostContent']);
    }

    public function registerItems()
    {
        foreach ($this->getItems() as $item) {
            if ($item instanceof Shortcode) {
                $item->register();
                if ($item->checkPostContent) {
                    $this->postContentChecks[] = [$item->shortcode, $item->checkPostContent];
                }
            }
        }
    }

    public function getItems(): array
    {
        if (is_callable($this->registrables)) {
            $items = call_user_func($this->registrables);
        } else {
            $items = [];
        }

        return apply_filters('naran_axis_shortcode_registrables', $items, $this->layout->getSlug());
    }

    public function unregisterItems()
    {
        foreach ($this->getItems() as $item) {
            if ($item instanceof Shortcode) {
                $item->unregister();
            }
        }
        $this->postContentChecks = [];
    }

    public function checkShortcodeInPostContent()
    {
        global $post;

        if (is_singular() && is_a($post, '\\WP_Post') && $this->postContentChecks) {
            foreach ($this->postContentChecks as [$shortcode, $postContentCheck]) {
                if (has_shortcode($post->post_content, $shortcode)) {
                    $callback = $postContentCheck; // TODO: parse callback
                    if (is_callable($callback)) {
                        call_user_func($callback, $shortcode);
                    }
                }
            }
        }
    }
}