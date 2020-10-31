## Тех. обслуживание для parser1500

### Для запуска изолированного дебага

``php yii parser-core-debug/news ParserCoreDebug``

Для этого нужно (сделать сим. ссылки) 
- ParserCoreDebugController -> commands/
- ParserCore -> components/core/

### Для запуска чекера парсеров

```php yii parser-core-debug/check-parsers X```

, где X = кол-во парсеров для проверки