<?php


namespace Naran\Axis\Core\Scheme\Registrables;

use WP_Comment;
use WP_Error;
use WP_Post;
use WP_Term;
use WP_User;

/**
 * Class Meta
 *
 * @package Naran\Axis\Core\Scheme\Registrables
 *
 * @property-read string    $object_subtype
 * @property-read string    $type
 * @property-read string    $description
 * @property-read mixed     $default
 * @property-read bool      $single
 * @property-read ?callable $sanitize_callback
 * @property-read ?callable $auth_callback
 * @property-read bool      $show_in_rest
 */
class Meta implements RegistrableInterface
{
    private static array $meta = [];

    private string $objectType = '';

    private string $metaKey = '';

    private array $args;

    /**
     * Constructor method
     *
     * @param string $metaKey    meta key name.
     * @param string $objectType meta field type.
     * @param array  $args       meta field args.
     *
     * @see register_meta()
     */
    public function __construct(string $objectType, string $metaKey, array $args)
    {
        $this->objectType = $objectType;
        $this->metaKey    = $metaKey;
        $this->args       = $args;
    }

    /**
     * @param string $objectType
     * @param string $metaKey
     * @param string $objectSubtype
     *
     * @return ?Meta
     */
    public static function factory(string $objectType, string $metaKey, string $objectSubtype): ?Meta
    {
        global $wp_meta_keys;

        if (isset($wp_meta_keys[$objectType][$objectSubtype][$metaKey])) {
            $args = &$wp_meta_keys[$objectType][$objectSubtype][$metaKey];

            if ( ! isset(static::$meta[$objectType][$objectSubtype][$metaKey])) {
                static::$meta[$objectType][$objectSubtype][$metaKey] = new Meta($metaKey, $objectType, $args);
            }

            return static::$meta[$objectType][$objectSubtype][$metaKey];
        }

        return null;
    }

    public function register()
    {
        if (
            $this->objectType &&
            $this->object_subtype &&
            ! registered_meta_key_exists($this->objectType, $this->metaKey, $this->object_subtype)
        ) {
            if (isset($this->args['sanitize_callback'])) {
                $this->args['sanitize_callback'] = wpdl_parse_callback($this->args['sanitize_callback']);
            }
            if (isset($this->args['auth_callback'])) {
                $this->args['auth_callback'] = wpdl_parse_callback($this->args['auth_callback']);
            }
            register_meta($this->objectType, $this->metaKey, $this->args);
        }
    }

    public function unregister()
    {
        if (
            $this->objectType &&
            $this->object_subtype &&
            registered_meta_key_exists($this->objectType, $this->metaKey, $this->object_subtype)
        ) {
            unregister_meta_key($this->objectType, $this->metaKey, $this->object_subtype);
        }
    }

    /**
     * Get each register_meta() argument.
     *
     * @param string $prop
     *
     * @return mixed|string|null
     *
     * @see register_meta()
     */
    public function __get(string $prop)
    {
        switch ($prop) {
            case 'object_subtype':
                return $this->args['object_subtype'] ?? '';

            case 'type':
                return $this->args['type'] ?? '';

            case 'description':
                return $this->args['description'] ?? '';

            case 'default':
                return $this->args['default'] ?? '';

            case 'sanitize_callback':
                return $this->args['sanitize_callback'] ?? '';

            case 'auth_callback':
                return $this->args['auth_callback'] ?? '';

            case 'show_in_rest':
                return $this->args['show_in_rest'] ?? '';

            default:
                return $this->args[$prop] ?? null;
        }
    }

    /**
     * Get object type.
     *
     * @return string
     */
    public function getObjectType(): string
    {
        return $this->objectType;
    }

    /**
     * Get meta field value.
     *
     * @param mixed $objectId
     *
     * @return mixed
     */
    public function get_value($objectId)
    {
        switch ($this->objectType) {
            case 'comment':
                return get_comment_meta($this->_getId($objectId), $this->metaKey, $this->single ?? false);

            case 'post':
                return get_post_meta($this->_getId($objectId), $this->metaKey, $this->single ?? false);

            case 'term':
                return get_term_meta($this->_getId($objectId), $this->metaKey, $this->single ?? false);

            case 'user':
                return get_user_meta($this->_getId($objectId), $this->metaKey, $this->single ?? false);

            default:
                return get_metadata(
                    $this->objectType,
                    $this->_getId($objectId),
                    $this->metaKey,
                    $this->args['single'] ?? false
                );
        }
    }

    /**
     * Get safe object ID.
     *
     * @param mixed $objectId
     *
     * @return false|int
     */
    protected function _getId($objectId)
    {
        if (is_int($objectId) || is_numeric($objectId)) {
            return intval($objectId);
        } elseif ($objectId instanceof WP_Post || $objectId instanceof WP_User) {
            return $objectId->ID;
        } elseif ($objectId instanceof WP_Term) {
            return $objectId->term_id;
        } elseif ($objectId instanceof WP_Comment) {
            return $objectId->comment_post_ID;
        } elseif (is_array($objectId) && isset($objectId['ID'])) {
            return $objectId['ID'];
        } elseif (is_object($objectId) && isset($objectId->ID)) {
            return $objectId->ID;
        } elseif (class_exists('\WC_Product') && is_a($objectId, '\WC_Product')) {
            return $objectId->get_id();
        }

        return false;
    }

    /**
     * Delete meta value of an object.
     *
     * @param mixed  $objectId
     * @param string $metaValue
     *
     * @return bool
     */
    public function delete($objectId, $metaValue = ''): bool
    {
        switch ($this->objectType) {
            case 'comment':
                return delete_comment_meta($this->_getId($objectId), $this->metaKey, $metaValue);
            case 'post':
                return delete_post_meta($this->_getId($objectId), $this->metaKey, $metaValue);
            case 'taxonomy':
                return delete_term_meta($this->_getId($objectId), $this->metaKey, $metaValue);
            case 'user':
                return delete_user_meta($this->_getId($objectId), $this->metaKey, $metaValue);
            default:
                return delete_metadata($this->objectType, $this->_getId($objectId), $this->metaKey, $metaValue);
        }
    }

    /**
     * Update meta field with valeu form request.
     *
     * @param $objectId
     *
     * @return bool|int|WP_Error
     */
    public function updateFromRequest($objectId)
    {
        if (isset($_REQUEST[$this->getKey()]) && is_callable($this->sanitize_callback)) {
            return $this->update($objectId, $_REQUEST[$this->getKey()]);
        } else {
            return false;
        }
    }

    /**
     * Get meta key.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->metaKey;
    }

    /**
     * Update meta field.
     *
     * @param mixed $objectId
     * @param mixed $metaValue
     * @param mixed $prevValue
     *
     * @return bool|int|WP_Error
     */
    public function update($objectId, $metaValue, $prevValue = '')
    {
        switch ($this->objectType) {
            case 'comment':
                return update_comment_meta($this->_getId($objectId), $this->metaKey, $metaValue, $prevValue);

            case 'post':
                return update_post_meta($this->_getId($objectId), $this->metaKey, $metaValue, $prevValue);

            case 'term':
                return update_term_meta($this->_getId($objectId), $this->metaKey, $metaValue, $prevValue);

            case 'user':
                return update_user_meta($this->_getId($objectId), $this->metaKey, $metaValue, $prevValue);

            default:
                return update_metadata(
                    $this->objectType,
                    $this->_getId($objectId),
                    $this->metaKey,
                    $metaValue,
                    $prevValue
                );
        }
    }
}