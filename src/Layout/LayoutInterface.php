<?php


namespace Naran\Axis\Core\Layout;

use Naran\Axis\Core\Module\Provider\LayoutModuleProvider;

interface LayoutInterface
{
    public function start();

    public function activation();

    public function deactivation();

    public function getSlug(): string;

    public function setSlug(string $slug);

    public function getSchemePath(): string;

    public function setSchemePath(string $path);

    public function getTextdomain(): string;

    public function setTextdomain(string $textdomain);

    public function getTitle(): string;

    public function setTitle(string $title);

    public function getVersion(): string;

    public function setVersion(string $version);

    public function getPriority(): int;

    public function setPriority(int $priority);

    public function getProvider(): LayoutModuleProvider;

    public function setProvider(LayoutModuleProvider $provider);

    public function get(string $name);

    public function set(string $name, $value);
}
