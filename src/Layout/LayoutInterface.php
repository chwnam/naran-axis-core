<?php


namespace Naran\Axis\Core\Layout;


interface LayoutInterface
{
    public function start();

    public function activation();

    public function deactivation();

    public function getSlug(): string;

    public function getSchemePath(): string;

    public function getTextdomain(): string;

    public function getTitle(): string;

    public function getVersion(): string;

    public function getModuleProvider();

    public function setModuleProvider($provider);
}
