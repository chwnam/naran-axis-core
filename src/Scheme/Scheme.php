<?php


namespace Naran\Axis\Core\Scheme;

use Closure;

class Scheme
{
    public const AJAX      = 'ajax';
    public const BLOCK     = 'block';
    public const CRON      = 'cron';
    public const META      = 'meta';
    public const OPTION    = 'option';
    public const POST_TYPE = 'post_type';
    public const SCRIPT    = 'script';
    public const SHORTCODE = 'shortcode';
    public const STYLE     = 'style';
    public const TAXONOMY  = 'taxonomy';

    /** @var array<string, Closure> */
    private array $scheme = [];

    /** @var array<string, bool> */
    private array $types = [
        self::AJAX      => false,
        self::BLOCK     => false,
        self::CRON      => false,
        self::META      => false,
        self::OPTION    => false,
        self::POST_TYPE => false,
        self::SCRIPT    => false,
        self::SHORTCODE => false,
        self::STYLE     => false,
        self::TAXONOMY  => false,
    ];

    public function setType(string $type, bool $setup)
    {
        if (isset($this->types[$type])) {
            $this->types[$type] = $setup;
        }
    }

    public function isTypeEnabled(string $type): bool
    {
        return $this->types[$type] ?? false;
    }

    public function get(string $type): ?Closure
    {
        return $this->scheme[$type] ?? null;
    }

    public function set(string $type, callable $items)
    {
        $this->scheme[$type] = Closure::fromCallable($items);
    }

    public function has(string $type): bool
    {
        return isset($this->scheme[$type]);
    }
}
