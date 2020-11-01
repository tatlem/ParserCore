<?php


namespace app\commands;

use app\components\Helper;
use yii\console\Controller;

// 0 - отключаем режим дебага у всех парсеров, 1+ - включаем
// данная строка должна быть всегда закомментирована, если не идет проверка
//define('CORE_PARSER_DEBUG_EXTERNAL', 0);
// кол-во парсеров
//define('CORE_PARSER_LIMIT_ITEMS_EXTERNAL', 1);

class ParserCoreDebugController extends Controller
{
    /**
     * Run parser by class name
     *
     * @param $class
     */

    public function actionNews(string $class)
    : void {
        $class = "app\components\\core\\$class";
        $class = new $class();
        $posts = ($class->run());
        foreach ($posts as $post)
        {
            $post->validate();
        }
        echo "Ok" . PHP_EOL;
        echo "Post count: " . count($posts) . PHP_EOL;
        Helper::printPosts($posts);
    }

    public function actionCheckParsers(int $parserLimit = 2000, int $itemsLimit = 1)
    : void {
        echo '--------------------------------------------------------------------' . PHP_EOL;
        echo ' Старт проверки парсеров (не проверяется релевантность контента). ' . PHP_EOL;
        //        echo ' Лимит парсеров: ' . $parserLimit . '; Лимит новостей: ' . $itemsLimit . PHP_EOL;
        echo ' Лимит парсеров: ' . $parserLimit . '; Лимит новостей: 1' . PHP_EOL;
        echo '--------------------------------------------------------------------' . PHP_EOL;

        // из-за символических ссылок, реальное выполнение идет из оригинального файла
        $dir   = dirname('../../');
        $files = scandir($dir . '/parser/components/parser/news');

        $i    = 0;
        $good = 0;
        $bad  = 0;

        //        echo '$itemsLimit = ';
        //        var_dump($itemsLimit);

        foreach ($files as $file)
        {
            $pathinfo = pathinfo($file);

            if (isset($pathinfo['extension']) && $pathinfo['extension'] == 'php')
            {
                if ($i >= $parserLimit)
                {
                    break;
                }

                if (strpos($pathinfo['filename'], 'CORE_') !== 0)
                {
                    continue;
                }

                $i++;

                // если надо пропустить какие-то парсеры
                //                if ($i <= 153)
                //                {
                //                    continue;
                //                }

                echo $i . '. ' . $pathinfo['filename'] . ' => ';

                $class = 'app\components\parser\\news\\' . $pathinfo['filename'];

                $Parser = new $class();
                //                $Parser->itemsLimitChecker = $itemsLimit;

                try
                {
                    $posts = $Parser->run();

                    foreach ($posts as $post)
                    {
                        $post->validate();
                    }

                    $good++;
                    echo "\033[32mOK\033[0m" . PHP_EOL;
                    //                    echo "Post count: " . count($posts) . PHP_EOL;
                    //                    Helper::printPosts($posts);
                } catch (\Throwable $t)
                {
                    $bad++;
                    echo "FAILED: " . $pathinfo['filename'];
                    echo ", error: " . $t->getMessage() . PHP_EOL;
                }
                //                echo PHP_EOL;
            }
        }

        echo 'Всего проверено парсеров: ' . $i . PHP_EOL;
        echo 'С ошибками: ' . $bad . PHP_EOL;
        echo 'Нормальные: ' . $good . PHP_EOL;
    }
}