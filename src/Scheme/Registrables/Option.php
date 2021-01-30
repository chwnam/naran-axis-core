<?php


namespace Naran\Axis\Core\Scheme\Registrables;

/**
 * Class Option
 *
 * @package Naran\Axis\Core\Scheme\Registrables
 *
 * @property-read string $type
 * @property-read string $description
 * @property-read ?callable $sanitize_callback
 * @property-read bool $show_in_rest
 * @property-read mixed $default
 * @property-read bool $autoload
 */
class Option implements RegistrableInterface
{
    private static array $options = [];

    private string $optionGroup;

    private string $optionName;

    private array $args;

    /**
     * WPDL_Option constructor.
     *
     * @param string $optionGroup
     * @param string $optionName
     * @param array $args
     */
    public function __construct(string $optionGroup, string $optionName, array $args = [])
    {
        $this->optionGroup = $optionGroup;
        $this->optionName  = $optionName;
        $this->args        = $args;
    }

    public static function factory(string $optionGroup, string $optionName): ?Option
    {
        global $wp_registered_setting;

        if (isset($wp_registered_setting[$optionName])) {
            $args = &$wp_registered_setting[$optionName];

            if ( ! isset (static::$options[$optionName])) {
                static::$options[$optionName] = new Option($optionGroup, $optionName, $args);
            }

            return static::$options[$optionName];
        }

        return null;
    }

    /**
     * @see register_setting()
     */
    public function register()
    {
        if ($this->optionGroup && $this->optionName) {
            if ($this->args['sanitize_callback']) {
                $this->args['sanitize_callback'] = wpdl_parse_callback($this->args['sanitize_callback']);
            }
            register_setting($this->optionGroup, $this->optionName, $this->args);
        }
    }

    public function unregister()
    {
        if ($this->optionGroup && $this->optionName) {
            unregister_setting($this->optionGroup, $this->optionName);
        }
    }

    /**
     * Get each register_setting() argument by name.
     *
     * @param string $name
     *
     * @return mixed
     *
     * @see register_setting()
     */
    public function __get(string $name)
    {
        switch ($name) {
            case 'type':
                return $this->args['type'] ?? '';

            case 'description':
                return $this->args['description'] ?? '';

            case 'sanitize_callback':
                return $this->args['sanitize_callback'] ?? null;

            case 'show_in_rest':
                return $this->args['show_in_rest'] ?? false;

            case 'default':
                return $this->args['default'] ?? false;

            case 'autoload':
                return $this->args['autoload'] ?? true;

            default:
                return $this->args[$name] ?? null;
        }
    }

    /**
     * Get option group.
     *
     * @return string
     */
    public function getOptionGroup(): string
    {
        return $this->optionGroup;
    }

    /**
     * Get option name.
     *
     * @return string
     */
    public function getOptionName(): string
    {
        return $this->optionName;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return get_option($this->optionName, $this->default);
    }

    public function updateFromRequest(): bool
    {
        if (isset($_REQUEST[$this->optionName]) && is_callable($this->sanitize_callback)) {
            return $this->update($_REQUEST[$this->optionName]);
        } else {
            return false;
        }
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function update($value): bool
    {
        return update_option($this->optionName, $value, $this->autoload);
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        return delete_option($this->optionName);
    }
}
