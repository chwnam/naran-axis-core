<?php


namespace Naran\Axis\Core\Layout;

use Naran\Axis\Core\Scheme\SchemeLoader;


abstract class BaseLayout implements LayoutInterface
{
    /** @var mixed */
    private $moduleProvider = null;

    private string $schemePath = '';

    private string $slug = '';

    private string $textdomain = '';

    private string $title = '';

    private string $version = '';

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

    public function getSchemePath(): string
    {
        return $this->schemePath;
    }

    public function setSchemePath(string $path)
    {
        $this->schemePath = $path;
    }

    public function getModuleProvider()
    {
        return $this->moduleProvider;
    }

    public function setModuleProvider($provider)
    {
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

//            new ModuleLoader($this);

            do_action('naran_axis_modules_loaded', $this->getSlug());
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

    public function deactivation()
    {
        $this->loadModules();
        $this->loadScheme();
        do_action('naran_axis_deactivation', $this->getSlug());
    }
}
