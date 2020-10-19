<?php
/**
 *
 * @author FingliGroup <info@fingli.ru>
 * @author Roman Goncharenya <goncharenya@gmail.com>
 *
 * @note   Данный код предоставлен в рамках оказания услуг, для выполнения поставленных задач по сбору и обработке данных.
 * Переработка, адаптация и модификация ПО без разрешения правообладателя является нарушением исключительных прав.
 *
 */

namespace app\components\mediasfera;

use app\components\parser\NewsPostItem;
use app\components\parser\NewsPost;
use DateTime;
use DateTimeZone;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\UriResolver;
use yii\base\Exception;
use app\components\Helper;
use DateTimeImmutable;
use DateInterval;
use wapmorgan\TimeParser\TimeParser;

/**
 * # Ядро для парсеров
 *
 * ## Задачи
 *
 * Ядро решает две задачи:
 *
 * 1. Навигация (менеджмент URL)
 * 1.1. Нахождение витрины
 * 1.2. Нахождение карточек в витрине
 * 1.3. Пагинация для HTML (не реализована)
 * 1.4. Обработка редиректов (некоторые сайты используют редирект через браузер) (не реализована)
 * 1.5. В перспективе - решение вопросов с капчами и авторизациями
 *
 * 2. Парсинг HTML (разбор конкретных страниц)
 * 2.1. Очищение данных
 * 2.2. Нормализация данных
 * 2.3. Вспомогательные функции
 * 2.4. Фильтрация данных
 *
 *
 * ## Дополнительные правила
 *
 * 1. Все URL указываются относительно домена (можно указать абсолютную ссылку, если другой домен или протокол)
 *
 *
 * ## Версионирование:
 *
 * При изменениях ломающих совместимость - увеличиваем мажорную версию
 * При добавление фич - минорную
 *
 * С целью пресечения возникновения ошибок в уже проверенных парсерах, каждая мажорная версия будет
 * новым файлом:
 * ParserCore.php
 * ParserCore2.php
 *
 */
class ParserCore
{
    // версия ядра (см. Версионирование)
    private const VERSION = '1.0.0-alfa';
    // доступные режимы работы парсера
    private const  MODE_TYPES = ['desktop', 'mobile', 'rss'];
    // путь до папки со вспомогательными файлами
    private const WORK_DIR = __DIR__ . '/../mediasfera/';
    // лимит на кол-во элементов по умолчанию
    private const MAX_ITEMS = 100;
    // лимит на кол-во элементов
    protected int $itemsLimit = self::MAX_ITEMS;
    // хранилище всякого
    private array $store = [];
    // внутренний формат для хранений данных элементов
    // $items => [
    //      URL => [...data...]
    // ]
    protected array $items = [];
    // Внутренний формат для хранения данных из текста новости.
    // чтобы была возможность перевода данных через адаптер в другой формат (json, xml, php) (на будущее)
    // $itemsTextData => [
    //      URL => [
    //          n => [
    //              'type'      => TYPE,
    //              'tag'       => tag,
    //              'text'      => text,
    //              'url'       => URL,
    //              'value'     => any value
    //              'property'  => [...properties...]
    //          ]
    //      ]
    // ]
    // NewsPostItem
    //    public int $type = (TYPE_HEADER, TYPE_TEXT, TYPE_IMAGE, TYPE_QUOTE, TYPE_LINK, TYPE_VIDEO);
    //    public ?string $text;
    //    public ?string $image;
    //    public ?string $link;
    //    public ?int $headerLevel;
    //    public ?string $youtubeId;
    protected array $itemsData = [];
    private const TYPE_HEADER = 'header';
    private const TYPE_TEXT   = 'text';
    private const TYPE_IMAGE  = 'image';
    private const TYPE_QUOTE  = 'quote';
    private const TYPE_LINK   = 'link';
    //private const TYPE_AUDIO = 'audio';
    private const TYPE_VIDEO = 'video';
    // URL который обрабатывается сейчас
    private string $currentUrl = '';
    // протокол и домен
    protected string $siteUrl = '';
    // режим работы парсера
    protected string $mode = '';
    // временная зона  UTC
    private string $timeZone = '+0300';
    // формат даты
    private string $dateFormat = '';
    // формат даты RSS
    private string $dateFormatRss = 'D, d M Y H:i:s O';
    // TimeParser
    protected $TimeParser;
    // теги которые точно оставляем для анализа
    protected $allowedTags = [
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'a',
        'img',
        //                              'figure', 'picture',
        'iframe',
        'video',
        'source',
        'blockquote',
        'q'
    ];
    // конфигурация для конкретного экземпляра
    public array $config = [
        // режимы работы парсера:
        // rss - RSS витрина
        // desktop - обычный сайт HTML
        'mode'       => 'desktop',

        // максимальное количество новостей, берушихся с витрины
        'itemsLimit' => self::MAX_ITEMS,

        // настройки сайта
        'site'       => [
            // протокол и домен
            // (обязательный)
            'url'             => '',

            // использовать юзер-агенты в http запросах.
            // (можно также попробовать передать значение: "bot", если сайт не парсится)
            // (опционально)
            //            'user_agent'  => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/42.0',
            'user_agent'      => 'bot',

            // часовой пояс UTC.
            // Чтобы определить часовой пояс, нужно зайти на https://time.is/Moscow и выбрать ближайший крупный город к которому относится сайт
            // узнать UTC и прописать его в формате +XX00
            // Например, Москва: '+0300', Владивосток: '+1000'
            // (опционально)
            'time_zone'       => '+0300',

            // формат даты для HTML витрины и карточки
            // (см. https://www.php.net/manual/ru/datetime.format.php)
            // d - день
            // m - месяц
            // Y - полный год
            // y - год, две цифры
            // H - час
            // i - минуты
            'date_format'     => 'd.m.Y H:i',

            // формат даты в RSS
            // (указывать только если он отличается от стандартного D, d M Y H:i:s O!)
            'date_format_rss' => 'D, d M Y H:i:s O',
        ],

        // настройки витрины (режим RSS)
        'rss'        => [
            // относительный URL где находится RSS
            // (обязательный)
            'url'                 => '/rss.xml',

            // css селектор для элемента витрины (желательно от корня)
            // (обязательный)
            'element'             => 'rss > channel > item',

            // css селектор для названия элемента (относительно элемента)
            // (обязательный)
            'element-title'       => 'title',

            // css селектор для описания элемента (относительно элемента)
            'element-description' => 'description',

            // css селектор для картинки элемента
            // (относительно элемента)
            'element-image'       => 'enclosure[url]',

            // css селектор для даты элемента
            // (относительно элемента)
            'element-date'        => 'pubDate',

            // css селектор для ссылки
            // (относительно элемента)
            'element-link'        => 'link',
        ],

        // настройки витрины (режим HTML)
        'list'       => [
            // URL где находится витрина
            'url'                 => '/',

            // css селектор для контейнера витрины
            //  (обязательный)
            'container'           => '',

            // css селектор для элемента витрины (относительно контейнера)
            //  (обязательный)
            'element'             => '',

            // css селектор !должен содержать конечный аттрибут href!  для ссылки (относительно элемента)
            //  (обязательный + должен быть обязательный атрибут, где хранится ссылка)
            'element-link'        => '',

            // css селектор для названия элемента (относительно элемента)
            // (обязательный)
            'element-title'       => '',

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
            'container'     => '',

            // css-селектор для основного текста
            // (для заполнения модели NewsPostItem)
            // (обязательный)
            'element-text'  => '',

            // css-селектор для получения даты создания новости
            // (заполняется только, если отсутствует в витрине)
            'element-date'  => '',

            // css селектор для получения картинки
            // !должен содержать конечный аттрибут src! (например: img.main-image[src])
            // (заполняется только, если отсутствует в витрине)
            'element-image' => '',

            // css-селектор для цитаты
            // (если не заполнено, то по умолчанию берутся теги: blockquote и q)
            // (опционально)
            //                'element-quote'       => '',

            // игнорируемые css-селекторы
            // (можно через запятую)
            // (опционально)
            //                'ignore-selectors'    => '',
        ]
    ];

