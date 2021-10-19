<?php

/**
 * Паттерн Фабричный метод
 *
 * Создатель объявляет фабричный метод, который может быть использован вместо
 * прямых вызовов конструктора продуктов
 */

namespace Patterns;

/**
 * Класс Создатель объявляет фабричный метод, который должен возвращать объект
 * класса Продукт. Подклассы Создателя обычно предоставляют реализацию этого
 * метода.
 */
abstract class Creator
{
    protected $maxUsers = 5000;

    public function __construct(?int $maxUsers = null)
    {
        if (!is_null($maxUsers)) {
            $this->maxUsers = $maxUsers;
        }
    }

    /**
     * Фабричный метод
     *
     * factoryMethod(): Product
     *
     * @return DriverInterface
     */
    abstract protected function getDriver(): DriverInterface;

    /**
     * Также заметьте, что, несмотря на название, основная обязанность Создателя
     * не заключается в создании продуктов. Обычно он содержит некоторую базовую
     * бизнес-логику, которая основана на объектах Продуктов, возвращаемых
     * фабричным методом. Подклассы могут косвенно изменять эту бизнес-логику,
     * переопределяя фабричный метод и возвращая из него другой тип продукта.
     */
    protected function getLocalData(): array
    {
        return DB::query()->fetch();
    }

    protected function setPlaceholders(string $value): string
    {
        return strtr($value, []);
    }

    public function execute($subject, $message): bool
    {
        $data = $this->getLocalData();
        $users = User::where('is_sent', true)->take($this->maxUsers);

        $subject = $this->setPlaceholders($subject);
        $message = $this->setPlaceholders($message);

        // Вызываем фабричный метод, чтобы получить объект-продукт.
        $driver = $this->getDriver();

        $driver->setConfig($data->config);
        $driver->setEmails($users->get('email'));
        $isSent = $driver->send($subject, $message);
        if ($isSent) {
            $users->update('is_sent', false);
        }

        return $isSent;
    }
}

/**
 * Интерфейс Продукта объявляет операции, которые должны выполнять все
 * конкретные продукты.
 */
interface DriverInterface
{
    public function setConfig(array $config): void;

    public function setEmails(array $emails): void;

    public function send(string $subject, string $message): bool;
}

/**
 * Конкретные Продукты предоставляют различные реализации интерфейса Продукта.
 */
class TelegramDriver implements DriverInterface
{
    public function auth(string $apiKey): void
    {
        new \Exception('not authorized');
    }

    public function setConfig(array $config): void
    {
        // TODO: Implement setConfig() method.
    }

    public function setEmails(array $emails): void
    {
        // TODO: Implement setEmails() method.
    }

    public function send(string $subject, string $message): bool
    {
        return true;
    }
}

class ViberDriver implements DriverInterface
{
    public function auth(string $login, string $password): void
    {
        new \Exception('not authorized');
    }

    public function setConfig(array $config): void
    {
        // TODO: Implement setConfig() method.
    }

    public function setEmails(array $emails): void
    {
        // TODO: Implement setEmails() method.
    }

    public function send(string $subject, string $message): bool
    {
        return true;
    }
}

/**
 * Конкретные Создатели переопределяют фабричный метод для того, чтобы изменить
 * тип результирующего продукта.
 */
class ViberCreator extends Creator
{
    const login = 'qwerty';
    const password = 'qwerty';

    /**
     * Обратите внимание, что сигнатура метода по-прежнему использует тип
     * абстрактного продукта, хотя фактически из метода возвращается конкретный
     * продукт. Таким образом, Создатель может оставаться независимым от
     * конкретных классов продуктов.
     */
    protected function getDriver(): DriverInterface
    {
        $driver = new ViberDriver();
        $driver->auth(self::login, self::password);
        return $driver;
    }
}


class TelegramCreator extends Creator
{
    const apiKey = 'qwerty';

    protected function getDriver(): DriverInterface
    {
        $driver = new TelegramDriver();
        $driver->auth(self::apiKey);
        return $driver;
    }
}

/**
 * На этапе инициализации приложение может выбрать, с какой социальной сетью оно
 * хочет работать, создать объект соответствующего подкласса и передать его
 * клиентскому коду.
 */
$mailer = new TelegramCreator(1000);
$mailer->execute('Hi {user}', '{user} this is a test message');

$mailer = new ViberCreator();
$mailer->execute('Hi {user}', '{user} this is a test message');