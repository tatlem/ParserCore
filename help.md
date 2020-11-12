## Тех. обслуживание для parser1500

### Для запуска изолированного дебага

``php yii parser-core-debug/news ParserCoreDebug``

Для этого нужно (сделать сим. ссылки) 
- ParserCoreDebugController -> commands/
- ParserCore -> components/core/

### Для запуска чекера парсеров

```php yii parser-core-debug/check-parsers <from> <to>``` 

### Для запуска чекера лид vs текст

```php yii parser-core-debug/check-desc-vs-text <from> <to>``` 