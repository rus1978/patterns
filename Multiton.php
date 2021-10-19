<?php

namespace Patterns;

/**
 * Мультитон (пул одиночек)
 *
 * Гарантирует, что класс имеет поименованные экземпляры объекта и обеспечивает глобальную точку доступа к ним.
 */
trait Multiton
{
    protected static array $instance = [];

    /**
     * Create a new instance
     */
    final public static function getInstance(string|int $key = 'default'): self
    {
        return isset(static::$instance[$key])
            ? static::$instance[$key]
            : static::$instance[$key] = new static;
    }

    /**
     * Constructor.
     */
    final protected function __construct()
    {
        $this->init();
    }

    /**
     * Initialize the multiton free from constructor parameters.
     */
    protected function init(): void
    {
    }

    public function __clone()
    {
        trigger_error('Cloning ' . __CLASS__ . ' is not allowed.', E_USER_ERROR);
    }

    public function __wakeup()
    {
        trigger_error('Unserializing ' . __CLASS__ . ' is not allowed.', E_USER_ERROR);
    }
}

class Config
{
    use Multiton;

    public function get(int|string $key)
    {
        return $this->config[$key] ?? null;
    }
}

/**
 * Client code
 */

$config = Config::getInstance('catalog');
$config->get('pagination');
