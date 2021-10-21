<?php

/**
 * Паттерн Наблюдатель
 */

namespace Patterns;

use SplObserver;
use SplSubject;


/**
 * Это может быть программа просчета или определенное событие например: onBeforeRenderContent, onInitSystem
 * на которое можно подписаться
 */
class Subject implements \SplSubject
{
    private $observers;

    private int $state = 0;

    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }

    public function attach(SplObserver $observer)
    {
        $this->observers->attach($observer);
    }

    public function detach(SplObserver $observer)
    {
        $this->observers->detach($observer);
    }

    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function getState(): int
    {
        return $this->state;
    }

    /**
     * Например запускается просчет цен и остатков по данным поступившим из 1с,
     * после чего нужно сообщить всем подписчикам о данном событии
     */
    public function execute()
    {
        //business logic
        $this->state = rand(100);

        //trigger event
        $this->notify();
    }
}

class Observer1 implements \SplObserver
{

    public function update(Subject|SplSubject $subject)
    {
        if ($subject->getState() == 25) {
            $this->action1();
        }else{
            $this->action2();
        }
    }

    protected function action1()
    {
    }
}

class Observer2 extends Observer1
{
}


/**
 * Client code
 */

$observer1 = new Observer1();
$observer2 = new Observer2();

$subject = new Subject();
$subject->attach($observer1);
$subject->attach($observer2);

$subject->execute();