    public function __construct()
    {
        if (static::EMULATE_MODE)
        {
            static::showLog('--- Внимание! Включен режим эмуляции http запросов. Реальные запросы не делаются ---', 'warning', true, true);
        }

        // инициализация переменных
        $this->siteUrl       = $this->getSiteUrl();
        $this->mode          = $this->getMode();
        $this->itemsLimit    = $this->getItemsLimit();
        $this->timeZone      = $this->getTimeZone();
        $this->dateFormat    = $this->getDateFormat();
        $this->dateFormatRss = $this->getDateFormatRss();
        $this->TimeParser    = $this->getTimeParser();

        // проверка переменных

        // данные свойства должны быть заполнены в парсерах
        $requiredPropsSite = [
            'url'
        ];

        $requiredPropsRss = [
            'url',
            'element',
            'element-link',
            'element-title'
        ];

        $requiredPropsList = [
            'url',
            'container',
            'element',
            'element-link',
        ];

        $requiredPropsCard = [
            'container',
            'element-text',
        ];

        if ($this->mode === 'rss')
        {
            $requiredProps = [
                'config' => [
                    'site'    => $requiredPropsSite,
                    'rss'     => $requiredPropsRss,
                    'element' => $requiredPropsCard,
                ]
            ];
        }
        else
        {
            $requiredProps = [
                'config' => [
                    'site'    => $requiredPropsSite,
                    'list'    => $requiredPropsList,
                    'element' => $requiredPropsCard
                ]
            ];
        }
        //        $requiredProps = [
        //            'siteUrl',
        //        ];

        $props = get_object_vars($this);

        $this->keysIsNotEmptyInAnotherArray($requiredProps, $props);

        if (0 && 'проверка совместимости с версией ядра')
        {
            $coreVer       = self::getVersionArray(self::VERSION);
            $parserCoreVer = self::getVersionArray(static::FOR_CORE_VERSION);

            if (!($coreVer[0] === $parserCoreVer[0]))
            {
                throw new Exception('Несовместимая версия ядра. Обновите, пожалуйста, зависимости через composer update. ' . PHP_EOL . '
                    Требуется ядро ParserCore' . $parserCoreVer[0]);
            }
        }
    }

    /**
     * Получение списка элементов из витрины
     *
     * @return array $items
     *
     */
    protected function getItems()
    : array
    {
        static::showLog(
            '--------------------------------------------------------------------' . PHP_EOL .
            ' Старт парсера на ядре ' . self::VERSION . ' [режим: ' . $this->mode . ', макс. новостей: ' . $this->itemsLimit . ']' . PHP_EOL .
            ' Время старта: ' . date('d.m.Y H:i:s') . ' (' . $this->timeZone . ')' . PHP_EOL .
            '--------------------------------------------------------------------', 'default', true, true);

        static::showLog(PHP_EOL . '----------------------------------');
        static::showLog(' Запрашиваем витрину ' . (($this->mode == 'rss') ? 'RSS' : 'HTML'));
        static::showLog('----------------------------------');

        $vitrinaSelector = '';

        if ($this->mode == 'rss')
        {
            $listPageUrl       = $this->getUrl($this->config['rss']['url']);
            $vitrinaElSelector = $this->config['rss']['element'];
        }
        else
        {
            $listPageUrl       = $this->getUrl($this->config['list']['url']);
            $vitrinaSelector   = $this->config['list']['container'];
            $vitrinaElSelector = $this->config['list']['element'];
        }

        static::showLog('-- ' . $listPageUrl);

        $listPageData = self::getPage($listPageUrl);

        if (empty($listPageData))
        {
            throw new Exception('Не удалось получить витрину');
        }

        //        print_r($listPageData);
        //        die;

        static::showLog('- разбираем витрину на элементы по CSS-селектору "' . $vitrinaSelector . ' ' . $vitrinaElSelector . '"...');

        if ($this->mode == 'rss')
        {
            $elementsData = $this->getElementsDataFromRss($listPageData, $this->config['rss']['element'], 'node', $this->itemsLimit);
        }
        else
        {
            $elementsData = $this->getElementsDataFromHtml($listPageData, $this->config['list']['container'], $this->config['list']['element'], 'html');
            $elementsData = array_splice($elementsData, 0, $this->itemsLimit);
        }


        //                print_r($elementsData);
        //        die;

        if (empty($elementsData))
        {
            throw new Exception('Не удалось получить элементы витрины');
        }
        else
        {
            static::showLog('- получено элементов: ' . count($elementsData) . '...');
        }

        static::showLog('- обработка списка элементов...');

        foreach ($elementsData as $elementData)
        {
            $elTitle       = '';
            $elLink        = '';
            $elDescription = '';
            $elImage       = '';
            $elDate        = '';


            if ($this->mode == 'rss')
            {
                $elTitleData = current($this->getElementsDataFromRss('', $this->config['rss']['element-title'], 'text', -1, $elementData));
                $elLinkData  = current($this->getElementsDataFromRss('', $this->config['rss']['element-link'], 'text', -1, $elementData));

                if (!empty($this->config['rss']['element-description']))
                {
                    $elDescriptionData = current($this->getElementsDataFromRss('', $this->config['rss']['element-description'], 'text', -1, $elementData));
                }

                if (!empty($this->config['rss']['element-image']))
                {
                    $elImageData = current($this->getElementsDataFromRss('', $this->config['rss']['element-image'], 'text', -1, $elementData));
                }

                if (!empty($this->config['rss']['element-date']))
                {
                    $elDateData = current($this->getElementsDataFromRss('', $this->config['rss']['element-date'], 'text', -1, $elementData));
                }
            }
            else
            {
                $elTitleData = current($this->getElementsDataFromHtml($elementData, '', $this->config['list']['element-title'], 'text'));
                $elLinkData  = current($this->getElementsDataFromHtml($elementData, '', $this->config['list']['element-link']));

                if (!empty($this->config['list']['element-description']))
                {
                    $elDescriptionData = current($this->getElementsDataFromHtml($elementData, '', $this->config['list']['element-description'], 'html'));
                }

                if (!empty($this->config['list']['element-image']))
                {
                    $elImageData = current($this->getElementsDataFromHtml($elementData, '', $this->config['list']['element-image']));
                }

                if (!empty($this->config['list']['element-date']))
                {
                    $elDateData = current($this->getElementsDataFromHtml($elementData, '', $this->config['list']['element-date']));
                }
            }

            static::showLog('- обработка полученных данных элемента...' . PHP_EOL);

            if (!empty($elTitleData))
            {
                $elTitle = strip_tags($elTitleData);
            }

            if (!empty($elLinkData))
            {
                $elLink = $this->getUrl($elLinkData);
            }

            if (!empty($elDescriptionData))
            {
                $elDescription = strip_tags($elDescriptionData);
            }

            if (!empty($elImageData))
            {
                $elImage = $this->getUrl($elImageData);
            }

            if (!empty($elDateData))
            {
                $elDate = $this->getDate($elDateData);
            }

            static::showLog('-- link: ' . $elLink);
            static::showLog('-- title: ' . $elTitle);
            static::showLog('-- date: ' . $elDate);
            static::showLog('-- image: ' . $elImage);
            static::showLog('-- description: ' . $elDescription);
            //            echo PHP_EOL;

            if (!empty($elLink))
            {
                $this->items[$elLink] = [
                    'title'       => $elTitle,
                    'link'        => $elLink,
                    'date'        => $elDate,
                    'image'       => $elImage,
                    'description' => $elDescription,
                ];
            }
        }

        //        print_r($this->items);
        //        die;


        //        return $urls;
        return $this->items;
    }

    /**
     * Проход по полученным элементам по их URL
     *
     * @return array $items
     *
     */
    protected function getCards(array $itemUrls, array $itemsToInstall = [])
    : array {
        static::showLog(PHP_EOL . '----------------------------------');
        static::showLog(' Запрашиваем карточки ');
        static::showLog('----------------------------------');

        if (!empty($itemsToInstall))
        {
            $this->items = $itemsToInstall;
        }

        static::showLog('- всего карточек: ' . count($itemUrls) . '...');
        //        print_r($this);
        //        die;

        $itemsParsed = [];

        if (!empty($itemUrls))
        {
            foreach ($itemUrls as $itemUrl)
            {
                static::showLog(PHP_EOL . '- запрашиваем ' . $itemUrl);
                $itemsParsed[$itemUrl] = $this->getCard($itemUrl);
            }
        }

        //        print_r($itemUrls);
        print_r($itemsParsed);
        die;

        return [];
    }

    /**
     * Парсинг конкретных страницы по URL
     *
     * @param string $url
     *
     * @return array элементы во внутреннем формате
     *
     */
    protected function getCard(string $url)
    : array {
        $item = [];
        $html = $this->getPage($url);

        if (!empty($html))
        {
            $elDescription = '';
            $elImage       = '';
            $elDate        = '';
            $elItemData    = []; // массив для NewsPostItem

            $containerData = current($this->getElementsDataFromHtml($html, '', $this->config['element']['container']));

            //            print_r($containerData);
            if (!empty($containerData))
            {
                if (!empty($this->config['element']['element-description']))
                {
                    $elDescriptionData = current($this->getElementsDataFromHtml($containerData, '', $this->config['element']['element-description'], 'html'));
                }

                if (!empty($this->config['element']['element-date']))
                {
                    $elDateData = current($this->getElementsDataFromHtml($containerData, '', $this->config['element']['element-date'], 'html'));
                }

                if (!empty($this->config['element']['element-image']))
                {
                    $elImageData = current($this->getElementsDataFromHtml($containerData, '', $this->config['element']['element-image'], 'html'));
                }

                if (!empty($this->config['element']['element-text']))
                {
                    $elTextData = current($this->getElementsDataFromHtml($containerData, '', $this->config['element']['element-text'], 'html'));
                }

                static::showLog('-- обработка полученных данных элемента...');

                if (!empty($elDescriptionData))
                {
                    $elDescription = strip_tags($elDescriptionData);
                }

                if (!empty($elDateData))
                {
                    $elDate = $this->getDate($elDateData);
                }

                if (!empty($elImageData))
                {
                    $elImage = $this->getUrl($elImageData);
                }

                static::showLog('--- date: ' . $elDate);
                static::showLog('--- image: ' . $elImage);
                static::showLog('--- description: ' . $elDescription);

                static::showLog('-- начинаем подготовку текста новости...');

                if (empty($elTextData))
                {
                    throw new Exception('Не получен element-text="' . (!empty($this->config['element']['element-text']) ? $this->config['element']['element-text'] : '') . '". Установите настройки парсера config[element][element-text]');
                }

                $elTextHtml = $this->getCardTextHtml($elTextData);

                //                print_r($elTextHtml);
                //                die;

                static::showLog('-- начинаем разбор текста новости во внутренний формат itemData...');

                $elItemData = $this->getItemData($elTextHtml);

                return [
                    'description' => $elDescription,
                    'image'       => $elImage,
                    'date'        => $elDate,
                    'data'        => $elItemData,
                ];
            }
        }

        return $item;
    }

    // @FEATURE вырезание игнорируемых CSS-селекторов из ignore-selectors
    // включая element-image, element-title, element-description. Если они ...
    protected function getHtmlWithoutIgnoredSelectors(string $html)
    : string {
        // TYPE_QUOTE

        // ignore-selectors
        return $html;
    }

    /**
     * Подготовка html текста новости для разбора
     * Вырезаем все теги кроме TYPE_IMAGE, TYPE_QUOTE, TYPE_LINK, TYPE_VIDEO
     *
     * @param string $html - подготовленный html без лишнего
     *
     * @return string
     */
    protected function getCardTextHtml(string $html)
    : string {
        $html = $this->getHtmlWithoutIgnoredSelectors($html);

        // коррекция тегов для правильной обрезки
        // (в частности нужно добавить пробелы перед <td> и <li>, чтобы текст не сливался
        $html = str_replace('<td>', '<td> ', $html);
        $html = str_replace('<li>', '<li> ', $html);

        // вырезаем все ненужные теги, кроме разрешенных в allowedTags
        $html = strip_tags($html, $this->allowedTags);

        return $html;
    }

    protected function getItemData(string $html)
    : array {
        //                echo $html;
        //        echo PHP_EOL . '-----------' . PHP_EOL;

        $variant = 'Вариант 1';

        // используем нативную PHP функцию
        if ('Вариант 1' == $variant)
        {
            $dom = new \DOMDocument();

            // чтобы принимал HTML5 теги прячем временно ошибки
            libxml_use_internal_errors(true);

            // @TODO могут быть проблемы с кодировкой
            // добавляем тег [xml encoding="utf-8"] чтобы исправить кодировку
            $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);

            // When using this funtion, be sure to clear your internal error buffer. If you dn't and you are using this in a long running process, you may find that all your memory is used up.
            libxml_clear_errors();

            $xpath = new \DOMXPath($dom);

            // use the fact that PHP DOM wraps everything into the body and get the text() and other tags
            $entries = $xpath->query('//body/text() | //body/*');

            $itemData = [];

            if (!empty($entries))
            {
                foreach ($entries as $entry)
                {
                    $tagName = !empty($entry->tagName) ? $entry->tagName : ':text';
                    $val     = $this->stripText($entry->nodeValue);
                    $text    = $this->stripText($entry->textContent);

                    // обработка на основе тега
                    $data = [];

                    switch ($tagName)
                    {
                        // просто текст
                        case 'p':
                        case 'text':
                            if (!empty($val))
                            {
                                $data = [
                                    'type' => self::TYPE_TEXT,
                                    'text' => $val,
                                    'tag'  => $tagName,
                                ];
                            }
                            break;

                        // заголовки
                        case 'h1' :
                        case 'h2' :
                        case 'h3' :
                        case 'h4' :
                        case 'h5' :
                        case 'h6' :
                            $data = [
                                'type' => self::TYPE_HEADER,
                                'text' => $text,
                                'tag'  => $tagName,
                            ];
                            break;

                        case 'img':
                            //                            $data = [
                            //                                'type' => self::TYPE_IMAGE,
                            //                                // в текст можно было б сохранять alt например
                            //                                'text' => $entry->getAttribute('alt'),
                            //                                'url'  => $this->getUrl($entry->getAttribute('src')),
                            //                                'tag'  => $tagName,
                            //                            ];
                            break;

                        case 'iframe':
                        case 'video':
                            $src = '';

                            if ($tagName == 'iframe')
                            {
                                $src = $entry->getAttribute('src');
                            }
                            elseif ($tagName == 'video')
                            {
                                $source = $entry->getElementsByTagName('source')->item(0);

                                if ($source)
                                {
                                    $src = $source->getAttribute('src');
                                }
                            }

                            //                            $data = [
                            //                                'type'  => self::TYPE_VIDEO,
                            //                                'tag'   => $tagName,
                            //                                'value' => static::getYoutubeIdFromUrl($src),
                            //                            ];
                            break;

                        // @TODO quote-selector
                        case 'blockquote':
                        case 'q':
                            //                            $data = [
                            //                                'type' => self::TYPE_QUOTE,
                            //                                'tag'  => $tagName,
                            //                                'text' => $text,
                            //                            ];
                            break;
                    }

                    if (!empty($data))
                    {
                        $itemData[] = $data;
                    }
                    //                    echo $tagName . PHP_EOL;
                    //                    echo $val . PHP_EOL;
                    //                    //                echo $entry->nodeValue;
                    //                    print_r($entry);
                    //                    echo '---' . PHP_EOL;
                }
            }
            //            print_r($itemData);
            //            die;

        }
        // с использованием Crawler (проблема - untagged text)
        elseif ('Вариант 2' == $variant)
        {
            // crawler сам дополняет код <html> и <body>
            $Crawler = new Crawler($html);

            $elements = $Crawler->filter('');

            if (!count($elements))
            {
                return [];
            }

            foreach ($elements as $element)
            {
                //            $element = new Crawler($content);
                //
                //            $elName = (isset($element->nodeName)) ? $element->nodeName : ':text';
                //            var_dump($element->nodeName);
                var_dump($element);
            }
        }

        // попробовать обернуть весь текст в <text>text</text> через регулярное выражение
        elseif ('Вариант 3' == $variant)
        {
            // ....
        }

        return $itemData;
    }

