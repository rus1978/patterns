<?php

namespace Patterns;

/**
 * Стратегия
 *
 * Поведенческий паттерн проектирования, который определяет семейство схожих алгоритмов и помещает каждый из них в
 * собственный класс, после чего алгоритмы можно взаимозаменять прямо во время исполнения программы не вникая в их
 * реализацию.
 *
 *
 *
 * Project: 27ua
 * User: bonda
 * Date: 17.10.2021
 */
interface AlgorithmInterface
{
    public function handle(array $data): int;
}

class Calculate
{
    private AlgorithmInterface $algorithm;
    private array $data;

    public function __construct(AlgorithmInterface $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    public function setAlgorithm(AlgorithmInterface $algorithm): void
    {
        $this->algorithm = $algorithm;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getGrace(): int
    {
        $result = $this->algorithm->handle($this->data);
        return $result;
    }
}

class Algorithm1 implements AlgorithmInterface
{

    public function handle(array $data): int
    {
        return $data['var'] * 2;
    }
}


$algorithms = [
    'Algorithm1'
];

foreach ($products as $product) {
    foreach ($algorithms as $algorithmName) {
        $algorithm = new $algorithmName;
        $calculate = new Calculate($algorithm);
        $calculate->setData($product->getData());
        //$calculate->setAlgoritm($algorithm);
        $grace = $calculate->getGrace();
        $product->setGrace($grace);
    }
}
