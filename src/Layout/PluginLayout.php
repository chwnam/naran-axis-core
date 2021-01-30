<?php


namespace Naran\Axis\Core\Layout;

use Closure;

class PluginLayout extends BaseLayout
{
    private string $mainFile = '';

    public function start()
    {
        add_action('plugins_loaded', Closure::fromCallable([$this, 'initLayout']));
        register_activation_hook($this->getMainFile(), Closure::fromCallable([$this, 'activation']));
        register_deactivation_hook($this->getMainFile(), Closure::fromCallable([$this, 'deactivation']));
    }

    public function getMainFile(): string
    {
        return $this->mainFile;
    }

    public function setMainFile(string $mainFile)
    {
        $this->mainFile = $mainFile;
    }

    public function initLayout()
    {
        $textdomain = $this->getTextdomain();

        if ( ! empty($textdomain)) {
            load_plugin_textdomain(
                $textdomain,
                false,
                wp_basename(dirname($this->getMainFile())) . '/languages'
            );
        }

        $this->loadModules();
        $this->loadScheme();
    }
}
