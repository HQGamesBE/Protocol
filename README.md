# Protocol

## PHP

### Register packet handler

```php
 use \HQGames\network\PacketHandlerManager as PHM;
/**
 * @var \HQGames\network\IProtocolPacketHandler $handler
 */
PHM::getInstance()->registerPacketHandler($handler);
```

### Unregister packet handler

```php
 use \HQGames\network\PacketHandlerManager as PHM;
/**
 * @var \HQGames\network\IProtocolPacketHandler $handler
 */
PHM::getInstance()->unregisterPacketHandler($handler);
```

## Javascript

> *Nothing implemented yet!*