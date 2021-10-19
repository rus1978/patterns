<?php

namespace Patterns;

/**
 * Класс Фасада предоставляет простой интерфейс для сложной логики одной или
 * нескольких подсистем. Фасад делегирует запросы клиентов соответствующим
 * объектам внутри подсистемы. Фасад также отвечает за управление их жизненным
 * циклом. Все это защищает клиента от нежелательной сложности подсистемы.
 */
class ImageFacade
{
    private PhpThumb $thumb;
    private FileStorage $storage;

    const defaultImage = 'upload/default.jpg';

    /**
     * В зависимости от потребностей вашего приложения вы можете предоставить
     * Фасаду существующие объекты подсистемы или заставить Фасад создать их
     * самостоятельно.
     */
    public function __construct()
    {
        $this->thumb = new PhpThumb();
        $this->storage = new FileStorage('local');
    }

    private function setParams(array $params): void
    {
        foreach ($params as $key => $value) {
            $this->thumb->setParameter($key, $value);
        }
    }

    /**
     * Методы Фасада удобны для быстрого доступа к сложной функциональности
     * подсистем. Однако клиенты получают только часть возможностей подсистемы.
     */
    public function resize(string $filepath, array $options): string
    {
        $this->setParams($options);
        $this->thumb->setSourceFilename($filepath);
        if ($this->thumb->GenerateThumbnail()) {
            $file = $this->thumb->RenderToFile();
            $this->storage->put($file->getName(), $file->getContent());
            return $file->getContent();
        } else {
            return self::defaultImage;
        }
    }
}


/**
 * Client code
 */

$image = new ImageFacade();
$image->resize('upload/image.jpg', ['w' => 100, 'h' => 50]);