    // NewsPostItem
    //    public int $type = (TYPE_HEADER, TYPE_TEXT, TYPE_IMAGE, TYPE_QUOTE, TYPE_LINK, TYPE_VIDEO);
    //    public ?string $text;
    //    public ?string $image;
    //    public ?string $link;
    //    public ?int $headerLevel;
    //    public ?string $youtubeId;
    protected function adapterToParser1500(array $items)
    : array {
        return [];
    }

    protected static function getYoutubeIdFromUrl(string $url)
    : ?string {
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i';

        if (preg_match($pattern, $url, $match))
        {
            return $match[1];
        }

        return '';
    }

    protected function stripText(string $text)
    : string {
        return trim($text);
    }

    //        protected function getItemTextData(array $data, string $type)
    //        : array {
    //
    //            $itemsTextData[$this->currentUrl] = [
    //
    //            ];
    //        }

    public function testGetDate()
    {
        static::showLog('--- format= ' . $this->dateFormat . ' | zone= ' . $this->timeZone . ' ---');
        $valuesDate = [
            '',
            '17:00',
            '16.10.2020 | 18:10',
            '15.10.2020',
            '15.10.2020 10:00',
            '15.10.2020, 10:00',
            '15/10/2020 10:00:00',
            '15-10-2020 10.00',
            '10:00 15.10.2020',
            //            '2020.01.01',
        ];
        $valuesText = [
            'сегодня',
            'сегодня в 2 часа',
            'Сегодня, 16:36',
            '16 октября 2020 года, 19:01',
            '19:01 от 16 октября 2020 года',
            '<a href="https://www.fondsk.ru/authors/vladislav-gulevich-37.html">Владислав ГУЛЕВИЧ</a> | 16.10.2020',
            'только что',
            '16.10 в 21:30',
            '2 часа назад',
            '7 часов назад',
            'вчера в 19:10',
            'вчера',
            '1 октября',
            '11 октября 2019',
            '11 октября 2019, 10:00:00',
            '16 Окт, 2020',
            '',
            'uqgwejhjvjv',
            'фигня какая-то'
        ];

        //                $values = $valuesDate;
        //        $values = $valuesText;
        $values = array_merge($valuesDate, $valuesText);

        foreach ($values as $value)
        {
            static::showLog($value . "\t" . '  => ' . $this->getDate($value));
        }
    }

