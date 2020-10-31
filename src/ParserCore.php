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

namespace fingli\ParserCore;

use app\components\parser\NewsPostItem;
use app\components\parser\NewsPost;
use app\components\Helper;

use DateTime;
use DateTimeZone;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\UriResolver;
use yii\base\Exception;
use DateTimeImmutable;
use DateInterval;
use wapmorgan\TimeParser\TimeParser;

/**
 * # Ядро для парсеров
 *
 * (с) "Не парься! Просто парси!"
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
 * ## Схема работы ядра
 *
 * - Запуск ядра и интегрирование настроек из файла парсера
 * - Получение списка элементов (новостей) из витрины. Это может быть RSS или HTML витрина в зависимости от указанного режима парсинга $config[mode]
 * - Получение и обработка полей элемента: title, link, (description, image)*- если есть
 * - Формирование списка URL элементов для парсинга
 * - Проход по каждому элементу из списка  URL и парсинг на основе CSS-селекторов, указанных в $config[element]
 * - Запись всех этих данных во внутренний формат парсера
 * - Проверка и обработка полученных данных (работа по компоновке, установка значений по умолчанию и т.д.)
 * - Перевод данных из внутреннего формата в формат клиента
 * - Возврат клиенту результата
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
    private const VERSION = '1.3.6';
    // доступные режимы работы парсера
    private const  MODE_TYPES = ['desktop', 'rss'];
    // путь до папки со вспомогательными файлами
    private const WORK_DIR = __DIR__ . '/assets/';
    // лимит на кол-во элементов по умолчанию
    private const MAX_ITEMS = 10;
    // максимальный размер дескрипшена
    private const MAX_DESCRIPTION_LENGTH = 200;
    // лимит на кол-во элементов
    protected int $itemsLimit = self::MAX_ITEMS;
    // @todo
    // лимит передаваемый из вне, когда определена конста CORE_PARSER_DEBUG_EXTERNAL
    //    public int $itemsLimitChecker;
    // хранение текущей кодировки сайта
    protected string $currentCharset;
    // инфа о запросе
    private $responseInfo;
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
    protected array $itemsData = [];
    private int $pauseBeforeRequest;
    private const TYPE_HEADER = 'header';
    private const TYPE_TEXT   = 'text';
    private const TYPE_IMAGE  = 'image';
    private const TYPE_QUOTE  = 'quote';
    private const TYPE_LINK   = 'link';
    //private const TYPE_AUDIO = 'audio';
    private const TYPE_VIDEO = 'video';
    // URL который обрабатывается сейчас
    private string $currentUrl;
    // элемент который обрабатывается сейчас
    private string $currentElement = 'none';
    // здесь хранится полный html из ->getPage
    private string $currentPageFullHtml;
    // переменная для хранения режима дебаг
    private int $debug;
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
        'q',
    ];
    // стоп-слова (которые вырезаем) из даты
    protected array $dateStopWords = [
        'понедельник',
        'вторник',
        'среда',
        'четверг',
        'пятница',
        'суббота',
        'воскресенье',
        'пн',
        'вт',
        'ср',
        'чт',
        'пт',
        'сб',
        'вс',
    ];
    // конфигурация для конкретного экземпляра
    public array $config = [
        // режимы работы парсера:
        // rss - RSS витрина
        // desktop - обычный сайт HTML
        'mode'       => 'desktop',

        // максимальное количество новостей, берушихся с витрины
        // ИСПОЛЬЗУЕТСЯ ТОЛЬКО В РЕЖИМЕ DEBUG
        // в остальных случаях жестко задается ядром
        //
        // не забывайте отключать лимит при сдаче парсера!
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

            // пауза между запросами в секундах (включается только, если сайт начинает блокировку)
            'pause'           => 0,
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
        // *** в CSS-селекторах можно указывать несколько селекторов через запятую (например, если сайт имеет несколько шаблонов карточки новости)
        'element'    => [

            // css-селектор для контейнера карточки
            // (все дальнейшие пути строятся относительно этого контейнера)
            // (обязательный)
            'container'           => '',

            // css-селектор для основного текста
            // (для заполнения модели NewsPostItem)
            // (обязательный)
            'element-text'        => '',

            // css-селектор для получения даты создания новости
            // (заполняется только, если отсутствует в витрине)
            'element-date'        => '',

            // css селектор для получения картинки
            // !должен содержать конечный аттрибут src! (например: img.main-image[src])
            // (заполняется только, если отсутствует в витрине)
            'element-image'       => '',

            // css-селектор для цитаты
            // (если не заполнено, то по умолчанию ищутся теги: <blockquote> и <q>)
            // (опционально)
            'element-quote'       => '',

            // игнорируемые css-селекторы (будут вырезаться из результата)
            // (можно несколько через запятую)
            // (опционально)
            'ignore-selectors'    => '',

            // css-селекторы которые будут вставлятся в начало текста новости element-text (селекторы ищутся от корня, т.е. не зависят от container)
            // (опционально)
            'element-text-before' => '',

            // css-селекторы которые будут вставлятся в конец текста новости element-text (селекторы ищутся от корня, т.е. не зависят от container)
            // (опционально)
            'element-text-after'  => '',
        ]
    ];

    public function __construct()
    {
        // инициализация переменных
        $this->debug              = $this->getDebug();
        $this->siteUrl            = $this->getSiteUrl();
        $this->mode               = $this->getMode();
        $this->itemsLimit         = $this->getItemsLimit();
        $this->timeZone           = $this->getTimeZone();
        $this->dateFormat         = $this->getDateFormat();
        $this->dateFormatRss      = $this->getDateFormatRss();
        $this->TimeParser         = $this->getTimeParser();
        $this->pauseBeforeRequest = $this->getPauseBeforeRequest();

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

        // опциональные свойства элемента (date, description) должны быть заполнены хотя бы в одном месте
        if ($this->mode === 'rss')
        {
            // @deprecated дескрипшен возьмем из тайтла
            //            if (
            //                empty($this->config['rss']['element-description']) &&
            //                empty($this->config['element']['element-description'])
            //            )
            //            {
            //                throw new Exception("Необходимо заполнить element-description или в витрине, или в карточке");
            //            }

            // дата
            if (
                empty($this->config['rss']['element-date']) &&
                empty($this->config['element']['element-date'])
            )
            {
                throw new Exception("Необходимо заполнить element-date или в витрине, или в карточке");
            }
        }
        else
        {
            // дата
            if (
                empty($this->config['list']['element-date']) &&
                empty($this->config['element']['element-date'])
            )
            {
                throw new Exception("Необходимо заполнить element-date или в витрине, или в карточке");
            }
        }

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

        if (defined('static::EMULATE_MODE') && static::EMULATE_MODE)
        {
            static::showLog('--- Внимание! Включен режим эмуляции http запросов. Реальные запросы не делаются ---', 'warning', true, true);
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
            ' Старт парсера ' . static::class . PHP_EOL .
            ' на ядре ' . self::VERSION . ' [Режим: ' . $this->mode . '; Макс. новостей: ' . $this->itemsLimit . ']' . PHP_EOL .
            ' Время старта: ' . date('d.m.Y H:i:s') . '; Час. пояс ' . $this->timeZone . '' . PHP_EOL .
            '--------------------------------------------------------------------');

        static::showLog(PHP_EOL . '----------------------------------');
        static::showLog(' Запрашиваем витрину ' . (($this->mode == 'rss') ? 'RSS' : 'HTML'));
        static::showLog('----------------------------------');

        $vitrinaSelector = '';

        if ($this->mode == 'rss')
        {
            $listPageUrl          = $this->getUrl($this->config['rss']['url']);
            $vitrinaElSelector    = $this->config['rss']['element'];
            $this->currentElement = 'rss';
        }
        else
        {
            $listPageUrl          = $this->getUrl($this->config['list']['url']);
            $vitrinaSelector      = $this->config['list']['container'];
            $vitrinaElSelector    = $this->config['list']['element'];
            $this->currentElement = 'list';
        }

        static::showLog('-- ' . $listPageUrl);

        $listPageData = self::getPage($listPageUrl);

        if (empty($listPageData))
        {
            throw new Exception('Не удалось получить контент витрины');
        }


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

        $itemsParsed = [];

        if (!empty($itemUrls))
        {
            foreach ($itemUrls as $itemUrl)
            {
                static::showLog(PHP_EOL . '- запрашиваем карточку ' . $itemUrl);
                $itemsParsed[$itemUrl] = $this->getCard($itemUrl);
            }
        }

        if (empty($itemsParsed))
        {
            throw new Exception('Не удалось распарсить карточки. (Пустой результат $itemsParsed)');
        }

        if ($this->debug >= 3)
        {
            echo "------ itemsParsed (before normalize) -----\033[44m" . PHP_EOL;
            print_r($itemsParsed);
            echo "------ / itemsParsed (before normalize) -----\033[44m" . PHP_EOL;
        }

        static::showLog(PHP_EOL . '----------------------------------');
        static::showLog('  нормализация данных (объединение одинаковых)...');
        static::showLog('----------------------------------');

        $itemsParsed = $this->normalizeItems($itemsParsed);
        static::showLog('Сделано');

        if ($this->debug >= 3)
        {
            echo "------ itemsParsed (normalized) -----\033[44m" . PHP_EOL;
            print_r($itemsParsed);
            echo "------ / itemsParsed (normalized) -----\033[44m" . PHP_EOL;
        }

        static::showLog(PHP_EOL . '----------------------------------');
        static::showLog('  перевод данных в формат клиента...');
        static::showLog('----------------------------------');

        $posts = $this->getAdaptiveToParser1500($itemsParsed);
        static::showLog('Сделано');

        static::showLog(PHP_EOL . '--------------------------------------------------------------------', 'success');
        static::showLog(' Заканчиваем работу парсера. Создано: ' . count($posts), 'success');
        static::showLog('--------------------------------------------------------------------' . PHP_EOL, 'success');

        return $posts;
    }

    protected function normalizeItems(array $items)
    : array {
        $itemsNorm = [];

        if ($items)
        {
            foreach ($items as $url => $item)
            {
                if (!empty($item['data']))
                {
                    $item['data'] = $this->normalizeItemData($item['data']);
                }

                $itemsNorm[$url] = $item;
            }

            $items = $itemsNorm;
        }

        return $items;
    }

    /**
     *
     * Нормализация данных из текста новости
     *
     * @param array $data
     *
     * @return array
     */
    protected function normalizeItemData(array $data)
    : array {
        $dataNew = [];

        if (!empty($data))
        {
            $acumulator = [];

            for ($i = 0; $i < count($data); $i++)
            {
                $cur      = $data[$i];
                $curType  = $cur['type'];
                $next     = isset($data[$i + 1]) ? $data[$i + 1] : null;
                $nextType = null;

                if ($next)
                {
                    $nextType = $next['type'];
                }

                // если следующий тип такой же, то акумулируем
                // только для текста
                if ($nextType !== null && $curType === $nextType && $curType === 'text')
                {
                    $acumulator[] = $cur;
                }
                // иначе сразу пишем
                else
                {
                    // если есть акумулятор, то его записываем
                    if (!empty($acumulator))
                    {
                        // мержим текст в один
                        $acumMerged = [
                            'type' => 'text',
                            'text' => '',
                            'tag'  => '#text'
                        ];

                        foreach ($acumulator as $acumData)
                        {
                            $acumMerged['text'] .= ' ' . $acumData['text'];
                        }

                        $acumMerged['text'] .= ' ' . $cur['text'];

                        $dataNew[] = $acumMerged;

                        // очищяем акумулятор
                        $acumulator = [];
                    }
                    // если нет, то просто элемент
                    else
                    {
                        $dataNew[] = $cur;
                    }
                }
            }

            if (!empty($dataNew))
            {
                $data = $dataNew;
            }
        }

        return $data;
    }

    /**
     *
     * Адаптер для перехода из внутреннего формата в формат клиента
     *
     * NewsPost
     *
     * @param int         $type        PostItemType
     * @param string|null $text        text item
     * @param string|null $image       url to image
     * @param string|null $link        url external link
     * @param int|null    $headerLevel header level for type HEADER
     * @param string|null $youtubeId   video youtube id
     *
     * @property string   parser
     * @property string   title
     * @property string   description
     * @property DateTime createDate
     * @property string   original
     * @property ?string  image
     * @property array    items
     *
     * NewsPostItem
     *
     */
    // $this->items - то что собрали с витрины
    // $cards - то что собрали с карточек
    protected function getAdaptiveToParser1500(array $cards)
    : array {
        if (empty($cards))
        {
            throw new Exception('Нет данных из карточек новостей для адаптации');
        }

        $posts = [];

        if ($this->items)
        {
            foreach ($this->items as $url => $item)
            {
                $listItem = $item;
                $cardItem = $cards[$url] ?? null;

                $title       = '';
                $date        = '';
                $image       = '';
                $description = '';

                // мержим значения

                // description
                if (isset($listItem['description']))
                {
                    $listItem['description'] = $this->stripText($listItem['description']) ?? '';
                }

                if (isset($cardItem['description']))
                {
                    $cardItem['description'] = $this->stripText($cardItem['description']) ?? '';
                }

                if (!empty($listItem['description']))
                {
                    $description = $listItem['description'];
                }
                elseif (!empty($cardItem['description']))
                {
                    $description = $cardItem['description'];
                }

                $description = $this->substrMax($description, self::MAX_DESCRIPTION_LENGTH);

                // title
                if (!empty($listItem['title']))
                {
                    $title = $listItem['title'];
                }
                elseif (!empty($description))
                {
                    $title = $description;
                }

                if (empty($description))
                {
                    $description = $title;
                }

                // date
                if (!empty($listItem['date']))
                {
                    $date = $listItem['date'];
                }
                elseif (!empty($cardItem['date']))
                {
                    $date = $cardItem['date'];
                }

                // image
                if (!empty($cardItem['image']))
                {
                    $image = $cardItem['image'];
                }
                elseif (!empty($listItem['image']))
                {
                    $image = $listItem['image'];
                }


                // Create Post
                $Post = new NewsPost(
                    static::class,
                    $title,
                    $description,
                    $date,
                    $url,
                    $image
                );

                // add data to post
                if (!empty($cardItem['data']))
                {
                    $i = 1;

                    $images = [];

                    if (!empty($image))
                    {
                        array_push($images, $image);
                    }

                    foreach ($cardItem['data'] as $data)
                    {
                        switch ($data['type'])
                        {
                            case 'header':
                                if (!empty($data['text']))
                                {
                                    $level = substr($data['tag'], 1, 1);

                                    if (!$level)
                                    {
                                        $level = 1;
                                    }

                                    // проверяем что нет дубля с названием
                                    if ($data['text'] == $title && $i == 1)
                                    {
                                        break;
                                    }

                                    // пропускаем заголовок, если он такой же как дескрипшен
                                    if ($data['text'] === $description)
                                    {
                                        break;
                                    }

                                    $Post->addItem(
                                        new NewsPostItem(
                                            NewsPostItem::TYPE_HEADER,
                                            $data['text'],
                                            null,
                                            null,
                                            $level,
                                            null
                                        ));
                                }
                                break;

                            case 'link':
                                if (!empty($data['url']))
                                {
                                    $Post->addItem(
                                        new NewsPostItem(
                                            NewsPostItem::TYPE_LINK,
                                            $data['text'],
                                            null,
                                            $data['url'],
                                            null,
                                            null
                                        ));
                                }
                                break;

                            case 'quote':
                                if (!empty($data['text']))
                                {
                                    $Post->addItem(
                                        new NewsPostItem(
                                            NewsPostItem::TYPE_QUOTE,
                                            $data['text'],
                                            null,
                                            null,
                                            null,
                                            null
                                        ));
                                }
                                break;

                            case 'text':
                                // вырезаем текст меньше 4 символов длиной, если он содержит ТОЛЬКО [.,\s?!]
                                if (
                                    (strlen($data['text']) <= 4 && !preg_match('/[^\s.,\?\!]+/', $data['text'])) ||
                                    empty(trim($data['text']))
                                )
                                {
                                    break;
                                }

                                // пропускаем текст, если он такой же как дескрипшен
                                if ($data['text'] === $description)
                                {
                                    break;
                                }

                                $Post->addItem(
                                    new NewsPostItem(
                                        NewsPostItem::TYPE_TEXT,
                                        $data['text'],
                                        null,
                                        null,
                                        null,
                                        null
                                    ));
                                break;

                            case 'video':
                                if (!empty($data['value']))
                                {
                                    $Post->addItem(
                                        new NewsPostItem(
                                            NewsPostItem::TYPE_VIDEO,
                                            null,
                                            null,
                                            null,
                                            null,
                                            $data['value']
                                        ));
                                }
                                break;

                            case 'image':
                                if (!empty($data['url']))
                                {
                                    // проверяем на дубли
                                    if (in_array($data['url'], $images))
                                    {
                                        break;
                                    }
                                    else
                                    {
                                        array_push($images, $data['url']);
                                    }

                                    $Post->addItem(
                                        new NewsPostItem(
                                            NewsPostItem::TYPE_IMAGE,
                                            $data['text'],
                                            $data['url'],
                                            null,
                                            null,
                                            null
                                        ));
                                }
                                break;
                        }

                        $i++;
                    }
                }

                $posts[] = $Post;
            }
        }

        return $posts;
    }


    /**
     *
     * Обрезаем $string до последнего из след. символов: \s.,?!
     * результат должен быть меньше $max символов
     *
     * @param string $string
     * @param int    $max
     *
     * @return string
     */
    // @todo нужно тестирование
    private function substrMax(string $string, int $max = 200)
    : string {
        if (empty($string))
        {
            return '';
        }

        $len = strlen($string);

        if ($len > $max)
        {
            $stringStripped = substr($string, 0, $max);

            preg_match_all('/[\s.,?!]/', $stringStripped, $matches, PREG_OFFSET_CAPTURE);

            //            $pos = isset($matches[0][1]) ? $matches[0][1] : $max;

            $pos = 0;

            if (isset($matches[0]) && is_array($matches[0]))
            {
                foreach ($matches[0] as $match)
                {
                    if (!empty($match[1]))
                    {
                        $pos = $match[1] > $pos ? $match[1] : $pos;
                    }
                }
            }

            return substr($stringStripped, 0, $pos + 1);
        }

        return $string;
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
        $this->currentElement = 'element';
        $item                 = [];
        $html                 = $this->getPage($url);

        if (!empty($html))
        {
            $elDescription = '';
            $elImage       = '';
            $elDate        = '';

            $containerData = current($this->getElementsDataFromHtml($html, '', $this->config['element']['container']));

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

                if (!empty($this->config['element']['element-date']))
                {
                    static::showLog('--- date: ' . $elDate);
                }

                if (!empty($this->config['element']['element-image']))
                {
                    static::showLog('--- image: ' . $elImage);
                }

                if (!empty($this->config['element']['element-description']))
                {
                    static::showLog('--- description: ' . $elDescription);
                }

                if (empty($elTextData))
                {
                    throw new Exception('Не получен element-text="' . (!empty($this->config['element']['element-text']) ? $this->config['element']['element-text'] : '') . '"  Установите настройки парсера config[element][element-text]');
                }


                static::showLog('-- начинаем подготовку текста новости...');

                $elTextHtml = $this->getCardTextHtml($elTextData);

                static::showLog('-- начинаем разбор текста новости во внутренний формат itemData...');

                // куски текста (массив для NewsPostItem)
                $elItemData = $this->getItemData($elTextHtml);

                return [
                    'description' => $elDescription,
                    'image'       => $elImage,
                    'date'        => $elDate,
                    'data'        => $elItemData,
                ];
            }
            else
            {
                static::showLog('-- Не найдены данные из контейнера карточки ' . $this->config['element']['container'] . '', 'warning');
            }
        }
        elseif ($this->debug >= 1)
        {
            static::showLog('Страница вернула пустой результат', 'warning');
        }


        return $item;
    }

    // вырезание игнорируемых CSS-селекторов из ignore-selectors
    protected function getHtmlWithoutIgnoredSelectors(string $html)
    : string {
        $selectors = $this->config['element']['ignore-selectors'] ?? '';

        if ($selectors)
        {
            $selectors = explode(',', $selectors);

            foreach ($selectors as $selector)
            {
                $html = $this->replaceNodeFromHtml($html, trim($selector));
            }
        }

        return $html;
    }

    /**
     * Заменяем в тексте element-quote CSS-селектор на <blockquote>
     *
     * @param string $html
     *
     * @return string
     */
    protected function getHtmlWithSubstitutedQuotes(string $html)
    : string {
        $selector = $this->config['element']['element-quote'] ?? '';

        if (strlen($selector) > 1)
        {
            $html = $this->replaceNodeFromHtml($html, $selector, 'blockquote');
        }

        return $html;
    }

    /**
     *  Подменяем CSS-селектор на просто тег (пока без поддержки классов)
     *  Также имеет режим удаления, если не указать $toTag
     *
     *  Пример:
     *  $this->replaceNodeFromHtml($html, 'div.blockquote', 'blockquote');
     *
     *  <div class="blockquote">bla-bla-bla</div> ===> <blockquote>bla-bla-bla</blockquote>
     *
     *
     * @param string      $html
     * @param string      $sourceSelector
     * @param string|null $toTag
     * @param string      $mode
     *
     * @return string
     */
    private function replaceNodeFromHtml(string $html, string $sourceSelector, ?string $toTag = null, string $mode = 'substitute')
    : string {
        $Crawler = new Crawler($html);

        $substitutions = [];

        $Crawler->filter($sourceSelector)->each(function (Crawler $element, $i) use (&$substitutions, $toTag, $mode) {
            $outerHtml = $element->outerHtml();


            if ($mode == 'substitute' && !empty($toTag))
            {
                $htmlNew = '<' . $toTag . '>' . $element->html() . '</' . $toTag . '>';
            }
            else
            {
                $htmlNew = '';
            }


            $substitutions[] = [
                'source' => $outerHtml,
                'to'     => $htmlNew
            ];
        });

        if ($substitutions)
        {
            foreach ($substitutions as $substitution)
            {
                $html = str_replace($substitution['source'], $substitution['to'], $html);
            }
        }

        return $html;
    }

    /**
     *
     * Вставка селекторов в текст
     *
     * Вставляем в начало текста новости элементы из селекторов element-text-before
     * Вставляем в конец текста новости элементы из селекторов element-text-after
     *
     * @param string $html - html текст новости element-text
     *
     * @return string
     */
    protected function getHtmlWithInsertedSelectors(string $html)
    : string {
        $htmlPrepended = '';
        $htmlAppended  = '';

        if (!empty($this->currentPageFullHtml))
        {
            // перед текстом
            if (!empty($this->config['element']['element-text-before']))
            {
                // найдем нужные селекторы в полном html страницы
                $elements = $this->getElementsDataFromHtml($this->currentPageFullHtml, '', $this->config['element']['element-text-before'], 'html');

                if (!empty($elements))
                {
                    foreach ($elements as $elementHtml)
                    {
                        $htmlPrepended .= $elementHtml;
                    }
                }

                $htmlPrepended .= ' ';
            }

            // в конец текст
            if (!empty($this->config['element']['element-text-after']))
            {
                // найдем нужные селекторы в полном html страницы
                $elements = $this->getElementsDataFromHtml($this->currentPageFullHtml, '', $this->config['element']['element-text-after'], 'html');

                if (!empty($elements))
                {
                    foreach ($elements as $elementHtml)
                    {
                        $htmlAppended .= $elementHtml;
                    }
                }

                $htmlAppended = ' ' . $htmlAppended;
            }
        }

        $html = $htmlPrepended . $html . $htmlAppended;

        return $html;
    }

    /**
     * Подготовка html текста новости element-text для разбора
     * Вырезаем все теги кроме TYPE_IMAGE, TYPE_QUOTE, TYPE_LINK, TYPE_VIDEO
     *
     * @param string $html - подготовленный html без лишнего
     *
     * @return string
     */
    protected function getCardTextHtml(string $html)
    : string {
        // добавляем css-селекторы в начало и/или в конец текста
        $html = $this->getHtmlWithInsertedSelectors($html);

        // вырезаем игнорируемые теги
        $html = $this->getHtmlWithoutIgnoredSelectors($html);

        // подменяем цитаты
        $html = $this->getHtmlWithSubstitutedQuotes($html);

        // коррекция тегов для правильной обрезки
        // (в частности нужно добавить пробелы перед <td> и <li>, чтобы текст не сливался
        $html = str_replace('<td>', '<td> ', $html);
        $html = str_replace('<li>', '<li> ', $html);

        // вырезаем все ненужные теги, кроме разрешенных в allowedTags
        $html = strip_tags($html, $this->allowedTags);

        return $html;
    }

    /**
     * Разбор текста новости на куски
     *
     * @param string $html
     *
     * @return array
     */
    protected function getItemData(string $html)
    : array {
        if ($this->debug >= 2)
        {
            echo "------ RAW HTML getItemData() -----\033[44m" . PHP_EOL;
            echo $html;
            echo PHP_EOL . "\033[0m------ / RAW HTML-----" . PHP_EOL;
        }


        $itemData = [];

        // crawler сам дополняет код <html> и <body>
        //        $Crawler = new Crawler($html);
        $Crawler = new Crawler('<fingli>' . $html . '</fingli>');

        //        $elements = $Crawler->filterXPath('//body/text() | //body//*');
        //        $elements = $Crawler->filterXPath('//body//text() | //body//*');
        //        $elements = $Crawler->filterXPath('//body//node()');
        $elements = $Crawler->filterXPath('//body/fingli/node()');

        if (!count($elements))
        {
            return [];
        }

        foreach ($elements as $element)
        {
            $tagName = '';
            $val     = '';
            $text    = '';
            $imgUrl  = '';
            $data    = [];

            $tagName = !empty($element->nodeName) ? $element->nodeName : '#text';
            $val     = $this->stripText($element->nodeValue);
            $text    = $this->stripText($element->textContent);

            // обработка на основе тега
            switch ($tagName)
            {
                // просто текст
                case 'p':
                case '#text':
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
                    $imgUrl = $this->getUrl($element->getAttribute('src'));

                    // в src бывают встроенные картинки - фиксим
                    // нашли встроенное
                    if (strpos($imgUrl, 'data:image/') === 0)
                    {
                        // пробуем взять другой аттрибут (data-src)
                        $imgUrl2 = $this->getUrl($element->getAttribute('data-src'));

                        if (!empty($imgUrl2))
                        {
                            $imgUrl = $imgUrl2;
                        }
                        else
                        {
                            // @todo сделать поиск на  корректный URL по всему тегу
                            $imgUrl = '';
                        }
                    }

                    if (!empty($imgUrl))
                    {
                        $data = [
                            'type' => self::TYPE_IMAGE,
                            'text' => $element->getAttribute('alt'),
                            'url'  => $imgUrl,
                            'tag'  => $tagName,
                        ];
                    }
                    break;

                case 'iframe':
                case 'video':
                    $src = '';

                    if ($tagName == 'iframe')
                    {
                        $src = $element->getAttribute('src');
                    }
                    elseif ($tagName == 'video')
                    {
                        $source = $element->getElementsByTagName('source')->item(0);

                        if ($source)
                        {
                            $src = $source->getAttribute('src');
                        }
                    }

                    if (!empty($src))
                    {
                        $data = [
                            'type'  => self::TYPE_VIDEO,
                            'tag'   => $tagName,
                            'value' => static::getYoutubeIdFromUrl($src),
                        ];
                    }
                    break;

                case 'blockquote':
                case 'q':
                    $data = [
                        'type' => self::TYPE_QUOTE,
                        'tag'  => $tagName,
                        'text' => $text,
                    ];
                    break;

                case 'a':
                    $youtubeId = '';
                    $url       = $this->getUrl($element->getAttribute('href'));

                    if (empty($url))
                    {
                        break;
                    }

                    // берем только родные ссылки. Внешние игнорим
                    // это внешние ссылки
                    if (strpos($url, $this->siteUrl) === false)
                    {
                        // внешнюю оставляем только ссылку на ютуб
                        if ($youtubeId = $this::getYoutubeIdFromUrl($url))
                        {
                        }
                        else
                        {
                            // но оставляем текст из ссылки, как текст
                            if (!empty($val))
                            {
                                $data = [
                                    'type' => self::TYPE_TEXT,
                                    'text' => $val,
                                    'tag'  => $tagName,
                                ];
                            }

                            break;
                        }
                    }

                    // для обработки смердженных типов, аля <a href="https://youtu.be/2jzecQ0W1cQ">Ссылка на ютуб</a>
                    if (!empty($youtubeId))
                    {
                        $data = [
                            'type'  => self::TYPE_VIDEO,
                            'tag'   => $tagName,
                            'value' => $youtubeId,
                        ];
                    }
                    else
                    {
                        // если в тексте ссылки содержатся allowed tags
                        // нужно их обработать
                        if ($element->childNodes->length >= 2)
                        {
                            $nodeText = '';

                            foreach ($element->childNodes as $node)
                            {
                                if (isset($node->nodeName))
                                {
                                    // берем альт картинки
                                    if ($node->nodeName == 'img')
                                    {
                                        $nodeText .= $this->getUrl($node->getAttribute('src')) . ' ';
                                    }
                                    elseif (in_array($node->nodeName, $this->allowedTags))
                                    {
                                        $nodeText .= $node->textContent . ' ';
                                    }
                                    elseif ($node->nodeName == '#text')
                                    {
                                        $nodeText .= $node->textContent . ' ';
                                    }
                                }
                            }


                            // делаем ссылку у которой текст = URL внутренней картинки и/или текст
                            if (!empty($nodeText) && !empty($url))
                            {
                                $data = [
                                    'type' => self::TYPE_LINK,
                                    'tag'  => $tagName,
                                    'text' => $nodeText,
                                    'url'  => $url
                                ];
                            }
                        }
                        else
                        {
                            // сохраняем как обычную ссылку
                            $data = [
                                'type' => self::TYPE_LINK,
                                'tag'  => $tagName,
                                'text' => $text,
                                'url'  => $url
                            ];
                        }
                    }
                    break;
            }

            if (!empty($data))
            {
                $itemData[] = $data;
            }
        }

        return $itemData;
    }


    /**
     * Получение YoutubeID из ссылки ютуба
     *
     * @param string $url
     *
     * @return string|null
     */
    // @todo потестить
    protected
    static function getYoutubeIdFromUrl(string $url
    )
    : ?string {
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i';

        if (preg_match($pattern, $url, $match))
        {
            return $match[1];
        }

        return '';
    }

    protected
    function stripText(string $text
    )
    : string {
        return trim($text);
    }

    // переводим в формат Гринвича
    private function getCorrectedDateToGrinvich(DateTimeImmutable $dateTime)
    : ?string {
        $dateTime2 = new DateTime(null, new DateTimeZone('UTC'));
        $dateTime2->setTimestamp($dateTime->getTimestamp());

        return $dateTime2->format('Y-m-d H:i:s');
    }

    /**
     * Парсинг даты (числовой или строковый формат)
     *
     * @param string $date
     *
     * @return string|null
     * @throws \Exception
     */
    // @todo потестить
    protected
    function getDate(string $date
    )
    : ?string {
        if (empty($date))
        {
            if ($this->debug >= 1)
            {
                static::showLog('Дата пустая!', 'warning');
            }

            return '';
        }

        $timeZone = new DateTimeZone($this->timeZone);

        if ($this->currentElement == 'rss')
        {
            $dateTime = DateTimeImmutable::createFromFormat($this->dateFormatRss, $date, $timeZone);

            return $this->getCorrectedDateToGrinvich($dateTime);
        }

        // убираем теги A, которые скорее всего содержат ненужную инфу (div)
        $date = preg_replace('~<a(.*?)</a>~Usi', "", $date);

        // вырезаем лишние теги
        $date = strip_tags($date);

        // убираем entities
        $date = html_entity_decode($date);

        // убираем ненужные слова
        $replacedWords = [
            ' г.',
            ' г.',
            ' год',
            ' год',
            ' года',
            ' года',
        ];

        $date = str_replace(' ', ' ', $date);
        $date = str_replace($replacedWords, '', $date);

        // вырезаем лишние символы
        if ($this->currentElement != 'rss')
        {
            $date = preg_replace('~[/\\\\-]~', '.', $date);
            $date = str_replace(',', '', $date);
        }

        // к нижнему регистру
        $date = mb_strtolower($date);

        // вырезаем стоп-слова
        if (!empty($this->dateStopWords))
        {
            foreach ($this->dateStopWords as $stopWord)
            {
                $date = str_replace($stopWord, '', $date);
            }
        }

        $date = trim($date);

        // вырезаем безхозные точки
        $date = $date . ' ';
        $date = preg_replace('/([^\S])(?<dot>\.)[^\S]/', '', $date);

        // есть текст
        if (preg_match('/[\p{Cyrillic}]+/u', $date))
        {
            $dateTime = $this->getDateFromText($date, $timeZone);
        }
        // нет текста
        else
        {
            $dateTime = $this->getDateFromDate($date, $timeZone);
        }

        if (!empty($dateTime))
        {
            if (1 && 'переводим в формат Гринвича')
            {
                return $this->getCorrectedDateToGrinvich($dateTime);
            }
            else
            {
                return $dateTime->format('Y-m-d H:i:s');
            }
        }

        return '';
    }

    /**
     *  Дата из даты
     *
     * @param string $date
     *
     * @return string || null
     *
     */
    protected
    function getDateFromDate(string $date, DateTimeZone $timeZone
    )
    : ?DateTimeImmutable {
        if ($this->currentElement === 'rss')
        {
            $dateTime = DateTimeImmutable::createFromFormat($this->dateFormatRss, $date, $timeZone);
        }
        else
        {
            if ('дополняем дату до полного вида')
            {
                // пытаемся определить дату через PHP
                $parsedDate = date_parse($date);

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

            $dateTime = DateTimeImmutable::createFromFormat($this->dateFormat, $date, $timeZone);
        }

        if (!empty($dateTime))
        {
            return $dateTime;
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
    // @todo некоторые форматы даты не поддерживаются, а именно:
    // 2020 | ноябрь | 23
    // январь
    // 16.10 в 21:30
    protected
    function getDateFromText(string $date, DateTimeZone $timeZone
    )
    : ?DateTimeImmutable {
        $date = mb_strtolower(trim($date));
        $now  = new DateTimeImmutable('now', $timeZone);

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


        if ('сложная дата (разбираем ее на часы, минуты, год, день-месяц, а остальное отбрасываем)')
        {
            $date = ' ' . $date . ' ';

            // вырезаем все слова меньше трех символов
            $date = preg_replace('/\s\p{Cyrillic}{1,2}\s/u', ' ', $date);

            // часы
            preg_match('/\d{2}:\d{2}/', $date, $matchHourMinute);

            $timeStr = $matchHourMinute[0] ?? '';

            if ($timeStr)
            {
                $timeStr = trim($timeStr);
            }
            else
            {
                $timeStr = date('12:00');
            }

            $date = str_replace($timeStr, '', $date);

            // год
            preg_match('/\d{4}/', $date, $matchYear);

            $timeYear = $matchYear[0] ?? '';

            if ($timeYear)
            {
                $timeYear = trim($timeYear);
            }
            else
            {
                $timeYear = date('Y');
            }

            $date = str_replace($timeYear, '', $date);

            // день и месяц
            preg_match('/(?<dayMonth>\d+\s\p{Cyrillic}{3,}+)/iu', $date, $matchDateDayMonth);

            $dayAndMonth = $matchDateDayMonth['dayMonth'] ?? '';

            if ($dayAndMonth)
            {
                $dayAndMonth = trim($dayAndMonth);
                $date        = str_replace($dayAndMonth, '', $date);
            }

            if (!empty($dayAndMonth))
            {
                $fullDate = $dayAndMonth . ' ' . $timeYear;
            }
            else
            {
                $fullDate = date('d m') . ' ' . $timeYear;
            }

            //
            // собираем новую дату
            //

            // узнаем дату из месяца
            $dateWithNumMonth = '';

            if (!empty($fullDate))
            {
                $dateWithNumMonth = $this->getDateWithNumMonth($fullDate);
            }

            $date = $dateWithNumMonth . ' ';

            // добавляем время
            if (!empty($timeStr))
            {
                $date .= $timeStr;
            }

            if (strlen($date) >= 15)
            {
                return DateTimeImmutable::createFromFormat('d m Y H:i', $date, $timeZone);
            }
        }

        // поздравляю! Вы победили в конкурсе "самая оригинальная дата"
        // @bug no timeZone
        {
            return $this->TimeParser->parse($date);
        }
    }

    private
    function getDateWithNumMonth(string $date
    )
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
            'январь'   => '01',
            'февраль'  => '02',
            'март'     => '03',
            'апрель'   => '04',
            'май'      => '05',
            'июнь'     => '06',
            'июль'     => '07',
            'август'   => '08',
            'сентябрь' => '09',
            'октябрь'  => '10',
            'ноябрь'   => '11',
            'декабрь'  => '12',
            'янв'      => '01',
            'фев'      => '02',
            'мар'      => '03',
            'апр'      => '04',
            'июн'      => '06',
            'июл'      => '07',
            'авг'      => '08',
            'сен'      => '09',
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

    /**
     * Геттер, который достает из строки все, что похоже на урл (относительный или абсолютный)
     * например: background-image: url(/back1.jpg) => /back1.jpg
     *
     * @param string $attrVal
     *
     * @return string|null
     */
    protected function getUrlFromStyleAttr(string $attrVal)
    : string {
        if (empty($attrVal))
        {
            return '';
        }

        preg_match_all('~\bbackground(-image)?\s*:(.*?)\(\s*(\'|")?(?<image>.*?)\3?\s*\)~i', $attrVal, $matches);
        $images = $matches['image'];

        if (!empty($images[0]))
        {
            return $images[0];
        }

        return '';
    }

    // геттер элементов HTML
    protected
    function getElementsDataFromHtml(string $html, string $containerSelector, string $elementSelector, string $get = 'html'
    )
    : array {
        $this->showLog('getElementsDataFromHtml($html, "' . $containerSelector . '", "' . $elementSelector . '" ):', 'talkative');

        $fullSelector = trim($containerSelector . ' ' . $elementSelector);

        if (empty($fullSelector))
        {
            throw new Exception('Не установлен CSS-селектор!');
        }

        // решаем проблемы с кодировкой
        if ($this->currentCharset != 'utf-8')
        {
            $html = str_replace('text/html; charset=' . $this->currentCharset, 'text/html; charset=utf-8', $html);
            $html = str_replace('<meta charset="' . $this->currentCharset . '">', '<meta charset="utf-8">', $html);
        }

        $data      = [];
        $Crawler   = new Crawler($html);
        $attribute = $this->getAttrFromSelector($elementSelector);
        $elements  = $Crawler->filter($fullSelector);


        if ($elements)
        {
            $elements->each(function (Crawler $element, $i) use (&$data, $get, $attribute) {
                if (!empty($attribute))
                {
                    // если запрашивается style, то ищем ссылку
                    if ($attribute == 'style')
                    {
                        $attrVal = $element->attr($attribute);
                        $data[]  = $this->getUrlFromStyleAttr($attrVal);
                    }
                    else
                    {
                        $data[] = $element->attr($attribute);
                    }
                }
                elseif ($get == 'html')
                {
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
    protected
    function getElementsDataFromRss(string $xml, string $elementSelector, string $get = 'html', int $limit = -1, Crawler $Crawler = null
    )
    : array {
        $this->showLog('getElementsDataFromRss($html, "' . $elementSelector . '", "' . $get . '", "' . $limit . '", Crawler "' . (bool)$Crawler . '" ):', 'talkative');

        if ($this->debug >= 2 && !empty($xml))
        {
            echo "------ RAW XML  -----\033[44m" . PHP_EOL;
            echo $xml;
            echo PHP_EOL . "\033[0m------ / RAW XML-----" . PHP_EOL;
        }

        $data = [];

        if ($this->currentElement == 'rss')
        {
            if (empty($Crawler))
            {
                $Crawler = new Crawler();
            }

            $Crawler->addXmlContent($xml);
        }
        else
        {
            if (empty($Crawler))
            {
                $Crawler = new Crawler($xml);
            }
        }


        // CssSelectorConverter(true) - по умолчанию camelCase теги не сохраняются (все переводится в lower case)
        $Converter       = new CssSelectorConverter(false);
        $attribute       = $this->getAttrFromSelector($elementSelector);
        $elementSelector = $Converter->toXPath($elementSelector);

        if ($limit > 0)
        {
            $elements = $Crawler->filterXPath($elementSelector)->slice(0, $limit);
        }
        else
        {
            $elements = $Crawler->filterXPath($elementSelector);
        }

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
                    $data[] = $element->text();
                }
            });
        }

        return $data;
    }

    /**
     *
     * вытаскиваем только последний атрибут из селектора
     * CSS-selector[non-attribute][attribute] => attribute
     *
     * @param string $elementSelector
     *
     * @return string|null
     */
    protected function getAttrFromSelector(string $elementSelector
    )
    : ?string {
        $attribute = '';

        preg_match('/\[([^\]=]+)\]$/', $elementSelector, $attrMatches);

        if (!empty($attrMatches))
        {
            $attribute = $attrMatches[1];
        }

        return $attribute;
    }


    // возвращаем абсолютную ссылку
    // @todo потестить
    private
    function getUrl(?string $url
    )
    : ?string {
        // кодируем кириллицу в ссылках
        if (!empty($url))
        {
            $url = $this->encodeRusUrl($url);
        }

        // убираем пробелы в конце (%20 %0A)
        if (strpos($url, '%20') !== false ||
            strpos($url, '%0A') !== false)
        {
            $url = preg_replace('/[%20%0A]*$/', '', $url);
        }


        $url = ($url) ? UriResolver::resolve($url, $this->siteUrl) : null;

        return $url;
    }

    /**
     * Русские буквы в ссылке
     *
     * @param string $url
     *
     * @return string
     */
    protected function encodeRusUrl(string $url)
    : string {
        if (preg_match('/[А-Яа-яЁё]/iu', $url))
        {
            preg_match_all('/[А-Яа-яЁё]/iu', $url, $result);

            $search  = [];
            $replace = [];

            foreach ($result as $item)
            {
                foreach ($item as $key => $value)
                {
                    $search[$key]  = $value;
                    $replace[$key] = urlencode($value);
                }
            }

            $url = str_replace($search, $replace, $url);
        }

        return $url;
    }

    private function getParserName()
    : ?string
    {
        $classParts = explode('\\', get_class($this));

        if (!empty($classParts))
        {
            return end($classParts);
        }
    }

    /**
     * url => filename
     *
     * Parameters:
     *     $string - The string to sanitize.
     *     $force_lowercase - Force the string to lowercase?
     *     $anal - If set to *true*, will remove all non-alphanumeric characters.
     */
    function convertUrlToFileName($string, $force_lowercase = true, $anal = false)
    {
        $strip = [
            "~",
            "`",
            "!",
            "@",
            "#",
            "$",
            "%",
            "^",
            "&",
            "*",
            "(",
            ")",
            "_",
            "=",
            "+",
            "[",
            "{",
            "]",
            "}",
            "\\",
            "|",
            ";",
            ":",
            "\"",
            "'",
            "&#8216;",
            "&#8217;",
            "&#8220;",
            "&#8221;",
            "&#8211;",
            "&#8212;",
            "â€”",
            "â€“",
            ",",
            "<",
            ".",
            ">",
            "/",
            "?"
        ];
        $clean = trim(str_replace($strip, "_", strip_tags($string)));
        $clean = preg_replace('/\s+/', "-", $clean);

        //        $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9\.\-]/", "", $clean) : $clean;

        return ($force_lowercase)
            ?
            (function_exists('mb_strtolower'))
                ?
                mb_strtolower($clean, 'UTF-8')
                :
                strtolower($clean)
            :
            $clean;
    }

    /**
     * Запрос страницы URL и возврат HTML
     *
     * @param string $url
     *
     * @return string || null
     */
    protected
    function getPage(string $url
    )
    : ?string {
        $this->currentUrl     = $url;
        $this->currentCharset = 'utf-8'; // по умолчанию

        if (empty($url))
        {
            return null;
        }

        // включен эмулятор
        if (defined('static::EMULATE_MODE') && static::EMULATE_MODE)
        {
            $responseHtml = $this->getEmulateHtml($url);

            if ($this->currentElement == 'rss')
            {
                $responseHtml = $this->getCorrectedXml($responseHtml);
            }
        }
        else
        {
            // чуть помедленнее, кони, чуть помедленнее...
            if ($this->pauseBeforeRequest > 0)
            {
                if ($this->debug >= 1)
                {
                    static::showLog('пауза ' . $this->pauseBeforeRequest . ' сек...');
                }

                sleep($this->pauseBeforeRequest);
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

            $responseHtml       = $Curl->get($url);
            $responseInfo       = $Curl->getInfo();
            $this->responseInfo = $responseInfo;

            if ($this->debug >= 3)
            {
                print_r($responseInfo);
                print_r($responseHtml);

                if ('записываем в /utils/pages/Parser/url.html')
                {
                    $pagesDir   = 'utils/pages';
                    $parserName = $this->getParserName();
                    $dir        = dirname(getcwd() . '/../' . $pagesDir) . '/pages/';
                    $parserDir  = $dir . $parserName;
                    $fileName   = $this->convertUrlToFileName($url);

                    if (!is_dir($parserDir))
                    {
                        mkdir($parserDir);
                    }

                    $file = $parserDir . '/' . $fileName . '.html';
                    static::showLog('Записываем контент страницы в ' . $pagesDir . '/' . $parserName . '/' . $fileName . '.html');
                    var_dump(file_put_contents($file, $responseHtml));
                }
            }

            // пост обработка
            if (!empty($responseHtml))
            {
                // контент получен
                if ($responseInfo['http_code'] >= 200 && $responseInfo['http_code'] < 300)
                {
                    // RSS
                    if ($this->currentElement == 'rss')
                    {
                        // решаем проблемы кодировки
                        //                        $responseHtml = iconv("UTF-8", "UTF-8//IGNORE", $responseHtml);

                        // и некоректных заголовков, неймспейсов
                        $responseHtml = $this->getCorrectedXml($responseHtml);
                    }
                    // HTML
                    else
                    {
                        // решаем проблемы кодировки. Все должно быть переведено в utf-8
                        $charset    = '';
                        $charsetRaw = !empty($responseInfo['content_type']) ? $responseInfo['content_type'] : null;

                        // нашли в заголовках
                        if (strpos($charsetRaw, 'charset=') !== false)
                        {
                            preg_match('~charset=(\'|")?(?<charset>[\w\-]*)(\'|")?~', $charsetRaw, $charsetMatches);

                            // план А
                            if (!empty($charsetMatches['charset']))
                            {
                                $charset = $charsetMatches['charset'];
                            }
                            // план В
                            else
                            {
                                $charsetRaw = str_replace("text/html; charset=", "", $charsetRaw);
                                $charsetRaw = str_replace("text/html;charset=", "", $charsetRaw);
                                $charset    = $charsetRaw;
                            }
                        }
                        // не нашли в заголовках. Ищем в контенте
                        else
                        {
                            preg_match('/charset=([-a-z0-9_]+)/i', $responseHtml, $charsetMatches);

                            if (!empty($charsetMatches[1]))
                            {
                                $charset = trim($charsetMatches[1]);
                            }
                        }

                        $charset              = strtolower($charset);
                        $this->currentCharset = $charset;


                        // делаем перекодировку
                        if (!empty($charset) && $charset !== 'utf-8')
                        {
                            $responseHtml = mb_convert_encoding($responseHtml, 'utf-8', $charset);
                        }
                    }

                    // @FEAUTURE проверяем что в html нет браузерного редиректа
                    // ...
                }
                else
                {
                    return null;
                }
            }
        }

        // вырезаем теги <script> <style>, которые не вырезаются через strip_tags
        if ($this->currentElement != 'rss' && $this->currentElement != 'list')
        {
            $responseHtml = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $responseHtml);
            $responseHtml = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $responseHtml);
        }

        if (is_string($responseHtml) && !empty($responseHtml))
        {
            $this->currentPageFullHtml = $responseHtml;
        }
        else
        {
            $this->currentPageFullHtml = '<get-page-gets-nothing />';
        }

        return $responseHtml;
    }

    // корректировка для кривых xml
    protected function getCorrectedXml($responseHtml)
    {
        // ставим первым <?xml version="1.0"...
        $responseHtml = preg_replace('/\s*<\?xml\s.*\?>/', '<?xml version="1.0"?>', $responseHtml);


        // убираем всякую чушь из тега rss
        // <rss version="2.0" xmlns="http://backend.userland.com/rss2" xmlns:yandex="http://news.yandex.ru">
        $responseHtml = preg_replace('/<rss\s+[^>]*>|<rss>/', '<rss version="2.0">', $responseHtml);

        if ($this->debug >= 3)
        {
            static::showLog('Начало XML после корректировки: ' . substr($responseHtml, 0, 1000));
        }

        if ($this->debug >= 1 && 'проверка RSS на корректность')
        {
            libxml_use_internal_errors(true);
            try
            {
                $xml = new \SimpleXmlElement($responseHtml);
            } catch (Exception $e)
            {
                //nothing
            }

            libxml_clear_errors();

            static::showLog('==== RSS в порядке ====', 'default');
        }

        return $responseHtml;
    }

    // установка формата времени для HTML
    private
    function getDateFormat()
    : string
    {
        return $this->config['site']['date_format'] ?? 'd.m.Y H:i';
    }

    private
    function getTimeParser()
    {
        // @author https://github.com/Metallizzer/TimeParser
        return new TimeParser('russian');
    }

    // @todo переделать
    private function getDebug()
    {
        // CORE_PARSER_DEBUG_EXTERNAL - определяется там где нужно "перебить" режим установленный в парсере
        return defined('CORE_PARSER_DEBUG_EXTERNAL') ? CORE_PARSER_DEBUG_EXTERNAL : (int)static::DEBUG;
    }

    private function getPauseBeforeRequest()
    : int
    {
        $sec = $this->config['site']['pause'] ?? 0;

        if ($sec < 0)
        {
            $sec = 0;
        }

        if ($sec > 10)
        {
            $sec = 10;
        }

        return $sec;
    }

    // установка формата времени для RSS
    private
    function getDateFormatRss()
    : string
    {
        return $this->config['site']['date_format_rss'] ?? 'D, d M Y H:i:s O';
    }

    // установка временной зоны
    private
    function getTimeZone()
    : string
    {
        return $this->config['site']['time_zone'] ?? '+0300';
    }

    // установка лимита элементов
    private
    function getItemsLimit()
    : int
    {
        $coreLimit   = self::MAX_ITEMS;
        $parserLimit = $this->config['itemsLimit'] ?? null;
        $realLimit   = 10;

        if (isset($this->debug) && $this->debug >= 1)
        {
            if (!empty($parserLimit))
            {
                $realLimit = $parserLimit;
            }
        }
        else
        {
            $realLimit = $coreLimit;
        }

        // если включен режим внешнего управление дебагом CORE_PARSER_DEBUG_EXTERNAL, то
        // считаем что также лимит = 1
        if (defined('CORE_PARSER_DEBUG_EXTERNAL'))
        {
            if (defined('CORE_PARSER_LIMIT_ITEMS_EXTERNAL'))
            {
                $realLimit = CORE_PARSER_LIMIT_ITEMS_EXTERNAL;
            }
            else
            {
                $realLimit = 1;
            }
        }


        return $realLimit;
    }

    // установка URL сайта
    private
    function getSiteUrl()
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
    private
    function getMode()
    : string
    {
        $mode = $this->config['mode'] ?? '';

        if (in_array($mode, self::MODE_TYPES))
        {
            return $mode;
        }

        return 'desktop';
    }

    private
    static function getVersionArray(string $version
    ) {
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

    private
    function keysIsNotEmptyInAnotherArray(array $arrayOfRequiredKeys, array $arrayTarget, $path = null
    ) {
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
                    throw new Exception($keyPath . ' - должен быть указан');
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
    private
    function getValFromKeyChain($arrayTarget, $keyChain
    ) {
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
    // https://habr.com/ru/post/119436/
    // https://en.wikipedia.org/wiki/ANSI_escape_code#/media/File:ANSI_sample_program_output.png
    private
    function showLog(string $message, string $mode = 'default', $break = true
    ) {
        $debug = $this->debug;

        if ($debug > 0)
        {
            $maxLen    = 1000;
            $debugMode = 'default';


            if (defined('static::DEBUG_MODE'))
            {
                $debugMode = static::DEBUG_MODE;
            }

            if ($debug < 2)
            {
                // определен дебаг режим (шаблон не имеет данной настройки)
                if (defined('static::DEBUG_MODE'))
                {
                    if ($mode == 'talkative' && $debugMode !== 'talkative')
                    {
                        return;
                    }
                }
                elseif ($mode == 'talkative')
                {
                    return;
                }
            }

            if ($mode == 'warning')
            {
                echo "\033[31m";
            }
            elseif ($mode == 'success')
            {
                echo "\033[32m";
            }
            elseif ($mode == 'talkative')
            {
                echo "\033[37m";
            }

            if (strlen($message) > $maxLen)
            {
                echo substr($message, 0, $maxLen) . PHP_EOL . '[...лог обрезан...]';
            }
            else
            {
                echo $message;
            }

            if ($mode == 'warning' || $mode == 'success' || $mode == 'talkative')
            {
                echo "\033[0m";
            }

            if ($break)
            {
                echo PHP_EOL;
            }
        }
    }

    // для эмуляции http-запросов к URL
    public
    function getEmulateHtml(string $url
    )
    : ?string {
        $emulateData  = [];
        $fileWithData = self::WORK_DIR . 'emulateHtml.php';

        if (file_exists($fileWithData))
        {
            $emulateData = include($fileWithData);
        }

        return $emulateData[$url] ?? null;
    }

    /*
     *
     * -------- ТЕСТЫ ----------
     *
     */

    // @note чтобы потестить даты без текста должен быть включен режим desktop
    public
    function testGetDate($mode = 'all', string $dateFormat = ''
    ) {
        if (empty($dateFormat))
        {
            $this->dateFormat = 'd.m.Y H:i';
        }
        else
        {
            $this->dateFormat = $dateFormat;
        }

        $this->timeZone = '+0000';

        static::showLog('--- format= ' . $this->dateFormat . ' | zone= ' . $this->timeZone . ' ---');

        $valuesDate = [
            '',
            '28.10.2020 07:38',
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
            '1 ноября',
            '1 ноября 2019',
            '1 ноября 2020',
            '1 ноября 2020 в 10:00',
            '1 ноября 2020 в 10:00:11',
            '2020, 23 ноября',
            '16 Окт, 2020',
            '2020 | ноябрь | 23',
            '28 сен 2020 / из 11:22:33',
            '1&nbsp;ноября 2020',
            '<div class="b-material-head__date"><span class="b-material-head__date-day">1&nbsp;ноября 2020&nbsp;г.</span> <span class="b-material-head__date-time">14:40</span></div>',
            'Sat, 24 Oct 2020 07:21:09 +0400',
            ' 22 Октября, Четверг',
            ' 22 Октября, пт.',
            ' 22 октябрь, пятница',
            ' январь',
            //            ' 22 октябрь, пятница-развратница',
            '<div class="bis-topic-header-date">
                        23.10.20 11:03
                        <div id="ctl00_content_tllv2_textLinks" class="bis-topic-tags-list" style="display: inline; margin-left: 20px;"><a href="tag.aspx?id=257" class="link">интернет-банк</a>, <a href="tag.aspx?id=1533" class="link">мобильные приложения</a> </div>
                    </div>',
            '22.10.20 18:43 <a href="tag.aspx?id=173" class="link">монеты</a>',
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
            '',
            'uqgwejhjvjv',
            'фигня какая-то'
        ];

        if ($mode == 'all')
        {
            $values = array_merge($valuesDate, $valuesText);
        }
        elseif ($mode == 'text')
        {
            $values = $valuesText;
        }
        elseif ($mode == 'date')
        {
            $values = $valuesDate;
        }

        foreach ($values as $value)
        {
            static::showLog($value . "\t" . '  => ', 'default', false);
            static::showLog($this->getDate($value), 'warning');
        }
    }

    public
    function testGetAttrFromSelector()
    : void
    {
        $selectors = [
            'img[target]',
            'img[target="some"]',
            'img[non-target][target]',
            'img[non-target][target="some"]',
            'img[]',
        ];

        foreach ($selectors as $selector)
        {
            echo $selector . ' => ' . $this->getAttrFromSelector($selector) . PHP_EOL;
        }
    }

    public function getVersion()
    {
        return self::VERSION;
    }
}