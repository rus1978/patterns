<?php

/**
 * Паттерн Стратегия
 *
 * Поведенческий паттерн проектирования, который определяет семейство схожих алгоритмов и помещает каждый из них в
 * собственный класс, после чего алгоритмы можно взаимозаменять прямо во время исполнения программы.
 */

namespace Patterns;

/**
 * Context
 */
class GraceCalculate
{
    private AlgorithmInterface $algorithm;
    private array $data;

//    public function __construct(AlgorithmInterface $algorithm)
//    {
//        $this->algorithm = $algorithm;
//    }

    /**
     * set Strategy
     *
     * @param AlgorithmInterface $algorithm
     */
    public function setAlgorithm(AlgorithmInterface $algorithm): void
    {
        $this->algorithm = $algorithm;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * Get result
     *
     * @return int
     */
    public function getResult(): int
    {
        $result = $this->algorithm->handle($this->data);
        return $result;
    }
}

/**
 * Strategy interface
 */
interface AlgorithmInterface
{
    public function handle(array $data): int;
}

/**
 * Concrete strategy
 */
class Algorithm1 implements AlgorithmInterface
{
    public function handle(array $data): int
    {
        return $data['var'] * 2;
    }
}

/**
 * Client code
 */
$algorithms = [
    'Algorithm1',
    'Algorithm2'
];

$calculate = new GraceCalculate();

foreach ($products as $product) {
    foreach ($algorithms as $algorithmName) {
        $algorithm = new $algorithmName;

        $calculate->setAlgorithm($algorithm);
        $calculate->setData($product->getData());

        $grace = $calculate->getResult();
        $product->setGrace($grace);
    }
}
