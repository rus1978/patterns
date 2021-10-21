<?php

/**
 * Паттерн Итератор
 *
 * Реализует интерфейсы \Iterator в классе итератора и \IteratorAggregate в классе коллекции.
 */

namespace Patterns;


class Iterator implements \Iterator
{
    private $index = 0;
    private array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function current()
    {
        return $this->items[$this->index];
    }

    public function next()
    {
        $this->index++;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return isset($this->items[$this->index]);
    }

    public function rewind()
    {
        $this->index = 0;
    }
}


class Collect implements \IteratorAggregate
{
    private array $items = [];

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getIterator()
    {
        return new Iterator($this->items);
    }

    public function addItem($value): void
    {
        $this->items[] = $value;
    }

    public function first()
    {
        return $this->items[0];
    }
}

/**
 * Client code
 */

$collect = new Collect(['orange', 'lemon']);
foreach ($collect as $key => $value) {
    echo "$key=>$value\n";
}

echo $collect->first();