    /**
     * Парсинг даты (числовой или строковый формат)
     *
     * @param string $date
     *
     * @return string|null
     * @throws \Exception
     */
    protected function getDate(string $date)
    : ?string {
        $timeZone = new DateTimeZone($this->timeZone);

        $date = strip_tags($date);

        // вырезаем лишние символы
        //        $date = preg_replace('~\b[а-я]{1,2}\b~', '', $date);
        //        echo $date;
        $date = preg_replace('~[/\\\\-]~', '.', $date);
        $date = str_replace(',', '', $date);

        // есть текст
        if (preg_match('/[\p{Cyrillic}]+/u', $date))
        {
            //            echo 'text: ';

            $dateTime = $this->getDateFromText($date, $timeZone);
        }
        // нет текста
        else
        {
            //            echo 'date: ';

            $dateTime = $this->getDateFromDate($date, $timeZone);
        }

        if (!empty($dateTime))
        {
            if (0 && 'переводим в формат Гринвича')
            {
                $dateTime2 = new \DateTime(null, new DateTimeZone('UTC'));
                $dateTime2->setTimestamp($dateTime->getTimestamp());

                return $dateTime2->format('Y-m-d H:i:s');
            }
            else
            {
                return $dateTime->format('Y-m-d H:i:s');
            }
        }

        return null;
    }

