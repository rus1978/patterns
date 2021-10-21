<?php

/**
 * Строитель (реальный пример сайта epicentrk.ua)
 */

namespace Patterns;

interface builderInterface
{
    public function setFilter(array $filter): self;

    public function setNavParams(array $params): self;

    public function setStoreID(int $storeID): self;

    public function setForce(bool $flag): self;

    public function setRank(array $rank): self;

    public function setMaxItems(int $max): self;
}

/**
 * Конкректный строитель листинга
 */
class ListingBuilder implements builderInterface
{
    protected Listing $listing;

    public function __construct()
    {
        $this->listing = new Listing();
    }

    public function setFilter(array $filter): builderInterface
    {
        $this->listing->arFilter = $filter;
        return $this;
    }

    public function setNavParams(array $params): builderInterface
    {
        $this->listing->arNavParams = $params;
        return $this;
    }

    public function setStoreID(int $storeID): builderInterface
    {
        $this->listing->store = $storeID;
        return $this;
    }

    public function setForce(bool $flag): builderInterface
    {
        $this->listing->force = $flag;
        return $this;
    }

    public function setRank(array $rank): builderInterface
    {
        $this->listing->rank = $rank;
        return $this;
    }

    public function setMaxItems(int $max): builderInterface
    {
        $this->listing->maxItems = $max;
        return $this;
    }

    /**
     * Конкретные Строители должны предоставить свои собственные методы
     * получения результатов. Это связано с тем, что различные типы строителей
     * могут создавать совершенно разные продукты с разными интерфейсами.
     * Поэтому такие методы не могут быть объявлены в базовом интерфейсе
     * Строителя (по крайней мере, в статически типизированном языке
     * программирования). Обратите внимание, что PHP является динамически
     * типизированным языком, и этот метод может быть в базовом интерфейсе.
     * Однако мы не будем объявлять его здесь для ясности.
     *
     * Как правило, после возвращения конечного результата клиенту, экземпляр
     * строителя должен быть готов к началу производства следующего продукта.
     * Поэтому обычной практикой является вызов метода сброса в конце тела
     * метода getProduct. Однако такое поведение не является обязательным, вы
     * можете заставить своих строителей ждать явного запроса на сброс из кода
     * клиента, прежде чем избавиться от предыдущего результата.
     */
    public function getResult(): array
    {
        return $this->listing->fetch();
    }
}


/**
 * Конкректный Строитель акционных листингов
 */
class ActionBuilder extends ListingBuilder
{
    public function setFilter(array $filter): builderInterface
    {
        parent::setFilter($filter);
        $this->listing->arrFilter = $filter['arrFilter'];
        return $this;
    }
}


/**
 * Product class
 * Вокруг этого класса и просходит вся эта заворуха
 *
 * Имеет смысл использовать паттерн Строитель только тогда, когда ваши продукты (Listing)
 * достаточно сложны и требуют обширной конфигурации.
 *
 * В отличие от других порождающих паттернов, различные конкретные строители
 * могут производить несвязанные продукты. Другими словами, результаты различных
 * строителей могут не всегда следовать одному и тому же интерфейсу.
 */
class Listing
{
    //входящие параметры
    public $arFilter = [];
    public $arNavParams = false;
    public $store;
    public $force = false;
    public $rank = []; //Сортировка и фильтрация согласно переданному массиву данных
    public $maxItems = null; //(int) костыль для акций, урезает максимальное кол.товаров

    public function fetch(): array
    {
        return DB::query()->fetchAll();
    }
}

/**
 * Класс опционален
 */
class Director
{
    private builderInterface $builder;

    public function setBuilder(builderInterface $builder): void
    {
        $this->builder = $builder;
    }

    /**
     * Директор может строить несколько вариаций продукта, используя одинаковые
     * шаги построения.
     *
     * @return array
     */
    public function buildFull(): array
    {
        $this->builder
            ->setFilter(['filter1' => 123])
            ->setMaxItems(20)
            ->setNavParams(['paginate' => 20])
            ->setStoreID(2)
            ->setRank(['item1', 'item2'])
            ->setForce(true);

        return $this->builder->getResult();
    }

    public function build(): array
    {
        $this->builder
            ->setFilter(['filter1' => 123])
            ->setMaxItems(20)
            ->setForce(true);

        return $this->builder->getResult();
    }
}


/*
антипаттерн - вот чтобы избежать вот этого, использутся паттерн Builder ниже

$instance = new Listing;
$arFilter['LABEL']=$arrFilter['LABEL'];
$instance->arFilter = $arFilter;
$instance->arrFilter = $arrFilter;
$instance->arNavParams = $arNavParams;
$instance->store = $store;
$instance->force = $force;
$instance->rank = $rank;

if(intval($arrFilter["LIMIT"])>0){//костыль для акций, уменьшает максимальное кол. товаров до заданного
    $instance->maxItems = intval($arrFilter["LIMIT"]);
}

$result= $instance->fetch();*/


//Вариант без директора
$action = new ActionBuilder();
$action->setFilter(['filter1' => 123])
    ->setMaxItems(20)
    ->setNavParams(['paginate' => 20])
    ->setStoreID(2)
    ->setRank(['item1', 'item2'])
    ->setForce(true);
$result = $action->getResult();


//Вариант с директором
$director = new Director();
$director->setBuilder(new ListingBuilder());
$result = $director->buildFull();