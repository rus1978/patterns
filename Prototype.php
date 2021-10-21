<?php

/**
 * Паттерн Прототип предоставляет удобный способ репликации существующих
 * объектов вместо их восстановления и копирования всех полей напрямую.
 * Прямое копирование не только связывает вас с классами клонируемых объектов,
 * но и не позволяет копировать содержимое приватных полей.
 * Паттерн Прототип позволяет выполнять клонирование в контексте клонированного класса,
 * где доступ к приватным полям класса не ограничен.
 *
 * PHP имеет встроенную поддержку клонирования
 */

namespace Patterns;

class Prototype
{
    private $private;
    protected $protected;

    public $public;
    public \DateTime $dateTime;

    /**
     * Обратите внимание, что конструктор не будет выполнен во время
     * клонирования. Если у вас сложная логика внутри конструктора, вам может
     * потребоваться выполнить ее также и в методе clone.
     */
    public function __construct($private, $protected)
    {
        $this->private = $private;
        $this->protected = $protected;
        $this->public = 'origin';
        $this->dateTime = new \DateTime();
    }

    public function __clone()
    {
        $this->private = null;
        $this->protected = null;
        $this->public = null;

        // Клонирование объекта, который имеет вложенный объект с обратной
        // ссылкой, требует специального подхода. После завершения клонирования
        // вложенный объект должен указывать на клонированный объект, а не на
        // исходный объект.
        $this->dateTime = clone $this->dateTime;
    }

    public function debug(): void
    {
        print_r([
            'private' => $this->private,
            'protected' => $this->protected,
            'public' => $this->public,
            'dateTime' => $this->dateTime->format('c')
        ]);
    }
}

$origin = new Prototype(111, 222);
$origin->public = 'origin change';

$clone = clone $origin;

$origin->debug();
$clone->debug();