    /**
     *  Дата из даты
     *
     * @param string $date
     *
     * @return string || null
     *
     */
    protected function getDateFromDate(string $date, DateTimeZone $timeZone)
    : ?DateTimeImmutable {
        //        echo $date . PHP_EOL;
        //        echo $this->timeZone . PHP_EOL;

        if ('дополняем дату до полного вида')
        {
            // пытаемся определить дату через PHP
            $parsedDate = date_parse($date);

            //            print_r($parsedDate);

            if ($parsedDate)
            {
                if (empty($parsedDate['day']) && empty($parsedDate['month']) && empty($parsedDate['year']))
                {
                    $date = date('d') . '.' . date('m') . '.' . date('Y') . ' ' .
                        ($parsedDate['hour'] ? $parsedDate['hour'] : '00') . ':' . ($parsedDate['minute'] ? $parsedDate['minute'] : '00');
                }
                else
                {
                    $date = $parsedDate['day'] . '.' . $parsedDate['month'] . '.' . $parsedDate['year'] . ' ' .
                        ($parsedDate['hour'] ? $parsedDate['hour'] : '00') . ':' . ($parsedDate['minute'] ? $parsedDate['minute'] : '00');
                }
            }
        }

        if ($this->mode === 'rss')
        {
            $dateTime = DateTimeImmutable::createFromFormat($this->dateFormatRss, $date, $timeZone);
        }
        else
        {
            $dateTime = DateTimeImmutable::createFromFormat($this->dateFormat, $date, $timeZone);
        }

        if (!empty($dateTime))
        {
            return $dateTime;
        }
        // последний шанс узнать дату
        else
        {
            // @FEATURE например из meta или header
        }

        return null;
    }

