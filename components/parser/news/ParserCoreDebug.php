<?php
/**
 * Данный класс предназначается для нужд отладки
 *
 * @author FingliGroup <info@fingli.ru>
 * @author Roman Goncharenya <goncharenya@gmail.com>
 *
 * @note   Данный код предоставлен в рамках оказания услуг, для выполнения поставленных задач по сбору и обработке данных.
 * Переработка, адаптация и модификация ПО без разрешения правообладателя является нарушением исключительных прав.
 *
 */

namespace app\components\parser\news;

use app\components\mediasfera\ParserCore;
use app\components\parser\ParserInterface;

class ParserCoreDebug extends ParserCore implements ParserInterface
{
    const USER_ID = 2;
    const FEED_ID = 2;
    // поддерживаемая версия ядра
    const FOR_CORE_VERSION = '1.0.0';
    // режим эмуляции запросов (только для разработки)
    // для подделки запроса к URL нужно добавить элемент массива в файле emulateHtml.php
    protected const EMULATE_MODE = false;
    // включить дебаг-режим (только для разработки)
    protected const DEBUG = true;
    // дебаг-режим  (только для разработки) [core, default]
    //    protected const DEBUG_MODE = 'talkative';
    protected const DEBUG_MODE = 'default';

    public function __construct()
    {
        // 1 - desktop
        // 2 - rss
        // 3 - CORE_ClassicalmusicnewsParsingRu_Parser
        $configType = 3;

        if ($configType == 1)
        {
            $this->config = [
                // режимы работы парсера:
                // rss - RSS витрина
                // desktop - обычный сайт HTML
                'mode'       => 'desktop',

                // максимальное количество новостей, берушихся с витрины
                'itemsLimit' => 1,

                // настройки сайта
                'site'       => [
                    // протокол и домен
                    // (обязательный)
                    'url'        => 'https://native.ru',

                    // использовать юзер-агенты в http запросах.
                    // можно передать значение: bot
                    'user_agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/42.0',

                    // часовой пояс UTC.
                    // Чтобы определить часовой пояс, нужно зайти на https://time.is/Moscow и выбрать ближайший крупный город к которому относится сайт
                    // узнать UTC и прописать его в формате +XX00
                    // Например, Москва: '+0300', Владивосток: '+1000'
                    //                'time_zone'   => '+0300',
                    'time_zone'  => '+0300',

                    // формат даты на исходном сайте (см. https://www.php.net/manual/ru/datetime.format.php)
                    // d - день
                    // m - месяц
                    // Y - полный год
                    // y - год, две цифры
                    // H - час
                    // i - минуты
                    //                    'date_format' => 'd.m.Y H:i',
                ],
                // настройки витрины (режим HTML)
                'list'       => [
                    // URL где находится витрина
                    'url'                 => '/vitrina-bolvanka',

                    // css селектор для контейнера витрины
                    // (обязательный)
                    'container'           => '#container',

                    // css селектор для элемента витрины (относительно контейнера)
                    // (обязательный)
                    'element'             => 'a',

                    // css селектор !должен содержать конечный аттрибут href!  для ссылки (относительно элемента)
                    // (обязательный + должен быть обязательный атрибут, где хранится ссылка)
                    'element-link'        => '[href]',

                    // css селектор для названия элемента (относительно элемента)
                    // (заполняется только, если отсутствует в карточке)
                    'element-title'       => '_text',

                    // css селектор для описания элемента (относительно элемента)
                    // (заполняется только, если отсутствует в карточке)
                    'element-description' => '',

                    // css селектор !должен содержать конечный аттрибут src! для картинки элемента (относительно элемента)
                    // (заполняется только, если отсутствует в карточке)
                    'element-image'       => '',

                    // css селектор для даты элемента (относительно элемента)
                    // (заполняется только, если отсутствует в карточке)
                    'element-date'        => '',
                ],

                // настройка карточки элемента
                'element'    => [

                    // css-селектор для контейнера карточки
                    // (все дальнейшие пути строятся относительно этого контейнера)
                    // (обязательный)
                    'container'           => '#container',

                    // css-селектор описания элемента
                    // (заполняется только, если отсутствует в витрине)
                    'element-description' => '',

                    // css-селектор для получения даты создания новости
                    // (заполняется только, если отсутствует в витрине)
                    'element-date'        => '',

                    // css селектор для получения картинки
                    // !должен содержать конечный аттрибут src! (например: img.main-image[src])
                    // (заполняется только, если отсутствует в витрине)
                    'element-image'       => '',

                    // css-селектор для основного текста
                    // (для заполнения модели NewsPostItem)
                    'element-text'        => '#text',

                    // css-селектор для цитаты
                    // (если не заполнено, то по умолчанию берутся теги: blockquote и q)
                    // (опционально)
                    'element-quote'       => '',

                    // игнорируемые css-селекторы
                    // (можно через запятую)
                    // (опционально)
                    'ignore-selectors'    => '',
                ]
            ];
        }
        elseif ($configType == 2)
        {
            $this->config = [
                // режимы работы парсера:
                // rss - RSS витрина
                // desktop - обычный сайт HTML
                'mode'       => 'rss',

                // максимальное количество новостей, берушихся с витрины
                'itemsLimit' => 10,

                // настройки сайта
                'site'       => [
                    // протокол и домен
                    // (обязательный)
                    'url'         => 'https://test/',

                    // использовать юзер-агенты в http запросах.
                    // можно передать значение: bot
                    //                'user_agent'  => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/42.0',
                    'user_agent'  => 'bot',

                    // часовой пояс UTC.
                    // Чтобы определить часовой пояс, нужно зайти на https://time.is/Moscow и выбрать ближайший крупный город к которому относится сайт
                    // узнать UTC и прописать его в формате +XX00
                    // Например, Москва: '+0300', Владивосток: '+1000'
                    'time_zone'   => '+0000',

                    // формат даты на исходном сайте (см. https://www.php.net/manual/ru/datetime.format.php)
                    // d - день
                    // m - месяц
                    // Y - полный год
                    // y - год, две цифры
                    // H - час
                    // i - минуты
                    'date_format' => 'd.m.Y',
                ],

                // настройки витрины (режим RSS)
                'rss'        => [
                    // относительный URL где находится RSS
                    // (обязательный)
                    'url'                 => '/rss.xml',

                    // css селектор для элемента витрины (желательно от корня)
                    // (обязательный)
                    'element'             => 'rss > channel > item',

                    // css селектор для ссылки (относительно элемента)
                    // (обязательный)
                    'element-link'        => 'link',

                    // css селектор для названия элемента (относительно элемента)
                    // (обязательный)
                    'element-title'       => 'title',

                    // css селектор для описания элемента (относительно элемента)
                    // (заполняется только, если отсутствует в карточке)
                    'element-description' => 'description',

                    // css селектор для картинки элемента (относительно элемента)
                    // (заполняется только, если отсутствует в карточке)
                    'element-image'       => 'enclosure[url]',

                    // css селектор для даты элемента (относительно элемента)
                    // (заполняется только, если отсутствует в карточке)
                    'element-date'        => 'pubDate',
                ],

                // настройка карточки элемента
                'element'    => [

                    // css-селектор для контейнера карточки
                    // (все дальнейшие пути строятся относительно этого контейнера)
                    // (обязательный)
                    'container'        => '#container',

                    // css-селектор для основного текста
                    // (для заполнения модели NewsPostItem)
                    // (обязательный)
                    'element-text'     => '#text',

                    // css-селектор для получения даты создания новости
                    // (заполняется только, если отсутствует в витрине)
                    'element-date'     => '',

                    // css селектор для получения картинки
                    // !должен содержать конечный аттрибут src! (например: img.main-image[src])
                    // (заполняется только, если отсутствует в витрине)
                    'element-image'    => '',

                    // css-селектор для цитаты
                    // (опционально)
                    'element-quote'    => '.quote-custom',

                    // игнорируемые css-селекторы
                    // (можно через запятую)
                    // (опционально)
                    'ignore-selectors' => '.reklama, span.bad',
                ]
            ];
        }
        elseif ($configType == 3)
        {
            $this->config = [
                // режимы работы парсера:
                // rss - RSS витрина
                // desktop - обычный сайт HTML
                'mode'       => 'rss',

                // максимальное количество новостей, берушихся с витрины
                'itemsLimit' => 10,

                // настройки сайта
                'site'       => [
                    // протокол и домен
                    // (обязательный)
                    'url'        => 'https://www.classicalmusicnews.ru',

                    // использовать юзер-агенты в http запросах.
                    // можно передать значение: bot
                    'user_agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/42.0',
                    //                    'user_agent' => 'bot',

                    // часовой пояс UTC.
                    // Чтобы определить часовой пояс, нужно зайти на https://time.is/Moscow и выбрать ближайший крупный город к которому относится сайт
                    // узнать UTC и прописать его в формате +XX00
                    // Например, Москва: '+0300', Владивосток: '+1000'
                    'time_zone'  => '+0300',

                    // формат даты на исходном сайте (см. https://www.php.net/manual/ru/datetime.format.php)
                    // d - день
                    // m - месяц
                    // Y - полный год
                    // y - год, две цифры
                    // H - час
                    // i - минуты
                    //                    'date_format' => 'd.m.Y',
                ],

                // настройки витрины (режим RSS)
                'rss'        => [
                    // относительный URL где находится RSS
                    // (обязательный)
                    'url'                 => '/feed',

                    // css селектор для элемента витрины (желательно от корня)
                    // (обязательный)
                    'element'             => 'rss > channel > item',

                    // css селектор для названия элемента (относительно элемента)
                    // (обязательный)
                    'element-title'       => 'title',

                    // css селектор для описания элемента (относительно элемента)
                    // (заполняется только, если отсутствует в карточке)
                    'element-description' => 'description',

                    // css селектор для картинки элемента (относительно элемента)
                    // (заполняется только, если отсутствует в карточке)
                    'element-image'       => 'enclosure[url]',

                    // css селектор для даты элемента (относительно элемента)
                    // (заполняется только, если отсутствует в карточке)
                    'element-date'        => 'pubDate',

                    // css селектор для ссылки (относительно элемента)
                    // (обязательный)
                    'element-link'        => 'link',
                ],

                // настройка карточки элемента
                'element'    => [

                    // css-селектор для контейнера карточки
                    // (все дальнейшие пути строятся относительно этого контейнера)
                    // (обязательный)
                    'container'           => '.content-sidebar-wrap',

                    // css-селектор для основного текста
                    // (для заполнения модели NewsPostItem)
                    // (обязательный)
                    'element-text'        => 'main .entry-content',

                    // css-селектор для получения даты создания новости
                    // (заполняется только, если отсутствует в витрине)
                    'element-date'        => '',

                    // css-селектор для получения описания новости
                    // (заполняется только, если отсутствует в витрине)
                    'element-description' => '',

                    // css селектор для получения картинки
                    // !должен содержать конечный аттрибут src! (например: img.main-image[src])
                    // (заполняется только, если отсутствует в витрине)
                    'element-image'       => 'img.size-medium[src]',

                    // css-селектор для цитаты
                    // (опционально)
                    'element-quote'       => '',

                    // игнорируемые css-селекторы
                    // (можно через запятую)
                    // (опционально)
                    'ignore-selectors'    => '',
                ]
            ];
        }
        parent::__construct();
    }

    public static function run()
    : array
    {
        $posts  = [];
        $Parser = new self();

        //        $Parser->testGetDate();
        $items = $Parser->getItems();
        $posts = $Parser->getCards(array_keys($items));

        //        echo '<pre>';
        //        print_r($posts);
        //        echo '</pre>';
        //        die;

        return $posts;
    }
}