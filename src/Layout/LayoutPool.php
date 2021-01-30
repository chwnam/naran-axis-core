<?php


namespace Naran\Axis\Core\Layout;

final class LayoutPool
{
    private static array $layouts = [];

    private function __construct()
    {
    }

    private function __clone()
    {
        wp_die('Cloning LayoutPool is not allowed.');
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function __sleep()
    {
        wp_die('Serializing LayoutPool is not allowed.');
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function __wakeup()
    {
        wp_die('Unserializing LayoutPool is not allowed.');
    }

    public static function add(LayoutInterface $layout, string $name = '')
    {
        if (empty($name)) {
            $name = $layout->getSlug() ? $layout->getSlug() : get_class($layout);
        }

        self::$layouts[$name] = $layout;
    }

    /**
     * @param string $layoutName
     * @return LayoutInterface
     * @throws LayoutNotFoundException
     */
    public static function get(string $layoutName): LayoutInterface
    {
        if (self::has($layoutName)) {
            return self::$layouts[$layoutName];
        }

        throw new LayoutNotFoundException("Layout {$layoutName} is not added.");
    }

    public static function has(string $layoutName): bool
    {
        return isset(self::$layouts[$layoutName]);
    }
}