    /**
     * @param string $date
     *
     * @return string|null
     * @throws \Exception
     *
     * Дата из текста
     * (только что, сегодня, вчера, час назад и т.д.)
     * а также, типа: 15 октября 2020
     *
     */
    protected function getDateFromText(string $date, DateTimeZone $timeZone)
    : ?DateTimeImmutable {
        $date = mb_strtolower(trim($date));
        $now  = new DateTimeImmutable('now', $timeZone);

        //        echo '? --- ' . $date . PHP_EOL;

        // только сегодня
        if ($date === 'только что' || $date === 'сегодня' || $date === 'сейчас')
        {
            return $now;
        }

        // часы назад
        if (str_contains($date, 'час') && str_contains($date, 'назад'))
        {
            $numericTime = preg_replace('/\bчас\b/u', '1', $date);
            $hours       = preg_replace('/[^0-9]/u', '', $numericTime);

            return $now->sub(new DateInterval("PT{$hours}H"));
        }

        // сегодня или вчера
        if (str_contains($date, 'сегодня') || str_contains($date, 'вчера'))
        {
            $time = preg_replace('/[^0-9:]/u', '', $date);

            // время меньше двух символов это значит часы
            if (strlen($time) <= 2 && strlen($time) >= 1)
            {
                $time .= ':00';
            }

            if (empty($time))
            {
                $time = date('H:i');
            }

            if (str_contains($date, 'сегодня'))
            {
                return DateTimeImmutable::createFromFormat('H:i', $time, $timeZone);
            }
            elseif (str_contains($date, 'вчера'))
            {
                return DateTimeImmutable::createFromFormat('H:i', $time, $timeZone)->sub(new DateInterval("P1D"));
            }
        }

        // сложная дата
        preg_match('/\d{2}:\d{2}/', $date, $matches);
        preg_match('/\d+\s\D{3,}\s\d{4}/', $date, $matches2);
        preg_match('/[^\.]+\d+\s\D{3,}/', $date, $matches3);

        $timeStr        = $matches[0] ?? '';
        $dateStr        = $matches2[0] ?? '';
        $dayAndMonthStr = '';

        if (isset($matches3[0]) && strlen(trim($matches3[0])) > 6)
        {
            //            echo strlen($matches3[0]);
            $dayAndMonthStr = trim($matches3[0]);
        }

        //        print_r($matches);
        //        print_r($matches2);

        //        echo '$timeStr = ' . $timeStr . PHP_EOL;
        //        echo '$dateStr = ' . $dateStr . PHP_EOL;
        //        echo '$dayAndMonthStr = ' . $dayAndMonthStr . ' (' . strlen($dayAndMonthStr) . ')' . PHP_EOL;

        if (!empty($dateStr) || !empty($dayAndMonthStr))
        {
            $dateWithNumMonth = '';

            if ($dateStr)
            {
                $dateWithNumMonth = $this->getDateWithNumMonth($dateStr);
            }
            elseif ($dayAndMonthStr && empty($dateStr))
            {
                $dateWithNumMonth = $this->getDateWithNumMonth($dayAndMonthStr);
            }

            //            print_r($dateWithNumMonth);
            //            echo '---';
            //            die;

            if (!empty($dateWithNumMonth))
            {
                if (!empty($timeStr))
                {
                    $fullDate = $dateWithNumMonth . ' ' . trim($timeStr);

                    //                    echo '$fullDate = ' . $fullDate . ' (' . strlen($fullDate) . ')' . PHP_EOL;

                    if (strlen($fullDate) > 14)
                    {
                        return DateTimeImmutable::createFromFormat('d m Y H:i', $fullDate, $timeZone);
                    }
                }
                else
                {
                    return DateTimeImmutable::createFromFormat('d m Y', $dateWithNumMonth, $timeZone);
                }
            }
        }

        // поздравляю! Вы победили в конкурсе "самая оригинальная дата"
        // @bug no timeZone
        return $this->TimeParser->parse($date);
    }

    private function getDateWithNumMonth(string $date)
    : ?string {
        $replaceMonth = [
            'января'   => '01',
            'февраля'  => '02',
            'марта'    => '03',
            'апреля'   => '04',
            'мая'      => '05',
            'июня'     => '06',
            'июля'     => '07',
            'августа'  => '08',
            'сентября' => '09',
            'октября'  => '10',
            'ноября'   => '11',
            'декабря'  => '12',
            'янв'      => '01',
            'фев'      => '02',
            'мар'      => '03',
            'апр'      => '04',
            //            'мая'      => '05',
            'июн'      => '06',
            'июл'      => '07',
            'авг'      => '08',
            'сент'     => '09',
            'окт'      => '10',
            'ноя'      => '11',
            'дек'      => '12',
        ];

        // решаем вопрос с отсутствием года
        if (!preg_match('/\d{4}/', $date))
        {
            $date .= ' ' . date('Y');
        }

        $date = trim(str_ireplace(array_keys($replaceMonth), $replaceMonth, $date));

        return $date;
    }

