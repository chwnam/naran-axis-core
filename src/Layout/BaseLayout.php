<?php


namespace Naran\Axis\Core\Layout;

use Naran\Axis\Core\Module\Provider\LayoutModuleProvider;
use Naran\Axis\Core\Scheme\SchemeLoader;


abstract class BaseLayout implements LayoutInterface
{
    private LayoutModuleProvider $provider;

    private string $schemePath = '';

    private string $slug = '';

    private string $textdomain = '';

    private string $title = '';

    private string $version = '';

    private int $priority;

    private array $storage = [];

    public function getTextdomain(): string
    {
        return $this->textdomain;
    }

    public function setTextdomain(string $textdomain)
    {
        $this->textdomain = $textdomain;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version)
    {
        $this->version = $version;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority)
    {
        $this->priority = $priority;
    }

    public function getSchemePath(): string
    {
        return $this->schemePath;
    }

    public function setSchemePath(string $path)
    {
        $this->schemePath = $path;
    }

    public function activation()
    {
        $this->loadModules();
        $this->loadScheme();
        do_action('naran_axis_activation', $this->getSlug());
    }

    protected function loadModules()
    {
        static $moduleLoaded = false;

        if ( ! $moduleLoaded) {
            $moduleLoaded = true;

            LayoutModuleProvider::factory($this, $this->get('module_provider'));
            $this->set('module_provider', null);

            do_action('naran_axis_modules_loaded', $this->getSlug());
        }
    }

    public function get(string $name)
    {
        return $this->storage[$name] ?? null;
    }

    public function set(string $name, $value)
    {
        if (is_null($value)) {
            unset($this->storage[$name]);
        } else {
            $this->storage[$name] = $value;
        }
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    protected function loadScheme()
    {
        static $schemeLoaded = false;

        if ( ! $schemeLoaded) {
            $schemeLoaded = true;

            new SchemeLoader($this);

            do_action('naran_axis_scheme_loaded', $this->getSlug());
        }
    }

    public function getProvider(): LayoutModuleProvider
    {
        return $this->provider;
    }

    public function setProvider(LayoutModuleProvider $provider)
    {
        $this->provider = $provider;
    }

    public function deactivation()
    {
        $this->loadModules();
        $this->loadScheme();
        do_action('naran_axis_deactivation', $this->getSlug());
    }
}
