<?php

/**
 * Паттерн Абстрактная Фабрика
 */

namespace Patterns;

/**
 * Интерфейс Абстрактной Фабрики объявляет набор методов, которые возвращают
 * различные абстрактные продукты. Эти продукты называются семейством и связаны
 * темой или концепцией высокого уровня. Продукты одного семейства обычно могут
 * взаимодействовать между собой. Семейство продуктов может иметь несколько
 * вариаций, но продукты одной вариации несовместимы с продуктами другой.
 */
interface AbstractFactoryInterface
{
    public function buildCorpus(): CorpusProduct;

    public function buildEngine(): EngineProduct;

    public function buildTire(): TireProduct;
}

interface CorpusProduct
{
    public function create(): string;
}


/**
 * Конкретная Фабрика производит семейство продуктов одной вариации. Фабрика
 * гарантирует совместимость полученных продуктов. Обратите внимание, что
 * сигнатуры методов Конкретной Фабрики возвращают абстрактный продукт, в то
 * время как внутри метода создается экземпляр конкретного продукта.
 */
class MilitaryFactory implements AbstractFactoryInterface
{

    public function buildCorpus(): CorpusProduct
    {
        return new PanzerCorpus();
    }

    public function buildEngine(): EngineProduct
    {
        return new PanzerEngine();
    }

    public function buildTire(): TireProduct
    {
        return new PanzerTire();
    }
}

class ManufacturingFactory implements AbstractFactoryInterface
{

    public function buildCorpus(): CorpusProduct
    {
        return new KrazCorpus();
    }

    public function buildEngine(): EngineProduct
    {
        return new KrazEngine();
    }

    public function buildTire(): TireProduct
    {
        return new KrazTire();
    }
}

class PanzerCorpus implements CorpusProduct
{

    public function create(): string
    {
        return 'гусеницы + стальные колеса';
    }
}

class KrazCorpus implements CorpusProduct
{

    public function create(): string
    {
        return 'обычные резиновые покрышки';
    }
}

/**
 * Client code
 */
class ClientCode
{
    protected array $items;

    protected function newItem(AbstractFactoryInterface $factory)
    {
        return [
            $factory->buildCorpus(),
            $factory->buildEngine(),
            $factory->buildTire(),
        ];
    }

    public function execute(AbstractFactoryInterface $factory, int $maxItems): void
    {
        for ($i = 0; $i < $maxItems; $i++) {
            $this->items[] = $this->newItem($factory);
        }
    }

    public function getResults()
    {
        return $this->items;
    }
}

$isWar = true;
$factory = $isWar ? new MilitaryFactory() : new ManufacturingFactory();

$example = new ClientCode();
//изготовить 30 танков или 30 коазов
$example->execute($factory, 30);
var_dump($example->getResults());
