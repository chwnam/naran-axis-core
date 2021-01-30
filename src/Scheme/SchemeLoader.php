<?php

namespace Naran\Axis\Core\Scheme;

use Naran\Axis\Core\Layout\LayoutInterface;
use Naran\Axis\Core\Scheme\Registerers\AjaxRegisterer;
use Naran\Axis\Core\Scheme\Registerers\BlockRegisterer;
use Naran\Axis\Core\Scheme\Registerers\CronRegisterer;
use Naran\Axis\Core\Scheme\Registerers\MetaRegisterer;
use Naran\Axis\Core\Scheme\Registerers\OptionRegisterer;
use Naran\Axis\Core\Scheme\Registerers\PostTypeRegisterer;
use Naran\Axis\Core\Scheme\Registerers\ScriptRegisterer;
use Naran\Axis\Core\Scheme\Registerers\ShortcodeRegisterer;
use Naran\Axis\Core\Scheme\Registerers\StyleRegisterer;
use Naran\Axis\Core\Scheme\Registerers\TaxonomyRegisterer;


class SchemeLoader
{
    private LayoutInterface $layout;
    private Scheme $scheme;

    public function __construct(LayoutInterface $layout)
    {
        $this->layout = $layout;
        $this->scheme = new Scheme();

        $schemePath = $layout->getSchemePath();
        if (file_exists($schemePath) && is_readable($schemePath)) {
            $scheme = &$this->scheme;
            $__sp__ = &$schemePath;
            (function () use ($__sp__, $scheme) {
                /** @noinspection PhpIncludeInspection */
                include_once $__sp__;
            })();
            $this->registerScheme();
        }
    }

    private function registerScheme()
    {
        $s = &$this->scheme;

        if ($s->isTypeEnabled(Scheme::AJAX)) {
            new AjaxRegisterer($this->layout, $s->get(Scheme::AJAX));
        }

        if ($s->isTypeEnabled(Scheme::BLOCK)) {
            new BlockRegisterer($this->layout, $s->get(Scheme::BLOCK));
        }

        if ($s->isTypeEnabled(Scheme::CRON)) {
            new CronRegisterer($this->layout, $s->get(Scheme::CRON));
        }

        if ($s->isTypeEnabled(Scheme::META)) {
            new MetaRegisterer($this->layout, $s->get(Scheme::META));
        }

        if ($s->isTypeEnabled(Scheme::OPTION)) {
            new OptionRegisterer($this->layout, $s->get(Scheme::OPTION));
        }

        if ($s->isTypeEnabled(Scheme::POST_TYPE)) {
            new PostTypeRegisterer($this->layout, $s->get(Scheme::POST_TYPE));
        }

        if ($s->isTypeEnabled(Scheme::SCRIPT)) {
            new ScriptRegisterer(
                $this->layout,
                $s->get(Scheme::SCRIPT . '/common'),
                $s->get(Scheme::SCRIPT . '/admin'),
                $s->get(Scheme::SCRIPT . '/front')
            );
        }

        if ($s->isTypeEnabled(Scheme::SHORTCODE)) {
            new ShortcodeRegisterer($this->layout, $s->get(Scheme::SHORTCODE));
        }

        if ($s->isTypeEnabled(Scheme::STYLE)) {
            new StyleRegisterer(
                $this->layout,
                $s->get(Scheme::STYLE . '/common'),
                $s->get(Scheme::STYLE . '/admin'),
                $s->get(Scheme::STYLE . '/front')
            );
        }

        if ($s->isTypeEnabled(Scheme::TAXONOMY)) {
            new TaxonomyRegisterer($this->layout, $s->get(Scheme::TAXONOMY));
        }
    }
}
