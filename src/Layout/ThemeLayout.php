<?php


namespace Naran\Axis\Core\Layout;

use Closure;

class ThemeLayout extends BaseLayout
{
    public function start()
    {
        add_action('after_setup_theme', Closure::fromCallable([$this, 'initLayout']));
        add_action('after_switch_theme', Closure::fromCallable([$this, 'activation']));
        add_action('switch_theme', Closure::fromCallable([$this, 'deactivation']));
    }

    public function initLayout()
    {
        $textdomain = $this->getTextdomain();

        if ( ! empty($textdomain)) {
            load_theme_textdomain($textdomain, get_stylesheet_directory() . '/languages');
        }

        $this->loadModules();
        $this->loadScheme();
    }
}