    // геттер элементов HTML
    protected function getElementsDataFromHtml(string $html, string $containerSelector, string $elementSelector, string $get = 'html')
    : array {
        $fullSelector = trim($containerSelector . ' ' . $elementSelector);

        if (empty($fullSelector))
        {
            throw new Exception('Не установлен CSS-селектор!');
        }

        $this->showLog('getElementsDataFromHtml($html, "' . $containerSelector . '", "' . $elementSelector . '" ):', 'talkative');

        $data = [];

        $Crawler = new Crawler($html);

        $attribute = $this->getAttrFromSelector($elementSelector);

        //        var_dump($attribute);

        $elements = $Crawler->filter($fullSelector);

        if ($elements)
        {
            $elements->each(function (Crawler $element, $i) use (&$data, $get, $attribute) {
                //                echo PHP_EOL . '---' . PHP_EOL;
                //                //                print_r($get);
                //                print_r($element);
                //                echo PHP_EOL . '---' . PHP_EOL;
                if (!empty($attribute))
                {
                    //                    echo '------- attribute on ' . $attribute . PHP_EOL;
                    $data[] = $element->attr($attribute);
                }
                elseif ($get == 'html')
                {
                    //                    $data[] = $element->html();
                    $data[] = $element->outerHtml();
                }
                elseif ($get == 'text')
                {
                    $data[] = $element->text();
                }
            });
        }

        return $data;
    }

    // геттер элементов RSS
    protected function getElementsDataFromRss(string $xml, string $elementSelector, string $get = 'html', int $limit = -1, Crawler $Crawler = null)
    : array {
        $data = [];

        //        print_r($xml);
        //        die;

        if (empty($Crawler))
        {
            $Crawler = new Crawler($xml);
        }

        // CssSelectorConverter(true) - по умолчанию camelCase теги не сохраняются (все переводится в lower case)
        $Converter = new CssSelectorConverter(false);

        $attribute = $this->getAttrFromSelector($elementSelector);

        $elementSelector = $Converter->toXPath($elementSelector);
        //        var_dump($elementSelector);

        //        var_dump($elementSelector);
        //        var_dump($attribute);

        if ($limit > 0)
        {
            $elements = $Crawler->filterXPath($elementSelector)->slice(0, $limit);
        }
        else
        {
            $elements = $Crawler->filterXPath($elementSelector);
        }


        //        print_r($elements);
        //        die;

        //        return $elements;

        if ($elements)
        {
            $elements->each(function (Crawler $element, $i) use (&$data, $get, $attribute) {
                if (!empty($attribute))
                {
                    $data[] = $element->attr($attribute);
                }
                elseif ($get == 'node')
                {
                    $data[] = $element;
                }
                elseif ($get == 'html')
                {
                    $data[] = $element->outerHtml();
                }
                elseif ($get == 'text')
                {
                    //                    print_r($element->outerHtml());
                    //                    echo 'get text ';
                    $data[] = $element->text();
                }
            });
        }

        return $data;
    }

    /**
     * вытаскиваем атрибут из селектора (selector[attribute] => attribute)
     *
     * @param string $elementSelector
     *
     * @return string|null
     */
    protected function getAttrFromSelector(string $elementSelector)
    : ?string {
        preg_match('/\[([^\]]+)\]/', $elementSelector, $attrMatches);
        $attribute = '';

        if ($attrMatches)
        {
            //            $elementSelector = preg_replace('/\[[^\]]+\]/', '', $elementSelector);
            $attribute = $attrMatches[1];
        }

        return $attribute;
    }

    // возвращаем абсолютную ссылку
    private function getUrl(?string $url)
    : ?string {
        return ($url) ? UriResolver::resolve($url, $this->siteUrl) : null;
    }

