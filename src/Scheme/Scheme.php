<?php


namespace Naran\Axis\Core\Scheme;

use Closure;

class Scheme
{
    public const AJAX            = 'ajax';
    public const BLOCK           = 'block';
    public const CRON            = 'cron';
    public const CRON_RECURRENCE = 'cron/recurrence';
    public const META            = 'meta';
    public const OPTION          = 'option';
    public const POST_TYPE       = 'post_type';
    public const SCRIPT          = 'script';
    public const SCRIPT_COMMON   = 'script/common';
    public const SCRIPT_ADMIN    = 'script/admin';
    public const SCRIPT_FRONT    = 'script/front';
    public const SHORTCODE       = 'shortcode';
    public const STYLE           = 'style';
    public const STYLE_COMMON    = 'style/common';
    public const STYLE_ADMIN     = 'style/admin';
    public const STYLE_FRONT     = 'style/front';
    public const TAXONOMY        = 'taxonomy';

    /** @var array<string, Closure> */
    private array $scheme = [];

    /** @var array<string, bool> */
    private array $types = [
        self::AJAX            => false,
        self::BLOCK           => false,
        self::CRON            => false,
        self::CRON_RECURRENCE => false,
        self::META            => false,
        self::OPTION          => false,
        self::POST_TYPE       => false,
        self::SCRIPT          => false,
        self::SHORTCODE       => false,
        self::STYLE           => false,
        self::TAXONOMY        => false,
    ];

    public function setType(string $type, bool $setup): Scheme
    {
        if (isset($this->types[$type])) {
            $this->types[$type] = $setup;
        }

        return $this;
    }

    public function isTypeEnabled(string $type): bool
    {
        return $this->types[$type] ?? false;
    }

    public function get(string $type): ?Closure
    {
        return $this->scheme[$type] ?? null;
    }

    public function set(string $type, callable $items): Scheme
    {
        $this->scheme[$type] = Closure::fromCallable($items);

        return $this;
    }

    public function has(string $type): bool
    {
        return isset($this->scheme[$type]);
    }
}