    /**
     * Запрос страницы URL и возврат HTML
     *
     * @param string $url
     *
     * @return string || null
     */
    protected function getPage(string $url)
    : ?string {
        $this->currentUrl = $url;

        if (empty($url))
        {
            return null;
        }

        if (static::EMULATE_MODE)
        {
            return $this->getEmulateHtml($url);
        }

        $Curl = Helper::getCurl();

        if ('настройка curl')
        {
            if (!empty($this->config['site']['user_agent']))
            {
                if ($this->config['site']['user_agent'] === 'bot')
                {
                    $Curl->setOption(CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
                }
                else
                {
                    $Curl->setOption(CURLOPT_USERAGENT, $this->config['site']['user_agent']);
                }
            }
        }

        //        print_r($Curl);
        //        die;

        $responseHtml = $Curl->get($url);
        $responseInfo = $Curl->getInfo();

        // пост обработка
        if (!empty($responseHtml))
        {
            // контент получен
            if ($responseInfo['http_code'] >= 200 && $responseInfo['http_code'] < 300)
            {
                // решаем проблемы кодировки. Все должно быть переведено в utf-8
                $charset    = '';
                $charsetRaw = !empty($responseInfo['content_type']) ? $responseInfo['content_type'] : null;

                if (strpos($charsetRaw, 'charset=') !== false)
                {
                    $charset = str_replace("text/html; charset=", "", $charsetRaw);
                }
                else
                {
                    preg_match('/charset=([-a-z0-9_]+)/i', $responseHtml, $charsetMatches);

                    if (!empty($charsetMatches[1]))
                    {
                        $charset = trim($charsetMatches[1]);
                    }
                }

                $charset = strtolower($charset);

                // делаем перекодировку
                if (!empty($charset) && $charset !== 'utf-8')
                {
                    $responseHtml = mb_convert_encoding($responseHtml, 'utf-8', $charset);
                }

                // @FEAUTURE проверяем что в html нет браузерного редиректа
                // ...
            }
            else
            {
                // что-то пошло не так
                return null;
            }
        }

        //        echo '$charset = ' . $charset . PHP_EOL;
        //        print_r($responseInfo);
        //        echo $responseHtml;

        return $responseHtml;
    }

    // установка формата времени для HTML
    private function getDateFormat()
    : string
    {
        return $this->config['site']['date_format'] ?? 'd.m.Y H:i';
    }

    private function getTimeParser()
    {
        // https://github.com/Metallizzer/TimeParser
        return new TimeParser('russian');
    }

    // установка формата времени для RSS
    private function getDateFormatRss()
    : string
    {
        return $this->config['site']['date_format_rss'] ?? 'D, d M Y H:i:s O';
    }

    // установка временной зоны
    private function getTimeZone()
    : string
    {
        return $this->config['site']['time_zone'] ?? '+0300';
    }

    // установка лимита элементов
    private function getItemsLimit()
    : int
    {
        return $this->config['itemsLimit'] ?? self::MAX_ITEMS;
    }

    // установка URL сайта
    private function getSiteUrl()
    : string
    {
        $url = parse_url($this->config['site']['url']);

        $host = '';

        if (!empty($url['host']) && empty($url['scheme']))
        {
            $host = $url['host'];
        }
        elseif (empty($url['host']) && !empty($url['path']))
        {
            $host = $url['path'];
        }
        elseif (!empty($url['host']))
        {
            $host = $url['host'];
        }

        $scheme = $url['scheme'] ?? 'http';

        if (empty($host))
        {
            throw new Exception('Не указан домен');
        }

        return strtolower($scheme) . '://' . $host;
    }

    // установка режима парсера
    private function getMode()
    : string
    {
        $mode = $this->config['mode'] ?? '';

        if (in_array($mode, self::MODE_TYPES))
        {
            return $mode;
        }

        return 'desktop';
    }

    private static function getVersionArray(string $version)
    {
        $vParts = explode('.', $version);

        if (!isset($vParts[0]))
        {
            $vParts[0] = 1;
        }

        if (!isset($vParts[1]))
        {
            $vParts[1] = 0;
        }

        if (!isset($vParts[2]))
        {
            $vParts[2] = 0;
        }

        return $vParts;
    }

    private function keysIsNotEmptyInAnotherArray(array $arrayOfRequiredKeys, array $arrayTarget, $path = null)
    {
        foreach ($arrayOfRequiredKeys as $k => $v)
        {
            if (!is_array($v))
            {
                $keys    = explode('->', $path . '->' . $v);
                $keys    = array_splice($keys, 1);
                $keyPath = '';

                foreach ($keys as $i => $key)
                {
                    $keyPath .= '[\'' . $key . '\']';
                }

                if (empty($this->getValFromKeyChain($arrayTarget, $keys)))
                {
                    throw new Exception('<Parser>' . $keyPath . ' - должен быть указан');
                }
            }
            else
            {
                $this->keysIsNotEmptyInAnotherArray($v, $arrayTarget, $path . '->' . $k);
            }
        }
    }

    /**
     *
     * Берем элемент массива по ключу из массива ключей
     * getValFromKeyChain($arrayTarget, ['a', 'b', 'c']) => return $arrayTarget['a']['b']['c']
     *
     *
     * @param $arrayTarget
     * @param $keyChain
     *
     * @return mixed|null
     *
     */
    private function getValFromKeyChain($arrayTarget, $keyChain)
    {
        $level = $arrayTarget;
        for ($i = 0; $i < count($keyChain); $i++)
        {
            if (isset($level[$keyChain[$i]]))
            {
                $level = $level[$keyChain[$i]];
            }
            else
            {
                return null;
            }
        }

        return $level;
    }

    // вывод инфы в лог (когда включен режим DEBUG)
    private function showLog(string $message, string $mode = 'default', $break = true, bool $showOnce = false)
    {
        // не получилось сделать так, чтобы showLog вызывался бы один раз из __construct
        //        $messageID = date('U');
        //
        //        //        $Parent = get_parent_class($this);
        //        var_dump(is_subclass_of($this, 'ParseCore'));
        //
        //        //        echo $Parent
        //        if (isset($Parent->store))
        //        {
        //            // проверяем что уже показывали данное сообщение
        //            if (isset($Parent->store['showLog'][$messageID]) && $showOnce === true)
        //            {
        //                return;
        //            }
        //
        //            if (!isset($Parent->store['showLog'][$messageID]))
        //            {
        //                $Parent->store['showLog'][$messageID] = $message;
        //            }
        //        }


        $maxLen = 1000;

        if (static::DEBUG)
        {
            // определен дебаг режим (шаблон не имеет данной настройки)
            if (defined('static::DEBUG_MODE'))
            {
                if ($mode == 'talkative' && static::DEBUG_MODE !== 'talkative')
                {
                    return;
                }
            }

            if (strlen($message) > $maxLen)
            {
                echo substr($message, 0, $maxLen) . PHP_EOL . '[...лог обрезан...]';
            }
            else
            {
                echo $message;
            }

            if ($break)
            {
                echo PHP_EOL;
            }
        }
        //        print_r($this->store);
    }

    // для эмуляции http-запросов к URL
    public function getEmulateHtml(string $url)
    : ?string {
        $emulateData  = [];
        $fileWithData = self::WORK_DIR . 'emulateHtml.php';

        if (file_exists($fileWithData))
        {
            $emulateData = include($fileWithData);
        }

        return $emulateData[$url] ?? null;
    }
}