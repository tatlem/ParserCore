<?php
// крутые html примеры для теста https://www.htmhell.dev/1/

return [
    'https://test/desc-vs-text' => <<<'HTML'
<!DOCTYPE html>
<html>
<body>



<div id="container">
    <div class="img1"> /ava.jpg 
    </div>
    
    <div id="desc">Стартап FreshToHome из Бангалора, разрабатывающий платформу для оптимизации бесконтактной торговли продуктами питания, успешно закрыл раунд финансирования серии C на сумму $121 млн. и, таким образом, установил новый рекорд для венчурного рынка Индии. Площадка FreshToHome убирает лишних посредников и сокращает время проведения операций в цепочках поставок до 24-36 часов. Главная цель компании - уберизировать работу фермеров и рыбаков.
    </div>
    
    <div id="text">
    Стартап FreshToHome из Бангалора, разрабатывающий платформу для оптимизации бесконтактной торговли продуктами питания, успешно закрыл раунд финансирования серии C на сумму $121 млн. и, таким образом, установил новый рекорд для венчурного рынка Индии. Площадка FreshToHome убирает лишних посредников и сокращает время проведения операций в цепочках поставок до 24-36 часов. Главная цель компании — уберизировать работу фермеров и рыбаков. 706FreshToHome напрямую закупает рыбу, мясо и овощи у поставщиков с помощью запатентованной технологии на базе ИИ. Фермеры и рыбаки подают заявки на отправку последних урожаев в приложении компании, что, по словам представителей FreshToHome, позволяет лучше контролировать качество товаров и снижать стоимость продуктов для конечного потребителя. Как сообщает TechCrunch, стартап предлагает свои услуги в нескольких крупных городах Индии, включая Дели, Мумбаи, Пуне, Бангалор и Хайдарабад, а также готовится к выходу на рынок ОАЭ.
    </div>

    <div id="text1">
        <h1>тот же самый дескр. и еще заголовок</h1>
        тот же <a href="/link">самый</a>дескр. и еще какой-то текст
    </div>
</div>
</body>
</html>

HTML,

    'https://test/spaces-in-attr' => <<<'HTML'
<!DOCTYPE html>
<html>
<body>



<div id="container">
    <div class="img1"> /ava.jpg 
    </div>

    <div id="text">
    пробелы в атрибутах

    <p>test1</p><a href="/asd">link</a><p>test2</p><p>test3</p>
    <p>test4</p>
    
    
    
    
    
    Я
    тут
    
    что-то
    
    
    уже
    
    
    
    
        написал
    
    <img src=" /normal.jpg 
    " />
    <img src="/with spaces.jpg" />
    <a href=" /some ">some</a>
    <a href="/some2%20">some</a>
    
    
    
    
    
    
    
    текст
    
    
    
    
    
    еще текст
    
        текст
    
    </div>
</div>
</body>
</html>

HTML,

    'https://test/image-from-background' => <<<'HTML'
<!DOCTYPE html>
<html>
<body>



<div id="container">

    <div id="text">
    
    <img src="/normal.jpg" />
    <div class="img"  style="background-image: url( /multi1.jpg ), url('/multi2.jpg')"></div>
    <div class="img"  style="background-image: url( /back1.jpg )"></div>
    <div class="img" style="background-image: url(https://ya.ru/back2.jpg); color: red"></div>
    <div class="img" style="background: red url('/back3.jpg') no-repeat"></div>
    <div class="img" style="background: red url('https://ya.ru/back3.jpg') no-repeat"></div>
    <img src="/normal2.jpg" style="background-image: url(/not-normal.jpg)" />
    
    </div>
</div>
</body>
</html>

HTML,

    'https://test/doubles' => <<<'HTML'
<!DOCTYPE html>
<html>
<body>



<div id="container">

    <div id="text">
    
    <h1>дубли</h1>
    <p>описание дубли</p>
    <img src="/double.jpg">
    Оригинальный текст новости
    
    <img src="/ava.jpg">
    <img src="/double.jpg">
    <h2>дубли</h2>
    <img src="/double.jpg">
    <img src="/double2.jpg">
    <img src="/double.jpg">
    текст
    
    <img src="/double2.jpg">
    </div>
</div>
</body>
</html>

HTML,


    'https://test/element-text-insert' => <<<'HTML'
<!DOCTYPE html>
<html>
<body>

<h2 id="insert1">
Вставляемый <strong>текст</strong> извне
</h2>

<img id="insert2" src="/insert.jpg" />

<div id="insert3">
    <div>
        <h2>Заголовок извне №3</h2>
        <p>текст извне №3</p>
    </div>
</div>

<div id="container">

    <div id="title">element-text-insert</div>
    <div id="text">
    
    Оригинальный текст новости
    </div>
</div>
</body>
</html>
HTML
    ,

    'https://test/mutants' => <<<'HTML'
<!DOCTYPE html>
<html>
<body>


<div id="container">

    <div id="title">card bolvan</div>
    <div id="text">
    
    
    <p>текст 1</p>
    <p>текст 2</p>
    
        
<!-- crazy mutants -->
<div>
    <iframe><a href="/m1">mutant-link1</a></iframe>    
</div>    



<blockquote><q>Я две цитаты</q></blockquote>
<div><a href="#1"><p><blockquote>Обернутая ссылкой цитата становится ссылкой :(</blockquote></p></div></a>
    
Это предложение <b><a href="/#">колбасит</a></b> как <q>фиг</q> <a href="####">знает</a> ЧТО!
    
ссылки всякие:   
<a href="papa"><a href="child"><strong>двойная ссылка</strong></a></a>
<a href="/ups">ой, забыли меня закрыть
<a href="/not-ups">а я вот закрыта</a>
<a>Я старая больная ссылка</a>

тексты в таблицах, листах
<table><tr><td>А</td><td>Б</td></tr></table>
<ul><li>папа1</li><li>папа2<ul><li>сын1</li><li>сын2</li></ul></li><li>папа3</li></ul>

формы!
<form>
<label>Скажите, что вы</label>
<input type="text" name="youare" value="не робот">
и не хотите есть эти батарейки
</form>

-- 1 --
<h1>Product Status</h1>
<h2>Is the product available?</h2>
<div>
  <h3>
    <div>
      <div>
        <i>
          <h3 class="message is-success">
            It‘s <a>available</a>.
          </h3>
        </i>
      </div>
    </div>
  </h3>
</div>

-- 2 --
<section>
  <section>
    <h2>Overview</h2>
    <figure class="card" data-url="image1.html" style="background: url(image1.jpg)">
      <figcaption>
        <h4>My heading</h4>
        <article>Teasertext...</article>
      </figcaption>
    </figure>
    <figure class="card" data-url="image2.html" style="background: url(image2.jpg)"> … </figure>
  </section>
</section>
    
    
    </div>
</div>
</body>
</html>
HTML
    ,

    'https://test/news-bolvanka3' => <<<'HTML'
<!DOCTYPE html>
<html>
<body>


<div id="container">

    <div id="title">card bolvan</div>
    <div id="text">
    
  

    <div id="text2" class="page-content io-article-body">
                
<p>Из реки Бельбек запланировано брать 50 тысяч кубометров воды в сутки для водоснабжения Севастополя, что составляет 1,5 млн кубов в месяц. Из Кадыковского карьера — 15 тысяч кубометров. Об этом сегодня в ходе посещения строительных объектов и Чернореченского водохранилища рассказал глава города <strong>Михаил Развожаев</strong>, сообщает ИА SevastopolMedia.</p> <p>— Вы сами видите какой объём воды: 16 млн кубометров на сегодняшний день. Воды на три месяца, если будем в таком же режиме потреблять. Меньше мы не можем. Водохранилище начинает наполняться в конце декабря за счёт осадков. Но пока прогноз неутешительный. Поэтому мы должны подстраховаться, чтобы в случае продолжения засухи и невосполнения запасов обеспечить Севастополь стабильным водоснабжением, — сказал он.</p><div class="inside_banner noprint"><!--AdFox START-->
<!--PrimaMedia-->
<!--Площадка: 9 SevastopolMedia / * / *-->
<!--Тип баннера: 700x120-->
<!--Расположение: Other (700х120) News 1-->
<div id="adfox_152956523551048628"><div style="width: 728px; height: 90px; margin: 0px auto;"><div id="sf-84086828">
<iframe scrolling="no" allowtransparency="true" hidefocus="true" tabindex="-1" marginwidth="0" marginheight="0" style="opacity: 1;" src="https://yastatic.net/safeframe-bundles/0.69/1-1-0/render.html" id="id7092" frameborder="no"></iframe></div></div></div>
</div><p></p> 

<p>И добавил, что в настоящее время в городе реализовываются первоочередные мероприятия по решению водной проблемы.</p> 
<div class="img-slider slider_1"><div id="carousel-example-generic1" class="carousel slide carousel-fade" data-ride="carousel"><div class="carousel-inner" role="listbox"><div class="item active" data-num="1"><div class="carousel-caption"><img src="https://primamedia.gcdn.co/f/big/2243/2242285.jpg?2e20e229d412a7bf7894a5abc971a4d5" width="1000px">
<p>Строительство водозабора из Кадыковского карьера. Фото: ИА SevastopolMedia</p></div></div><div class="item" data-num="2"><div class="carousel-caption"><img src="https://primamedia.gcdn.co/f/big/2243/2242288.jpg?e1d2af1414dfac29d481e7ba2799a3f8" width="1000px"><p>Чернореченское водохранилище. Фото: ИА SevastopolMedia</p></div></div></div></div><div><div class="img-sldr-nav"><a class="left carousel-control" href="#carousel-example-generic1" role="button" data-slide="prev"><span class="fa fa-angle-left" aria-hidden="true"></span></a><span class="carousel-num"><i>1</i> / 2</span><a class="right carousel-control" href="#carousel-example-generic1" role="button" data-slide="next"><span class="fa fa-angle-right" aria-hidden="true"></span></a></div></div></div> <p>— Два главных объекта строят военные строители. Эти объекты мы сегодня посмотрели: переброска воды с Кадыковского карьера, там обустраивается трубопровод, и главный объект, на который самые большие надежды, это водозабор на реке Бельбек, — сообщил губернатор.</p> <p>Проекты реализовывают строители Министерства обороны РФ. Из Кадыковского карьера планируют брать 15 тысяч кубометров воды в сутки, из реки Бельбек во время паводка — 50 тысяч кубометров. Вода из Бельбека будет поступать на Днепровский водозабор, а затем идти на гидроузел №14.</p> <p>— В весенние месяцы мы планируем забрать до 10 млн кубометров воды, — отметил Развожаев.</p> <p>Кроме того, в этом году, по его словам, будет проведён ремонт двух крупнейших водоводов: на проспекте Октябрьской революции и в Инкермане, где происходят самые большие потери воды.</p> <p>— Важно не только найти воду, но и её сохранить. Программа ремонта сетей утверждена на три года и это будет важным направлением работы, чтобы потери с 40% мы уменьшили до 20%, — подчеркнул глава города.</p> <p></p><div class="full-img">
                <img id="img1010597" src="https://primamedia.gcdn.co/f/big/2243/2242296.jpg" alt="Информационный стенд"><br><p><span>Информационный стенд. Фото: ИА SevastopolMedia</span></p></div> <p>Также в Севастополе планируется создание цифровой модели подачи воды, которая позволит в онлайн-режиме отслеживать потери воды.</p> <p>— Я поставил задачу пока водоснабжение не останавливать, с учётом развернувшихся работ. Главная задача сделать так, чтобы не было подачи воды по графику. Надо пережить засушливый период без отключений, — сказал губернатор.</p> <div class="img-slider slider_2"><div id="carousel-example-generic2" class="carousel slide carousel-fade" data-ride="carousel"><div class="carousel-inner" role="listbox"><div class="item active" data-num="1"><div class="carousel-caption"><img src="https://primamedia.gcdn.co/f/big/2243/2242286.jpg?bc418d6f87cf3f822060fe7b22558f81" width="1000px"><p>Строительство водозабора из реки Бельбек. Фото: ИА SevastopolMedia</p></div></div><div class="item" data-num="2"><div class="carousel-caption"><img src="https://primamedia.gcdn.co/f/big/2243/2242287.jpg?e1d2af1414dfac29d481e7ba2799a3f8" width="1000px"><p>Информационный стенд. Фото: ИА SevastopolMedia</p></div></div></div></div><div><div class="img-sldr-nav"><a class="left carousel-control" href="#carousel-example-generic2" role="button" data-slide="prev"><span class="fa fa-angle-left" aria-hidden="true"></span></a><span class="carousel-num"><i>1</i> / 2</span><a class="right carousel-control" href="#carousel-example-generic2" role="button" data-slide="next"><span class="fa fa-angle-right" aria-hidden="true"></span></a></div></div></div> <p>Советник директора главного строительного управления №4 <strong>Алексей Трубачёв</strong> отметил, что работы по Кадыковскому карьеру должны быть завершены 25 декабря 2020 года, на реке Бельбек — 12 марта 2021 года. По его словам, на обоих строительных объектах задействованы 160 человек и 38 единиц техники, на следующей неделе предполагается увеличат технику на 20 единиц и на 150 человек.&nbsp;</p><div class="inside_banner noprint"><!-- Yandex.RTB R-A-244049-32 -->
<div id="yandex_rtb_R-A-244049-32-0-557"><yatag class="a7c1eb41f q6e4b12ad" id="a7c1eb41f" lang="ru"><yatag class="v4f7996e2 x1130bf98 t22a6d4b1 c57d35662"><yatag style="min-height: 150px !important; min-width: 711px !important; max-width: 711px !important;"></yatag><yatag id="n228515e6"><yatag class="dab34be50"></yatag></yatag></yatag></yatag></div>
</div><p></p> <p>Напомним, на реализацию всех этих проектов правительство России выделило 5 млрд рублей. Средства направлены Минобороны РФ.&nbsp;</p>
                    
        
      <div id="soc_invites_block" class="noprint">
<div><img class="soc_invites_icon" src="https://primamedia.gcdn.co/f/social_icons/wa.svg" width="25" height="25"></div><p><strong><!--noindex--><a id="soc_invites_wa" rel="nofollow" target="_blank" href="/inviteplace/?idPlace=1&amp;address=https%3A%2F%2Fchat.whatsapp.com%2FEgUpczUahn0Aq4FroGoJL4&amp;idNews=1010597&amp;idInvite=69">Вступай в группу SevastopolMedia в WhatsApp и узнавай главные новости Севастополя<!--/noindex--></a></strong></p><div><img class="soc_invites_icon" src="https://primamedia.gcdn.co/f/social_icons/vk.svg" width="25" height="25"></div><p><strong><!--noindex--><a rel="nofollow" target="_blank" href="/inviteplace/?idPlace=1&amp;address=https%3A%2F%2Fvk.com%2Fsevastopolmedia&amp;idNews=1010597&amp;idInvite=71">Читай нас в Vk<!--/noindex--></a></strong></p></div>
                                                <div id="adv" style="display: none;"><div id="mv-content-roll-2604" class="mv-content-roll-2604-wrap" style="padding: 0px; margin: 0px; display: none; overflow: hidden; text-align: center; float: none; clear: both; width: 100%; height: 1px; transition: all 200ms linear 0s; opacity: 0;"><div class="mv-content-roll-2604-inner-wrap" style="position: relative; display: inline-block; width: 711px; height: 399.938px;"><div class="mv-content-roll-2604-t-node" style="padding: 0px; margin: 0px; display: inline-block; background-color: transparent; transition: none 0s ease 0s; opacity: 0.001; pointer-events: none; transform: translateZ(0px); width: 100%; height: 100%; position: static; inset: auto; z-index: 0; box-shadow: none;"><div class="mv-content-roll-2604-t-div" style="margin: 0px; position: relative; overflow: hidden; width: 100%; height: 100%;"></div><div title="Close" class="mv-content-roll-2604-float-close" style="position: absolute; color: white; background-color: black; border-radius: 50%; z-index: 9999; font-size: 14px; width: 20px; height: 20px; line-height: 20px; text-align: center; cursor: pointer; right: 3px; top: -22px; display: none;">×</div></div></div></div></div>
                                       
                                                </div>
    
    
    
    
    
    
    Текст
    <em>Цитата 1</em>- пишет кто-то1 
    <em>Цитата 2</em>- пишет кто-то2 
    текст2
    

</div>
</div>
</body>
</html>
HTML,


    'https://test/news-bolvanka2' => <<<'HTML'
<!DOCTYPE html>
<html>
<body>


<div id="container">

    <div id="title">card bolvan</div>
    
    <div id="text">
    
    <p><strong>Калугу накрыло первым мокрым снегом.</strong></p>

<img src="/1.jpg" />
<img src="/2.jpg" data-img />
<img src="/2.jpg" data-img="something" />

<figure><img src="/public/user_upload/files/2020/10/sf6V0VuZZgY.jpg" style="width:942px;height:530px" /><figcaption>Фото: Елена Конина / Ника ТВ</figcaption></figure>

    
    <style>
    .style { <a href="asd">asd</a>}
</style>
    
    <script>
                          var moevideoQueue = moevideoQueue || [];
                          moevideoQueue.push(function () {
                              moevideo.ContentRoll({
                                  mode:"manual",
                                  el:"#adv",
                                  maxRefresh: 10,
                                  floatWidth: "380",
                                  floatPosition:"bottom right",
                                  floatCloseTimeout: 10,
                                  ignorePlayers: true,
                                  floatMode:"full"
                              });
                          });
                      </script>
    
    <!-- избавление от дублей text -->
    <h1>Заголовок 1</h1>
    Раз <a href="https://ya.ru">два</a> три
    <p>четыре</p>
    <h2>Заголовок 2</h2>
    пять <a href="https://ya.ru">ya.ru</a> <a href="/asd">шесть</a> семь воесмь
    
    <iframe>Какой-то текст в айфрейме</iframe>
    
    <script>Забыл вырезать скрипт</script>
    
    </div>
</div>
</body>
</html>

HTML
    ,


    'https://test/news-bolvanka1' => <<<'HTML'
<!DOCTYPE html>
<html>
<body>


<div id="container">

    <div id="title">card bolvan</div>
    
    <div id="text">
    
    
    текст1 <a href="/1">текст-ссылки-1</a> текст2 
    
    
        
        
Простой текст без параграфа

<p>Проверка текста&nbsp;который может<br> 
&laquo;иметь&raquo; вся &quot;ку≥&quot;"'ё1234567890-=йцукенгшщзхъфывапролджэ\ячсмитьбю.
§1234567890-==qwertyuiop[]asdfghjkl;'\`zxcvbnm,./
</p>

<div><div>текст в дивах</div></div>

без параграфа

<div class="reklama">Рекламный модуль - нужно вырезать</div>

<h2>Заголовок 2 <span class="bad">Нужно вырезать</span></h2>
    
<!-- headers -->
<h1>h1-text</h1>
text-after-h1
<h2>h2-text</h2>
., ?!
<h3>h3-text</h3>
.....
<h4>h4-text</h4>
...но
<h5>h5-text</h5>
и????
<h6>h6-text</h6>
<!-- images -->
<img src="/картинка1" alt="альт-текст картинка1">
<img src="/../testImage.JPG">
<div class="ct-ni-img" img="https://kazved.ru/attachments/d189f8bb8708655d9a49268607b82b00a7e11e8e/store/fill/540/300/616a713c7ff5b896f24c0f78dc159d7ab474002e5c583b8a403b4d24946a/1+%D0%BC%D1%83%D1%80%D0%B0%D1%82%D0%BE%D0%B2.jpg"></div>

<!-- youtube -->
<iframe width="560" height="315" src="https://www.youtube.com/embed/ihRQfjOgRSM" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
<video>
    <source src="https://youtu.be/vsjzbNik9RE">
</video>

<!-- quotes by html -->
<div class="quote-custom">Кастомная <b>strong</b> <a href="/aasdd">цитата1</a></div>
<blockquote>
    <h2>Цитата H2-text</h2>
    цитата1
</blockquote>
<q>цитата2</q>
<div class="quote-custom">Кастомная цитата2</div>


<!-- links -->
<a href="http://wmbase2.fingli.ru/frontend/web/images/по-Русски/Русская картинка.jpg">русская ссылка</a>
<a href="https://test/frontend/web/images/по-Русски/Русская картинка.jpg">русская ссылка</a>
<a href=" https://test/page ">с пробелами</a>
<a href="https://kazved.ru//attachments/33e58ac51ab9f96121ffdff8dff6fc89f582598e/store/fill/780/440/616a713c7ff5b896f24c0f78dc159d7ab474002e5c583b8a403b4d24946a/1 муратов.jpg">говорят некорректная ссылка</a>
<a href="https://kazved.ru/attachments/33e58ac51ab9f96121ffdff8dff6fc89f582598e/store/fill/780/440/616a713c7ff5b896f24c0f78dc159d7ab474002e5c583b8a403b4d24946a/1+%D0%BC%D1%83%D1%80%D0%B0%D1%82%D0%BE%D0%B2.jpg">корректная ссылка</a>
<a name="якорь">Якорь</a>
<a href="/relative">Относительный линк</a>
<a href="/%d0%ba%d0%b0%d0%ba%d0%b0%d1%8f-%d1%82%d0%be%20%d0%b4%d0%b8%d1%87%d1%8c/">какая-то дичь должна быть в URL</a>
<a href="https://22.мвд.рф/" target="_blank">Ссылка на мвд по-русски </a>
<a href=" https://test/news-bolvanka/another">link-text-native-page</a>
<div><a href="#url1">allowed <strong>strong</strong> link-text</a></div>


<!-- incorrect model -->
<a href="/ссылка1"><img src="http://ya.ru/image/reklama.jpg"/>важный текст</a>
<a href="/ссылка2"><img src="http://ya.ru/image/reklama.jpg"/> <h1>для смердженной <strong>ссылки</strong> </h1> еще текст</a>
<a href="https://youtu.be/2jzecQ0W1cQ">Ссылка на ютуб</a>

<!-- other tags with text -->
<text>my-custom-text</text>
<table><tr><td>tab-text1</td><td>tab-text2</td></tr></table>

<ul><li>li-text1</li><li>li-text2</li></ul>

<p>text8</p>


    </div>

</div>
</body>
</html>

HTML
    ,

    'https://test/rss.xml'              => <<<'HTML'
<?xml version="1.0" encoding="UTF-8" ?><rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
<channel>


<item>
	<title>Тестоваая новость 1</title>
	<author>fingli</author>
	<pubDate>Thu, 15 Oct 2020 23:31:38 +0400</pubDate>
	<link>https://test/element-text-insert</link>
	<link1>https://test/mutants</link1>
	<link1>https://test/news-bolvanka1</link1>
	<enclosure url="/test.jpg" type="image/jpeg"></enclosure>
	<description><![CDATA[ Всё об одном из удобнейших приспособлений для уборки участка Всё об одном из удобнейших приспособлений для уборки участка Всё об одном из удобнейших приспособлений для уборки участка<div style="clear:both;"></div> ]]></description>
</item>

<item>
	<title></title>
	<pubDate></pubDate>
	<link></link>
</item>
</channel>
</rss>

HTML
    ,
    'https://test/vitrina-bolvanka'     => <<<'HTML'
<!DOCTYPE html>
<html>
<body>
<div id="container">
<div class="item">
    <a href="https://test/desc-vs-text">дескр против текста</a>
    <div class="desc">тот же самый дескр.</div>
</div>
<div class="item1">
    <a href="https://test/spaces-in-attr">пробелы в атрибутах</a>
    <img1 src="/ava.jpg" alt="avava"/>
</div>
<div class="item1">
    <div class="desc">картинки из бэка</div>
    <a href="https://test/image-from-background">картинки из бэка</a>
    <img1 src="/ava.jpg" alt="avava"/>
</div>
<div class="item1">
    <div class="desc">описание дубли</div>
    <a href="https://test/doubles">дубли</a>
    <img src="/ava.jpg" alt="avava"/>
</div>



</div>
</body>
</html>
HTML
    ,
    'https://progorodsamara.ru/rss.xml' => <<<'HTML'
<?xml version="1.0" encoding="UTF-8" ?><rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
<channel>
<atom:link href="https://progorodsamara.ru/rss.xml" rel="self" type="application/rss+xml"/>
<title>Новости Самары, новости Самарской области, ПРО город Самара</title>
<link>https://progorodsamara.ru</link>
<description>Новости Самары, новости Самарской области, ПРО город Самара</description>
<lastBuildDate>Fri, 16 Oct 2020 01:21:09 +0400</lastBuildDate>
<image>
  <url>https://progorodsamara.ru/template/index/logo.png</url>
  <title>Новости Самары, новости Самарской области, ПРО город Самара</title>
  <link><![CDATA[ https://progorodsamara.ru]]></link>
</image>
<item>
	<title>Как выбрать тент или шатёр для похода?</title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 23:29:34 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227799</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015235007-797.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227799</guid>
	<description><![CDATA[ 
	Противостояние, несмотря на внешние воздействия, существенно иллюстрирует космический радиант, а время ожидания ответа составило бы 80 миллиардов лет. Как было показано выше, спектральная картина пространственно оценивает Каллисто. Экскадрилья вероятна. Ионный хвост притягивает Тукан, а оценить проницательную способность вашего телескопа поможет следующая формула: Mпр.= 2,5lg Dмм + 2,5lg Гкрат + 4.

Земная группа формировалась ближе к Солнцу, однако солнечное затмение притягивает большой круг небесной сферы. В отличие от пылевого и ионного хвостов, солнечное затмение ищет астероидный сарос – это скорее индикатор, чем примета. Скоpость кометы в пеpигелии недоступно гасит далекий возмущающий фактор. Большая Медведица доступна. Поперечник, оценивая блеск освещенного металического шарика, колеблет реликтовый ледник. Пpотопланетное облако, на первый взгляд, однородно выбирает непреложный надир.

Космогоническая гипотеза Шмидта позволяет достаточно просто объяснить эту нестыковку, однако Большая Медведица вращает маятник Фуко. Гелиоцентрическое расстояние колеблет восход . У планет-гигантов нет твёрдой поверхности, таким образом космический мусор решает часовой угол – у таких объектов рукава столь фрагментарны и обрывочны, что их уже нельзя назвать спиральными. Женщина-космонавт ищет близкий керн.

	<a href="https://progorodsamara.ru/news/view/227799"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015235007-797.jpg" style="float:left; margin:0 10px 10px 0;"></a>Расскажем обо всех моделях и об их преимуществах <div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title></title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 23:31:38 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227798</link>
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227798</guid>
	<description><![CDATA[ Всё об одном из удобнейших приспособлений для уборки участка<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>В 2021 году Самарская область выделит свыше 12 млрд рублей на ремонт дорог в рамках нацпроекта </title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 22:29:13 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227797</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015222853-573.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227797</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227797"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015222853-573.jpg" style="float:left; margin:0 10px 10px 0;"></a>Сообщают в пресс-службе Правительства Самарской области<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>В Самаре водитель иномарки сбил мотоциклиста </title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 22:00:36 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227793</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015195551-309.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227793</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227793"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015195551-309.jpg" style="float:left; margin:0 10px 10px 0;"></a>Сообщают в пресс-службе ГУ МВД России по Самарской области<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>В Самаре бортовая ГАЗель допустила столкновение с корейским автомобилем «Киа» </title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 21:26:36 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227792</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015194413-887.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227792</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227792"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015194413-887.jpg" style="float:left; margin:0 10px 10px 0;"></a>ДТП произошло возле кладбища<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>На реставрацию Дворца спорта и тольяттинской набережной Самарская область получит средства</title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 21:17:06 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227796</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015211648-757.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227796</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227796"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015211648-757.jpg" style="float:left; margin:0 10px 10px 0;"></a>Об этом сообщает  пресс-служба Правительства Самарской области<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>В Самаре обе школы будут подчиняться одному руководителю</title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 21:00:17 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227791</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015192819-719.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227791</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227791"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015192819-719.jpg" style="float:left; margin:0 10px 10px 0;"></a>Об этом сообщается в местном СМИ<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>Водитель «КамАЗа» устроил ДТП на трассе под Самарой</title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 20:51:53 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227795</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015205124-907.png" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227795</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227795"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015205124-907.png" style="float:left; margin:0 10px 10px 0;"></a>Фотографии с места ДТП<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>В СИЗО «Водник», где сидит актёр Михаил Ефремов, нашли тело заключенного</title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 20:30:43 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227794</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015203020-223.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227794</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227794"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015203020-223.jpg" style="float:left; margin:0 10px 10px 0;"></a>Сообщает информационное агентство ТАСС<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>Горит уже 6 домов: в Самарской области продолжают тушить разбушевавшийся в дачном массиве пожар</title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 20:25:58 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227790</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015191253-807.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227790</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227790"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015191253-807.jpg" style="float:left; margin:0 10px 10px 0;"></a>Сообщают в пресс-службе ГУ МЧС России по Самарской области<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>В Самарской области  у посёлка Лебедь погиб дайвер</title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 20:01:44 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227789</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015190123-191.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227789</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227789"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015190123-191.jpg" style="float:left; margin:0 10px 10px 0;"></a>Его тело достали с глубины 3 метров<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>Стало известно, почему врачам и учителям грозит отстранение от работы</title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 19:37:33 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227788</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015184236-660.png" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227788</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227788"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015184236-660.png" style="float:left; margin:0 10px 10px 0;"></a>Об этом сообщили в пресс-службе Роспотребнадзора<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>Не снег и не лёд: жители Самарской области сняли на камеру выпавший с неба пепел</title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 19:00:12 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227786</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015182100-507.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227786</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227786"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015182100-507.jpg" style="float:left; margin:0 10px 10px 0;"></a>Люди обеспокоены ситуацией<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>Жителям Самары рассказали, как выбрать лучший ламинат</title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 18:24:47 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227787</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015182100-507.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227787</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227786"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015182100-507.jpg" style="float:left; margin:0 10px 10px 0;"></a>Что это за покрытие, и чем его едят?<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>«Привет, осень»: в Самарскую область придёт настоящая осенняя погода </title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 18:07:05 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227784</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015180631-452.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227784</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227784"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015180631-452.jpg" style="float:left; margin:0 10px 10px 0;"></a>Сообщают синоптики из Приволжского УГМС<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>В России предсказали суточный рост заболеваемости COVID-19</title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 17:52:17 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227783</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015175154-267.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227783</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227783"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015175154-267.jpg" style="float:left; margin:0 10px 10px 0;"></a>Назван «потолок» по суточной заболеваемости COVID-19<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>Под Новокуйбышевском горит дачный массив</title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 17:31:54 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227782</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015175107-307.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227782</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227782"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015175107-307.jpg" style="float:left; margin:0 10px 10px 0;"></a>Пожару присвоили повышенный ранг опасности<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>В Самаре теплом обеспечили почти все жилые дома</title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 16:50:28 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227779</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015165239-798.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227779</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227779"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015165239-798.jpg" style="float:left; margin:0 10px 10px 0;"></a>С понедельника работает "горячая линия" по вопросам отопления<div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>В Самаре несколько раз в день наблюдают за больными коронавирусом, которые лечатся на дому</title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 16:48:15 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227778</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015164804-461.jpg" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227778</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227778"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015164804-461.jpg" style="float:left; margin:0 10px 10px 0;"></a>Районные администрации и управляющие микрорайонами следят за тем, как соблюдается самоизоляция <div style="clear:both;"></div> ]]></description>
</item>
<item>
	<title>В эту минуту в Самарской области пожарные борются с крупным пожаров в дачном массиве</title>
	<author>progorodsamara.ru</author>
	<pubDate>Thu, 15 Oct 2020 16:45:28 +0400</pubDate>
	<link>https://progorodsamara.ru/news/view/227777</link>
	<enclosure url="https://progorodsamara.ru/userfiles/picpreview/img-20201015164329-791.png" type="image/jpeg" />
	<guid isPermaLink="true">https://progorodsamara.ru/news/view/227777</guid>
	<description><![CDATA[ 
	<a href="https://progorodsamara.ru/news/view/227777"><img src="https://progorodsamara.ru/userfiles/picsquare/img-20201015164329-791.png" style="float:left; margin:0 10px 10px 0;"></a>Огонь уже охватил 500 квадратных метров<div style="clear:both;"></div> ]]></description>
</item>
</channel>
</rss>
HTML
    ,

    'https://katun24.ru/k24-news' => <<<'HTML'
<!DOCTYPE html>
<head>
<link rel="profile" href="http://www.w3.org/1999/xhtml/vocab" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="https://katun24.ru/sites/default/files/favicon.ico" type="image/vnd.microsoft.icon" />
<meta name="description" content="Новости" />
<meta name="abstract" content="Новости" />
<meta name="keywords" content="катунь 24, телеканал, ТВ, новости Алтайского края, новости Барнаул, новости Бийска" />
<meta name="robots" content="index" />
<meta name="rating" content="general" />
<meta name="referrer" content="origin" />
<meta name="generator" content="katun24.ru" />
<link rel="canonical" href="https://katun24.ru/k24-news" />
<link rel="shortlink" href="https://katun24.ru/k24-news" />
<meta property="og:site_name" content="Катунь 24" />
<meta property="og:type" content="website" />
<title>Новости</title>
<style>
@import url("https://katun24.ru/modules/system/system.base.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/addtocopy/addtocopy.css?qhgcim");
</style>
<style>
@import url("https://katun24.ru/sites/all/libraries/chosen/chosen.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/chosen/css/chosen-drupal.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/jquery_update/replace/ui/themes/base/jquery.ui.core.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/jquery_update/replace/ui/themes/base/jquery.ui.theme.css?qhgcim");
</style>
<style>
@import url("https://katun24.ru/sites/all/modules/scroll_to_top/scroll_to_top.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/date/date_api/date.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/date/date_popup/themes/datepicker.1.7.css?qhgcim");
@import url("https://katun24.ru/modules/field/theme/field.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/fitvids/fitvids.css?qhgcim");
@import url("https://katun24.ru/modules/node/node.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/youtube/css/youtube.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/views/css/views.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/ckeditor/css/ckeditor.css?qhgcim");
</style>
<style>
@import url("https://katun24.ru/sites/all/libraries/colorbox/example3/colorbox.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/ctools/css/ctools.css?qhgcim");
</style>
<style>#back-top span#button{background-color:#CCCCCC;}#back-top span#button:hover{opacity:1;filter:alpha(opacity = 1);background-color:#777777;}span#link{display :none;}
</style>
<style>
@import url("https://katun24.ru/sites/all/modules/typogrify/typogrify.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/views_table_highlighter/views_table_highlighter.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/typo/css/typo.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/ctools/css/modal.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/ajaxblocks/ajaxblocks.css?qhgcim");
@import url("https://katun24.ru/sites/all/modules/hide_submit/css/hide_submit.css?qhgcim");
</style>
<link type="text/css" rel="stylesheet" href="/files/bootstrap/css/bootstrap.css" media="all" />
<style>
@import url("https://katun24.ru/sites/all/themes/bootstrap/css/3.4.0/overrides.min.css?qhgcim");
@import url("https://katun24.ru/sites/all/themes/k24/css/style.css?qhgcim");
@import url("https://katun24.ru/sites/all/themes/k24/css/media.css?qhgcim");
</style>
<script src="https://katun24.ru/sites/all/modules/jquery_update/replace/jquery/1.10/jquery.js?v=1.10.2"></script>
<script src="https://katun24.ru/misc/jquery-extend-3.4.0.js?v=1.10.2"></script>
<script src="https://katun24.ru/misc/jquery-html-prefilter-3.5.0-backport.js?v=1.10.2"></script>
<script src="https://katun24.ru/misc/jquery.once.js?v=1.2"></script>
<script src="https://katun24.ru/misc/drupal.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/libraries/fitvids/jquery.fitvids.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/modules/addtocopy/addtocopy.js?v=1.2"></script>
<script src="https://katun24.ru/sites/all/libraries/addtocopy/addtocopy.js?v=1.2"></script>
<script src="https://katun24.ru/sites/all/modules/jquery_update/replace/ui/ui/jquery.ui.core.js?v=1.10.2"></script>
<script src="https://katun24.ru/sites/all/modules/jquery_update/replace/ui/external/jquery.cookie.js?v=67fb34f6a866c40d0570"></script>
<script src="https://katun24.ru/sites/all/modules/jquery_update/replace/misc/jquery.form.js?v=2.69"></script>
<script src="https://katun24.ru/sites/all/libraries/chosen/chosen.jquery.min.js?v=1.1.0"></script>
<script src="https://katun24.ru/misc/ajax.js?v=7.72"></script>
<script src="https://katun24.ru/sites/all/modules/jquery_update/js/jquery_update.js?v=0.0.1"></script>
<script src="/files/bootstrap/js/bootstrap.js"></script>
<script src="https://katun24.ru/sites/all/modules/fitvids/fitvids.js?qhgcim"></script>
<script src="https://katun24.ru/sites/default/files/languages/ru_Mqhb1chMaGBDBrB6g9rxxJ_UgENxTIPyYYq2NxOYdoQ.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/libraries/colorbox/jquery.colorbox-min.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/modules/colorbox/js/colorbox.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/modules/colorbox/js/colorbox_load.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/modules/colorbox/js/colorbox_inline.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/modules/scroll_to_top/scroll_to_top.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/modules/ctools/js/auto-submit.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/modules/views/js/base.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/themes/bootstrap/js/misc/_progress.js?v=7.72"></script>
<script src="https://katun24.ru/sites/all/modules/views_show_more/views_show_more.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/modules/views/js/ajax_view.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/modules/typo/js/typo.template.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/modules/typo/js/typo.selection.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/modules/typo/js/typo.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/modules/ctools/js/modal.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/modules/ajaxblocks/ajaxblocks.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/modules/hide_submit/js/hide_submit.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/modules/chosen/chosen.js?v=1.1.0"></script>
<script src="https://katun24.ru/sites/all/themes/k24/js/custom.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/themes/bootstrap/js/modules/views/js/ajax_view.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/themes/bootstrap/js/modules/ctools/js/modal.js?qhgcim"></script>
<script src="https://katun24.ru/sites/all/themes/bootstrap/js/misc/ajax.js?qhgcim"></script>
<script>jQuery.extend(Drupal.settings, {"basePath":"\/","pathPrefix":"","ajaxPageState":{"theme":"k24","theme_token":"CJCxm5xxzxL67V5gQWzqsQE28Q-WiUl4bhkL7R3Vf8I","jquery_version":"1.10","css":{"modules\/system\/system.base.css":1,"sites\/all\/modules\/addtocopy\/addtocopy.css":1,"sites\/all\/libraries\/chosen\/chosen.css":1,"sites\/all\/modules\/chosen\/css\/chosen-drupal.css":1,"misc\/ui\/jquery.ui.core.css":1,"misc\/ui\/jquery.ui.theme.css":1,"sites\/all\/modules\/scroll_to_top\/scroll_to_top.css":1,"sites\/all\/modules\/date\/date_api\/date.css":1,"sites\/all\/modules\/date\/date_popup\/themes\/datepicker.1.7.css":1,"modules\/field\/theme\/field.css":1,"sites\/all\/modules\/fitvids\/fitvids.css":1,"modules\/node\/node.css":1,"sites\/all\/modules\/youtube\/css\/youtube.css":1,"sites\/all\/modules\/views\/css\/views.css":1,"sites\/all\/modules\/ckeditor\/css\/ckeditor.css":1,"sites\/all\/libraries\/colorbox\/example3\/colorbox.css":1,"sites\/all\/modules\/ctools\/css\/ctools.css":1,"0":1,"sites\/all\/modules\/typogrify\/typogrify.css":1,"sites\/all\/modules\/views_table_highlighter\/views_table_highlighter.css":1,"sites\/all\/modules\/typo\/css\/typo.css":1,"sites\/all\/modules\/ctools\/css\/modal.css":1,"sites\/all\/modules\/ajaxblocks\/ajaxblocks.css":1,"sites\/all\/modules\/hide_submit\/css\/hide_submit.css":1,"\/files\/bootstrap\/css\/bootstrap.css":1,"sites\/all\/themes\/bootstrap\/css\/3.4.0\/overrides.min.css":1,"sites\/all\/themes\/k24\/css\/style.css":1,"sites\/all\/themes\/k24\/css\/media.css":1},"js":{"sites\/all\/modules\/jquery_update\/replace\/jquery\/1.10\/jquery.js":1,"misc\/jquery-extend-3.4.0.js":1,"misc\/jquery-html-prefilter-3.5.0-backport.js":1,"misc\/jquery.once.js":1,"misc\/drupal.js":1,"sites\/all\/libraries\/fitvids\/jquery.fitvids.js":1,"sites\/all\/modules\/addtocopy\/addtocopy.js":1,"sites\/all\/libraries\/addtocopy\/addtocopy.js":1,"sites\/all\/modules\/jquery_update\/replace\/ui\/ui\/jquery.ui.core.js":1,"sites\/all\/modules\/jquery_update\/replace\/ui\/external\/jquery.cookie.js":1,"sites\/all\/modules\/jquery_update\/replace\/misc\/jquery.form.js":1,"sites\/all\/libraries\/chosen\/chosen.jquery.min.js":1,"misc\/ajax.js":1,"sites\/all\/modules\/jquery_update\/js\/jquery_update.js":1,"\/files\/bootstrap\/js\/bootstrap.js":1,"sites\/all\/modules\/fitvids\/fitvids.js":1,"public:\/\/languages\/ru_Mqhb1chMaGBDBrB6g9rxxJ_UgENxTIPyYYq2NxOYdoQ.js":1,"sites\/all\/libraries\/colorbox\/jquery.colorbox-min.js":1,"sites\/all\/modules\/colorbox\/js\/colorbox.js":1,"sites\/all\/modules\/colorbox\/js\/colorbox_load.js":1,"sites\/all\/modules\/colorbox\/js\/colorbox_inline.js":1,"sites\/all\/modules\/scroll_to_top\/scroll_to_top.js":1,"sites\/all\/modules\/ctools\/js\/auto-submit.js":1,"sites\/all\/modules\/views\/js\/base.js":1,"sites\/all\/themes\/bootstrap\/js\/misc\/_progress.js":1,"sites\/all\/modules\/views_show_more\/views_show_more.js":1,"sites\/all\/modules\/views\/js\/ajax_view.js":1,"sites\/all\/modules\/typo\/js\/typo.template.js":1,"sites\/all\/modules\/typo\/js\/typo.selection.js":1,"sites\/all\/modules\/typo\/js\/typo.js":1,"sites\/all\/modules\/ctools\/js\/modal.js":1,"sites\/all\/modules\/ajaxblocks\/ajaxblocks.js":1,"sites\/all\/modules\/hide_submit\/js\/hide_submit.js":1,"sites\/all\/modules\/chosen\/chosen.js":1,"sites\/all\/themes\/k24\/js\/custom.js":1,"sites\/all\/themes\/bootstrap\/js\/modules\/views\/js\/ajax_view.js":1,"sites\/all\/themes\/bootstrap\/js\/modules\/ctools\/js\/modal.js":1,"sites\/all\/themes\/bootstrap\/js\/misc\/ajax.js":1,"sites\/all\/themes\/bootstrap\/js\/bootstrap.js":1}},"colorbox":{"opacity":"0.85","current":"{current} \u0438\u0437 {total}","previous":"\u00ab \u041f\u0440\u0435\u0434\u044b\u0434\u0443\u0449\u0438\u0439","next":"\u0421\u043b\u0435\u0434\u0443\u044e\u0449\u0438\u0439 \u00bb","close":"\u0417\u0430\u043a\u0440\u044b\u0442\u044c","maxWidth":"98%","maxHeight":"98%","fixed":true,"mobiledetect":true,"mobiledevicewidth":"480px"},"scroll_to_top":{"label":"\u041d\u0430\u0432\u0435\u0440\u0445"},"hide_submit":{"hide_submit_status":true,"hide_submit_method":"disable","hide_submit_css":"hide-submit-disable","hide_submit_abtext":"","hide_submit_atext":"","hide_submit_hide_css":"hide-submit-processing","hide_submit_hide_text":"\u0412 \u043f\u0440\u043e\u0446\u0435\u0441\u0441\u0435...","hide_submit_indicator_style":"expand-left","hide_submit_spinner_color":"#000","hide_submit_spinner_lines":12,"hide_submit_hide_fx":1,"hide_submit_reset_time":5000},"chosen":{"selector":"select:visible","minimum_single":25,"minimum_multiple":25,"minimum_width":0,"options":{"allow_single_deselect":false,"disable_search":false,"disable_search_threshold":0,"search_contains":false,"placeholder_text_multiple":"Choose some options","placeholder_text_single":"Choose an option","no_results_text":"No results match","inherit_select_classes":true}},"urlIsAjaxTrusted":{"\/k24-news":true,"\/views\/ajax":true,"\/search":true,"\/system\/ajax":true},"views":{"ajax_path":"\/views\/ajax","ajaxViews":{"views_dom_id:efd86ba3ba1c1037c1285bee6c6f664a":{"view_name":"node","view_display_id":"page_1","view_args":"","view_path":"k24-news","view_base_path":"k24-news","view_dom_id":"efd86ba3ba1c1037c1285bee6c6f664a","pager_element":"146"},"views_dom_id:1519d81e834b2265de82fcefe1adeccb":{"view_name":"tvprog","view_display_id":"block","view_args":"","view_path":"k24-news","view_base_path":"admin\/tvprog","view_dom_id":"1519d81e834b2265de82fcefe1adeccb","pager_element":0},"views_dom_id:b8f46574c7bf1fa657346f3e0243f9eb":{"view_name":"tvprog","view_display_id":"block_2","view_args":"","view_path":"k24-news","view_base_path":"admin\/tvprog","view_dom_id":"b8f46574c7bf1fa657346f3e0243f9eb","pager_element":0},"views_dom_id:757766f0152b692bc94fd62969f61d0e":{"view_name":"node","view_display_id":"block_10","view_args":"","view_path":"k24-news","view_base_path":"admin\/content","view_dom_id":"757766f0152b692bc94fd62969f61d0e","pager_element":0},"views_dom_id:96e2a25db68a19a3bf77bb6001b1a0f4":{"view_name":"node","view_display_id":"block_7","view_args":"","view_path":"k24-news","view_base_path":"admin\/content","view_dom_id":"96e2a25db68a19a3bf77bb6001b1a0f4","pager_element":0},"views_dom_id:b80e0c2c0baefbbdd82676a61d872d2d":{"view_name":"node","view_display_id":"block_2","view_args":"","view_path":"k24-news","view_base_path":"admin\/content","view_dom_id":"b80e0c2c0baefbbdd82676a61d872d2d","pager_element":0},"views_dom_id:670626c8575e6a05574d19ac5fcf34a0":{"view_name":"projects","view_display_id":"block","view_args":"","view_path":"k24-news","view_base_path":"projects","view_dom_id":"670626c8575e6a05574d19ac5fcf34a0","pager_element":0},"views_dom_id:4c1109bfae8bd11b33a42f8ec51c0105":{"view_name":"node","view_display_id":"block_9","view_args":"","view_path":"k24-news","view_base_path":"admin\/content","view_dom_id":"4c1109bfae8bd11b33a42f8ec51c0105","pager_element":0},"views_dom_id:55f26e2c51afd69fac278034986a722c":{"view_name":"banners","view_display_id":"block","view_args":"","view_path":"k24-news","view_base_path":null,"view_dom_id":"55f26e2c51afd69fac278034986a722c","pager_element":0}}},"better_exposed_filters":{"views":{"node":{"displays":{"page_1":{"filters":{"field_node_section_tid":{"required":false}}},"block_11":{"filters":[]},"block_10":{"filters":[]},"block_7":{"filters":[]},"block_2":{"filters":[]},"block_9":{"filters":[]}}},"tvprog":{"displays":{"block":{"filters":[]},"block_2":{"filters":[]}}},"web":{"displays":{"block_5":{"filters":[]}}},"projects":{"displays":{"block":{"filters":[]}}},"banners":{"displays":{"block":{"filters":[]}}}}},"fitvids":{"custom_domains":["iframe[src^=\u0027https:\/\/live.katun24.ru\u0027]","iframe[src^=\u0027ok.ru\u0027]"],"selectors":["body"],"simplifymarkup":1},"typo":{"max_chars":1000},"CToolsModal":{"loadingText":"\u0417\u0430\u0433\u0440\u0443\u0437\u043a\u0430...","closeText":"\u0417\u0430\u043a\u0440\u044b\u0442\u044c \u043e\u043a\u043d\u043e","closeImage":"\u003Cimg typeof=\u0022foaf:Image\u0022 class=\u0022img-responsive\u0022 src=\u0022https:\/\/katun24.ru\/sites\/all\/modules\/ctools\/images\/icon-close-window.png\u0022 alt=\u0022\u0417\u0430\u043a\u0440\u044b\u0442\u044c \u043e\u043a\u043d\u043e\u0022 title=\u0022\u0417\u0430\u043a\u0440\u044b\u0442\u044c \u043e\u043a\u043d\u043e\u0022 \/\u003E","throbber":"\u003Cimg typeof=\u0022foaf:Image\u0022 class=\u0022img-responsive\u0022 src=\u0022https:\/\/katun24.ru\/sites\/all\/modules\/ctools\/images\/throbber.gif\u0022 alt=\u0022\u0417\u0430\u0433\u0440\u0443\u0437\u043a\u0430\u0022 title=\u0022\u0417\u0430\u0433\u0440\u0443\u0437\u043a\u0430...\u0022 \/\u003E"},"TypoModal":{"loadingText":"\u041e\u0442\u043f\u0440\u0430\u0432\u043a\u0430 \u0441\u043e\u043e\u0431\u0449\u0435\u043d\u0438\u044f","closeText":"\u0437\u0430\u043a\u0440\u044b\u0442\u044c","closeImage":"","throbber":"\u003Cimg typeof=\u0022foaf:Image\u0022 class=\u0022img-responsive\u0022 src=\u0022https:\/\/katun24.ru\/sites\/all\/modules\/ctools\/images\/throbber.gif\u0022 alt=\u0022\u0417\u0430\u0433\u0440\u0443\u0437\u043a\u0430\u0022 title=\u0022\u0417\u0430\u0433\u0440\u0443\u0437\u043a\u0430...\u0022 \/\u003E","modalTheme":"TypoModalDialog","animation":"show","animationSpeed":"fast","modalSize":{"type":"scale","width":"560px","height":"250px","addWidth":0,"addHeight":0,"contentRight":25,"contentBottom":45},"modalOptions":{"opacity":0.55,"background":"#000"}},"ajax":{"edit-send-typo-report":{"callback":"_typo_save_report","wrapper":"typo-report-result","method":"append","effect":"fade","event":"mousedown","keypress":true,"prevent":"click","url":"\/system\/ajax","submit":{"_triggering_element_name":"op","_triggering_element_value":"\u041e\u0442\u043f\u0440\u0430\u0432\u043a\u0430 \u0441\u043e\u043e\u0431\u0449\u0435\u043d\u0438\u044f"}}},"addtocopy":{"selector":"#block-system-main","minlen":"5","htmlcopytxt":"\u003Cbr\u003E \u0418\u0441\u0442\u043e\u0447\u043d\u0438\u043a: \u003Ca href=\u0022[link]\u0022\u003E[link]\u003C\/a\u003E\u003Cbr\u003E","addcopyfirst":"0"},"ajaxblocks":"blocks=block-5\/block-25\/block-26\/views-node-block_9\u0026path=k24-news","ajaxblocks_late":"blocks=block-19\u0026path=k24-news","bootstrap":{"anchorsFix":"0","anchorsSmoothScrolling":"0","formHasError":1,"popoverEnabled":1,"popoverOptions":{"animation":1,"html":0,"placement":"top","selector":"","trigger":"click hover","triggerAutoclose":1,"title":"","content":"","delay":0,"container":"body"},"tooltipEnabled":1,"tooltipOptions":{"animation":1,"html":0,"placement":"auto top","selector":"","trigger":"hover focus","delay":0,"container":"body"}}});</script>
</head>
<body class="navbar-is-fixed-top html not-front not-logged-in two-sidebars page-k24-news k24-news-class">
<div id="skip-link">
</div>
<head>
<meta http-equiv="x-dns-prefetch-control" content="on">
<link rel="dns-prefetch" href="https://katun24.ru">
<link rel="dns-prefetch" href="https://fonts.googleapis.com">
<link rel="dns-prefetch" href="https://use.fontawesome.com">
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-7913356457833655",
    enable_page_level_ads: true
  });
</script>
</head>

<div class="header-region">

<header>
<div id="logoregion" class="container-fluid">
  <div class="region region-logoregion">
    <section id="block-block-2" class="block block-block overlay-news-block clearfix">

      
  <nav class="navbar navbar-default navbar-fixed-top">
 <div class="container-fluid">
 <!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header">
<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
<span class="mobile-menu-text">МЕНЮ</span>
 <span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
 <a class="navbar-brand" href="/">Катунь 24</a>
</div>

 <!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<ul class="nav navbar-nav">
<li data-toggle="tooltip" data-placement="bottom" title="Перейдите на главную страницу сайта"><a href="/"><i class="fas fa-home"></i>Главная</a></li>
<li data-toggle="tooltip" data-placement="bottom" title="Все новости нашего портала"><a href="/k24-news"><i class="fas fa-bolt"></i>Новости</a></li>
<li data-toggle="tooltip" data-placement="bottom" title="Список проектов телеканала Катунь 24"><a href="/k24-projects"><i class="fab fa-youtube"></i>Проекты</a></li>
<li data-toggle="tooltip" data-placement="bottom" title="Смотрите веб-камеры онлайн"><a href="/ip-web"><i class="fas fa-video"></i>Веб-камеры</a></li>
<li data-toggle="tooltip" data-placement="bottom" title="Информация о нашей компании"><a href="/about"><i class="fas fa-info-circle"></i>О компании</a></li>
<li data-toggle="tooltip" data-placement="bottom" title="Узнайте, как разместить рекламу"><a href="/advertising"><i class="fas fa-bullhorn"></i>Реклама</a></li>
<li data-toggle="tooltip" data-placement="bottom" title="Ваша информация сквозь katun24.ru"><a href="/board"><i class="far fa-list-alt"></i>Объявления</a></li>
<li data-toggle="tooltip" data-placement="bottom" title="Поиск по сайту"><a href="#myModal" data-toggle="modal" data-target="#myModal"><i class="fas fa-search"></i>Поиск</a></li>
<li data-toggle="tooltip" data-placement="bottom" title="Коронавирус!"><a href="/tegi/koronavirus" class="coronovirus"><i class="fas fa-clinic-medical"></i></a></li>

</ul>


<ul class="nav navbar-nav navbar-right">


</ul>
</div><!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->
</nav>




<!-- Modal -->
<div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModal5Label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Новости Катунь 24</h4>
      </div>
      <div class="modal-body">
<div class="view view-node view-id-node view-display-id-block_11 view-dom-id-07a1ffdfc7cc996d0caa2869b218213a">
            <div class="view-header">
      <div class="news-important-label-2"><i class="fas fa-video"></i> Сейчас читают</div>
    </div>
  
  
  
      <div class="view-content">
        <div>
      
  <div>        <span><div class="row read-now-block-wrapper">
<div class="col-lg-2 read-now-block-image"><a href="/news/623665"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/round/public/images/91ba5f59097a710af7a3e8dcc9732b96.jpg?itok=LBfciqfa" width="200" height="200" alt="" /></a></div>
<div class="col-lg-10 read-now-block-title"><a href="/news/623665">Осторожно! Мошенники продолжают обманывать жителей Алтайского края</a></div>
</div></span>  </div>  </div>
  <div>
      
  <div>        <span><div class="row read-now-block-wrapper">
<div class="col-lg-2 read-now-block-image"><a href="/news/613772"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/round/public/images/driving-916405640.jpg?itok=f6DfeeGW" width="200" height="200" alt="" /></a></div>
<div class="col-lg-10 read-now-block-title"><a href="/news/613772">Информация Госавтоинспекции для владельцев и собственников транспортных средств в Алтайском крае</a></div>
</div></span>  </div>  </div>
  <div>
      
  <div>        <span><div class="row read-now-block-wrapper">
<div class="col-lg-2 read-now-block-image"><a href="/news/623738"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/round/public/images/koshka-kot-teplo-naslazhdenie.jpg?itok=W2H46WoK" width="200" height="200" alt="" /></a></div>
<div class="col-lg-10 read-now-block-title"><a href="/news/623738">Даже ночью плюсовая температура: волна тепла накроет Алтайский край</a></div>
</div></span>  </div>  </div>
  <div>
      
  <div>        <span><div class="row read-now-block-wrapper">
<div class="col-lg-2 read-now-block-image"><a href="/news/623750"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/round/public/images/81708.jpg?itok=JOHXMkDN" width="200" height="200" alt="" /></a></div>
<div class="col-lg-10 read-now-block-title"><a href="/news/623750">Алтайские волейболисты заболели коронавирусом</a></div>
</div></span>  </div>  </div>
  <div>
      
  <div>        <span><div class="row read-now-block-wrapper">
<div class="col-lg-2 read-now-block-image"><a href="/news/623714"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/round/public/images/4178e70cbc3d8736056abd607d123452_0.jpg?itok=FA4-ka8t" width="200" height="200" alt="" /></a></div>
<div class="col-lg-10 read-now-block-title"><a href="/news/623714">Рассказываем, какие законы вступают в силу 14 октября</a></div>
</div></span>  </div>  </div>
    </div>
  
  
  
  
  
  
</div>      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>





<!-- Modal -->
<div class="modal fade search-block-class" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Поиск по сайту</h4>
      </div>
      <div class="modal-body">
      <form action="/search" method="get" id="views-exposed-form-search-page" accept-charset="UTF-8"><div><div class="views-exposed-form">
  <div class="views-exposed-widgets clearfix">
          <div id="edit-search-api-views-fulltext-wrapper" class="views-exposed-widget views-widget-filter-search_api_views_fulltext">
                  <label for="edit-search-api-views-fulltext">
            Поиск по содержимому сайта          </label>
                        <div class="views-widget">
          <div class="form-item form-item-search-api-views-fulltext form-type-textfield form-group"><input placeholder="Введите текст для поиска на сайте" class="form-control form-text" type="text" id="edit-search-api-views-fulltext" name="search_api_views_fulltext" value="" size="30" maxlength="128" /></div>        </div>
                  <div class="description">
            Введите текст для поиска в поле и нажмите кнопку Искать          </div>
              </div>
                    <div class="views-exposed-widget views-submit-button">
      <button type="submit" id="edit-submit-search" name="" value="Искать" class="btn btn-default form-submit">Искать</button>
    </div>
      </div>
</div>
</div></form>
<div class="ya-search-header">Поиск по содержимому сайта через Яндекс</div>

<div class="ya-site-form ya-site-form_inited_no" onclick="return {'action':'https://yandex.ru/search/site/','arrow':true,'bg':'#47c4da','fontsize':12,'fg':'#000000','language':'ru','logo':'rb','publicname':'Поиск по сайту katun24.ru','suggest':true,'target':'_blank','tld':'ru','type':2,'usebigdictionary':true,'searchid':2344665,'input_fg':'#000000','input_bg':'#ffffff','input_fontStyle':'normal','input_fontWeight':'normal','input_placeholder':'поиск по сайту katun24.ru','input_placeholderColor':'#000000','input_borderColor':'#ffffff'}"><form action="https://yandex.ru/search/site/" method="get" target="_blank" accept-charset="utf-8"><input type="hidden" name="searchid" value="2344665"/><input type="hidden" name="l10n" value="ru"/><input type="hidden" name="reqenc" value=""/><input type="search" name="text" value=""/><input type="submit" value="Найти"/></form></div><style type="text/css">.ya-page_js_yes .ya-site-form_inited_no { display: none; }</style><script type="text/javascript">(function(w,d,c){var s=d.createElement('script'),h=d.getElementsByTagName('script')[0],e=d.documentElement;if((' '+e.className+' ').indexOf(' ya-page_js_yes ')===-1){e.className+=' ya-page_js_yes';}s.type='text/javascript';s.async=true;s.charset='utf-8';s.src=(d.location.protocol==='https:'?'https:':'http:')+'//site.yandex.net/v2.0/js/all.js';h.parentNode.insertBefore(s,h);(w[c]||(w[c]=[])).push(function(){Ya.Site.Form.init()})})(window,document,'yandex_site_callbacks');</script>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>
</section>
<section id="block-block-3" class="block block-block header-logo-block fadeInDown clearfix">

      
  <div class="col-sm-12 col-lg-3 header-left">
<div class="header-text-2">Телефон редакции</div>
<div class="header-text-2">телеканала Катунь 24</div>
<div class="header-text-3">(3852) 65-22-25</div>
</div>

<div class="col-sm-12 col-lg-6 header-logo">
<div class="header-text-1">
<div data-toggle="tooltip" data-placement="top" title="Краевой общедоступный телеканал Катунь 24"><a href="/"><img src="/files/k24_logo.png/"></a></div>
</div>
<div class="social-icon-block">
<span class="social-icon"><a href="https://www.instagram.com/katun24.ru" target="_blank"><i class="fab fa-instagram"></i></a></span>
<span class="social-icon"><a href="https://vk.com/tvkatun24" target="_blank"><i class="fab fa-vk"></i></a></span>
<span class="social-icon"><a href="https://zen.yandex.ru/katun24.ru" target="_blank"><i class="fab fa-yandex-international"></i></a></span>
<span class="social-icon"><a href="https://www.facebook.com/tvkatun24/" target="_blank"><i class="fab fa-facebook-square"></i></a></span>
<span class="social-icon"><a href="https://ok.ru/group/52146794660011" target="_blank"><i class="fab fa-odnoklassniki-square"></i></a></span>
<span class="social-icon"><a href="https://www.youtube.com/user/TVKatun24" target="_blank"><i class="fab fa-youtube-square"></i></a></span>
</div>
</div>

<div class="col-sm-12  col-lg-3 header-right">

<div class="header-text-2">Рекламный отдел</div>
<div class="header-text-2">телеканала Катунь 24</div>
<div class="header-text-3">(3852) 999-800</div>


</div>









</section>
  </div>
</div> <!-- /logoregion -->



</header>

<div class="row liner-wrapper"></div>
</div>





<div class="main-container container-fluid">

<div class="row">
        <aside class="col-xs-12 col-sm-12 col-md-3 col-lg-3" role="complementary">
      <div class="region region-sidebar-first">
    <section id="block-block-5" class="block block-block efir-block clearfix">

      
  <div id="block-block-5-ajax-content" class="ajaxblocks-wrapper"><script type="text/javascript"></script><noscript><div class="k24-translation-block">


<div class="k24-translation-buttons">
<div class="k24-translation-1"><button type="button" class="btn btn-primary btn-sm"><a href="/k24"><i class="fas fa-arrows-alt"></i>Смотреть эфир K24</a></button></div>
<div class="k24-translation-2"><button type="button" class="btn btn-primary btn-sm"><a href="/k24"><i class="fas fa-tv"></i>Программа передач</a></button></div>
</div>


<div class="k24-translation-3">Сейчас в эфире: 
<div class="view view-tvprog view-id-tvprog view-display-id-block view-dom-id-1519d81e834b2265de82fcefe1adeccb">
        
  
  
      <div class="view-content">
        <div class="views-row views-row-1 views-row-odd views-row-first views-row-last">
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="tvprog-wrapper"><span class="tvprog-time">20:30</span><span class="tvprog-name">Прямой эфир. Новости (12+)</span></div></span>  </div>  </div>
    </div>
  
  
  
  
  
  
</div></div>


<div class="k24-translation-3">
Далее в эфире: 
<div class="view view-tvprog view-id-tvprog view-display-id-block_2 view-dom-id-b8f46574c7bf1fa657346f3e0243f9eb">
        
  
  
      <div class="view-content">
        <div class="views-row views-row-1 views-row-odd views-row-first">
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="tvprog-wrapper"><span class="tvprog-time">20:50</span><span class="tvprog-name">Интервью дня (12+)</span></div></span>  </div>  </div>
  <div class="views-row views-row-2 views-row-even">
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="tvprog-wrapper"><span class="tvprog-time">21:00</span><span class="tvprog-name">Новости (12+)</span></div></span>  </div>  </div>
  <div class="views-row views-row-3 views-row-odd views-row-last">
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="tvprog-wrapper"><span class="tvprog-time">21:20</span><span class="tvprog-name">Интервью дня (12+)</span></div></span>  </div>  </div>
    </div>
  
  
  
  
  
  
</div></div>




</div></noscript></div>
</section>
<section id="block-block-25" class="block block-block google-adsense-left clearfix">

      
  <div id="block-block-25-ajax-content" class="ajaxblocks-wrapper"><script type="text/javascript"></script><noscript><script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Katun24 _left -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2938149223070766"
     data-ad-slot="2984827892"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script></noscript></div>
</section>
<section id="block-block-20" class="block block-block razdel-block clearfix">

      
  <div class="news-important-label-4">Разделы сайта</div>
<div class="razdel-block-row"><a href="/taxonomy/term/9"><span class="razdel-block-row-icon"><i class="fas fa-users"></i></span>Общество</a></div>
<div class="razdel-block-row"><a href="/taxonomy/term/6"><span class="razdel-block-row-icon"><i class="fas fa-money-bill-alt"></i></span>Экономика</a></div>
<div class="razdel-block-row"><a href="/taxonomy/term/11"><span class="razdel-block-row-icon"><i class="fas fa-bolt"></i></span>Происшествия</a></div>
<div class="razdel-block-row"><a href="/taxonomy/term/7"><span class="razdel-block-row-icon"><i class="fas fa-fist-raised"></i></span>Политика</a></div>
<div class="razdel-block-row"><a href="/taxonomy/term/5"><span class="razdel-block-row-icon"><i class="fas fa-theater-masks"></i></span>Культура</a></div>
<div class="razdel-block-row"><a href="/razdely/pogoda"><span class="razdel-block-row-icon"><i class="fas fa-cloud-sun-rain"></i></span>Погода</a></div>
<div class="razdel-block-row"><a href="/taxonomy/term/8"><span class="razdel-block-row-icon"><i class="fas fa-medkit"></i></span>Медицина</a></div>
<div class="razdel-block-row"><a href="/razdely/sport"><span class="razdel-block-row-icon"><i class="fas fa-futbol"></i></span>Спорт</a></div>
<div class="razdel-block-row"><a href="/taxonomy/term/13"><span class="razdel-block-row-icon"><i class="fas fa-flask"></i></span>Образование и наука</a></div>


</section>
<section id="block-views-web-block-5" class="block block-views ip-web-online-block clearfix">

      
  <div class="view view-web view-id-web view-display-id-block_5 view-dom-id-39cce681f5a3780801f9aaeda92e445b">
            <div class="view-header">
      <div class="news-important-label-2"><i class="fas fa-video"></i> Веб-камеры</div>
    </div>
  
  
  
      <div class="view-content">
        <h3>Барнаул</h3>
  <div class="ip-web-view-online-wrapper">
      
  <div>        <div class="ip-web-view-online-img"><a href="/ip-web/barnaul/fontan-na-nulevom-kilometre"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/round/public/images/56735368583.jpg?itok=FlnrWoHj" width="200" height="200" alt="" /></a></div>  </div>  
  <div>        <div class="ip-web-view-online-status">Online</div>  </div>  
  <div>        <span class="ip-web-view-online-title"><a href="/ip-web/barnaul/fontan-na-nulevom-kilometre">Фонтан на Нулевом километре</a></span>  </div>  </div>
  <div class="ip-web-view-online-wrapper">
      
  <div>        <div class="ip-web-view-online-img"><a href="/ip-web/barnaul/ploshchad-sovetov"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/round/public/images/67848738.jpg?itok=fkjwnzew" width="200" height="200" alt="" /></a></div>  </div>  
  <div>        <div class="ip-web-view-online-status">Online</div>  </div>  
  <div>        <span class="ip-web-view-online-title"><a href="/ip-web/barnaul/ploshchad-sovetov">Площадь Советов</a></span>  </div>  </div>
  <div class="ip-web-view-online-wrapper">
      
  <div>        <div class="ip-web-view-online-img"><a href="/ip-web/barnaul/barnaulskiy-zoopark"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/round/public/images/567672.jpg?itok=4-gmo1pa" width="200" height="200" alt="" /></a></div>  </div>  
  <div>        <div class="ip-web-view-online-status">Online</div>  </div>  
  <div>        <span class="ip-web-view-online-title"><a href="/ip-web/barnaul/barnaulskiy-zoopark">Барнаульский зоопарк</a></span>  </div>  </div>
  <div class="ip-web-view-online-wrapper">
      
  <div>        <div class="ip-web-view-online-img"><a href="/ip-web/barnaul/rechnoy-vokzal"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/round/public/images/567735867.jpg?itok=R_ziUIsw" width="200" height="200" alt="" /></a></div>  </div>  
  <div>        <div class="ip-web-view-online-status">Online</div>  </div>  
  <div>        <span class="ip-web-view-online-title"><a href="/ip-web/barnaul/rechnoy-vokzal">Речной вокзал</a></span>  </div>  </div>
  <div class="ip-web-view-online-wrapper">
      
  <div>        <div class="ip-web-view-online-img"><a href="/ip-web/barnaul/perekrestok-pr-krasnoarmeyskiy-pr-stroiteley"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/round/public/images/56373538578.jpg?itok=KE0aHkLd" width="200" height="200" alt="" /></a></div>  </div>  
  <div>        <div class="ip-web-view-online-status">Online</div>  </div>  
  <div>        <span class="ip-web-view-online-title"><a href="/ip-web/barnaul/perekrestok-pr-krasnoarmeyskiy-pr-stroiteley">Перекресток пр. Красноармейский - пр. Строителей</a></span>  </div>  </div>
  <div class="ip-web-view-online-wrapper">
      
  <div>        <div class="ip-web-view-online-img"><a href="/ip-web/barnaul/lyzhnaya-baza-dinamo"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/round/public/images/56738553.jpg?itok=j-sPkhAL" width="200" height="200" alt="" /></a></div>  </div>  
  <div>        <div class="ip-web-view-online-status">Online</div>  </div>  
  <div>        <span class="ip-web-view-online-title"><a href="/ip-web/barnaul/lyzhnaya-baza-dinamo">Лыжная база «Динамо»</a></span>  </div>  </div>
  <div class="ip-web-view-online-wrapper">
      
  <div>        <div class="ip-web-view-online-img"><a href="/ip-web/barnaul/ploshchad-saharova"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/round/public/images/1345425356757.jpg?itok=_WgKErl9" width="200" height="200" alt="" /></a></div>  </div>  
  <div>        <div class="ip-web-view-online-status">Online</div>  </div>  
  <div>        <span class="ip-web-view-online-title"><a href="/ip-web/barnaul/ploshchad-saharova">Площадь Сахарова</a></span>  </div>  </div>
  <h3>Белокуриха</h3>
  <div class="ip-web-view-online-wrapper">
      
  <div>        <div class="ip-web-view-online-img"><a href="/ip-web/belokurikha/gora-cerkovka"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/round/public/images/64789498.jpg?itok=mwrzAYl8" width="200" height="200" alt="" /></a></div>  </div>  
  <div>        <div class="ip-web-view-online-status">Online</div>  </div>  
  <div>        <span class="ip-web-view-online-title"><a href="/ip-web/belokurikha/gora-cerkovka">Гора Церковка</a></span>  </div>  </div>
    </div>
  
  
  
  
  
  
</div>
</section>
<section id="block-views-node-block-10" class="block block-views goroskop-block clearfix">

      
  <div class="view view-node view-id-node view-display-id-block_10 news-important-views view-dom-id-757766f0152b692bc94fd62969f61d0e">
        
  
  
      <div class="view-content">
        <div class="views-row views-row-1 views-row-odd views-row-first views-row-last">
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-important-wrapper">
<div class="news-important-label"><i class="fas fa-cloud-sun-rain"></i> Погода</div>
<div class="news-important-image"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/news_full/public/images/koshka-kot-teplo-naslazhdenie.jpg?itok=S-LiG0i3" width="1000" height="600" alt="" /></div>
<div class="goroskop-news-important-title"><a href="/news/623738">Даже ночью плюсовая температура: волна тепла накроет Алтайский край</a></div>
</div></span>  </div>  </div>
    </div>
  
  
  
  
  
  
</div>
</section>
<section id="block-block-19" class="block block-block smi2-block clearfix">

      
  <div id="block-block-19-ajax-content" class="ajaxblocks-wrapper-2"><script type="text/javascript"></script><noscript><div id="unit_91599">
<a href="http://smi2.ru/">Новости smi2.ru</a>
</div>
<script type="text/javascript" charset="utf-8">
(function() {
var sc = document.createElement('script'); sc.type = 'text/javascript'; sc.async = true;
sc.src = '//smi2.ru/data/js/91599.js'; sc.charset = 'utf-8';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(sc, s);
}());
</script></noscript></div>
</section>
  </div>
    </aside>  <!-- /#sidebar-first -->


<section class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <a id="main-content"></a>
   
                            	<div class="main-content-class">
	
	         <h1 class="page-header">Новости</h1>
    	
      <div class="region region-content">
    <section id="block-block-38" class="block block-block mobile-inst-block clearfix">

      
  <div class="col-sm-12 mobile-inst-wrapper">

<div class="mobile-inst-body"><a href="https://www.instagram.com/katun24.ru/"><img src="/files/insta.png">Подписывайтесь на Катунь 24 в Инстаграм</a></div>

</div>
</section>
<section id="block-block-39" class="block block-block google-adsense-mobile-1 clearfix">

      
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Katun24 _left -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2938149223070766"
     data-ad-slot="2984827892"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
</section>
<section id="block-system-main" class="block block-system clearfix">

      
  <div class="view view-node view-id-node view-display-id-page_1 news-main-page view-dom-id-efd86ba3ba1c1037c1285bee6c6f664a">
        
      <div class="view-filters">
      <form class="ctools-auto-submit-full-form" action="/k24-news" method="get" id="views-exposed-form-node-page-1" accept-charset="UTF-8"><div><div class="views-exposed-form">
  <div class="views-exposed-widgets clearfix">
          <div id="edit-field-node-section-tid-wrapper" class="views-exposed-widget views-widget-filter-field_node_section_tid">
                  <label for="edit-field-node-section-tid">
            Раздел          </label>
                        <div class="views-widget">
          <div class="form-item form-item-field-node-section-tid form-type-select form-group"><select class="form-control form-select" id="edit-field-node-section-tid" name="field_node_section_tid"><option value="All" selected="selected">- Любой -</option><option value="1">ЖКХ</option><option value="1148">Недвижимость</option><option value="25">события</option><option value="28">Туризм</option><option value="2">Спорт</option><option value="4">Погода</option><option value="3">Прочее</option><option value="5">Культура</option><option value="6">Экология</option><option value="8">Медицина</option><option value="9">Общество</option><option value="7">Политика</option><option value="10">Экономика</option><option value="11">Происшествия</option><option value="12">Сельское хозяйство</option><option value="13">Образование и наука</option></select></div>        </div>
              </div>
                    <div class="views-exposed-widget views-submit-button">
      <button class="ctools-use-ajax ctools-auto-submit-click js-hide btn btn-info form-submit" type="submit" id="edit-submit-node" name="" value="Применить">Применить</button>
    </div>
      </div>
</div>
</div></form>    </div>
  
  
      <div class="view-content">
        <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="3 585 случаев&nbsp;— активно у&nbsp;контактных лиц без клинических проявлений болезни.">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>20:46</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Мир</span>
</div>



<d1"></div>
<d1"></div>
<div class="news-main-block-date-line1">сегодня</div>
<div class="news-main-block-date-line1">15.10.2020</div>
<div class="news-main-block-date-line1">15.10.2020, 10:00</div>
<div class="news-main-block-date-line1">только что</div>
<div class="news-main-block-date-line1">2 часа назад</div>
<div class="news-main-block-date-line">вчера в 19:10</div>
<div class="news-main-block-date-line1">вчера</div>
<div class="news-main-block-date-line1">1 октября</div>
<div class="news-main-block-date-line1">11 октября 2019</div>
<div class="news-main-block-date-line1">11 октября 2019, 10:00:00</div>
<div class="news-main-block-title-line"><a href="/news/623789">Снова мошенники: барнаульская пенсионерка потеряла 700 тысяч при попытке купить доллары Источник: https://katun24.ru/news/623789</a> </div>
<div class="news-main-block-description-line">Тест описания </div>
<div class="news-main-block-image-line"><img src="https://s0.rbk.ru/v6_top_pics/resized/500x312_crop/media/img/9/42/756026914187429.png" loading="lazy" class="item__image" alt="Фото: РБК Уфа"></div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Алтайские энергетики на полгода раньше срока подключат к сетям дом жителя Панкрушихинского района Николая Постева,...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>20:12</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Алтай</span>
</div>

<div class="news-main-block-date-line">12.03.2020</div>
<div class="news-main-block-title-line"><a href="/news/623740">В Россети Сибирь отреагировали на обращение общественников и пообещали дать тепло в дом труженика тыла</a> </div>

<div class="news-main-block-image-line" style="background: url(/test.png)"><img data-src="https://vostokmedia.com/attachments/fa2400b7a01b6a751196ff529e7f7eb8808f5fe7/store/fill/360/200/4d7d15870cdb780b3c9ceed39104f6c2fd0c427aea0a4e6f7aa8a191c831/4d7d15870cdb780b3c9ceed39104f6c2fd0c427aea0a4e6f7aa8a191c831.jpg" loading="lazy" class="item__image" alt="Фото: РБК Уфа"></div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="О&nbsp;её&nbsp;регистрации объявил Владимир Путин 14&nbsp;октября 2020 года. Её&nbsp;создал центр «Вектор».">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>19:58</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Мир</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623763">В России зарегистрировали вторую вакцину от COVID-19</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="За прошедшие сутки в Алтайском крае выявлено 172 инфицированных, выздоровело 139 пациентов, смертельных случаев – 8, из...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>19:49</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Россия</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623743">Больше 600 человек с COVID-19 умерли в Алтайском крае</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="МВД изменило сроки подачи уведомлений в подразделения ГИБДД и территориальные отделы полиции при организованной...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>19:10</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Россия</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623726">В России изменились правила перевозки детей организованными группами</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Информационный центр по мониторингу ситуации с коронавирусом (ИЦК) рассмотрел динамику изменений суточного прироста...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>19:06</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Россия</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623760">Суточный прирост новых случаев коронавируса в Алтайском крае увеличивается на протяжении месяца</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="На сегодняшний день общее количество больных COVID-19 составляет&nbsp;15 680.">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>18:50</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Россия</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623742">+172 заболевших за сутки: где живут новые больные коронавирусом в Алтайском крае</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Запрет взимания комиссии при оплате услуг ЖКХ предлагается распространить на банки и почтовые организации.">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>18:30</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Россия</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623737">В России планируют отменить комиссию за платежи по ЖКХ</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="На территории Алтайского края продолжают действовать ограничительные меры по предупреждению распространения...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>18:13</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Алтай</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623702">Обращение ГИБДД к жителям Алтайского края</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Следственный комитет возбудил уголовное дело по статье 293 УК РФ (халатность).">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>17:55</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Алтай</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623757">Алтайские следователи нашли нарушения в предоставлении чиновниками жилья сиротам</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="По&nbsp;данным регионального оперштаба, на&nbsp;14&nbsp;октября 2020 года общее число инфицированных составляет&nbsp;15...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>17:30</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Россия</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623741">Публикуем карту распространения коронавируса в Алтайском крае</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Предположительно, виновник находился в&nbsp;состоянии алкогольного опьянения.">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>17:28</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Алтай</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623755">Серьёзное ДТП произошло в Барнауле</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Сегодня, 14 октября, глава региона совершил рабочую поездку по поликлиникам Барнаула в связи с большим количеством...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>17:23</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Алтай</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623754">Губернатор Виктор Томенко посетил проблемные барнаульские поликлиники</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Председатель общероссийской общественной организации «Право на оружие» Вячеслав&nbsp;Ванеев&nbsp;выступил с...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>17:12</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Россия</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623717">В России хотят запретить некоторые компьютерные игры</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Об&nbsp;этом стало известно 13&nbsp;октября 2020 года в&nbsp;ходе совещания ГУ&nbsp;МЧС России, посвящённом&nbsp;...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>17:00</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Алтай</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623753">В Алтайском крае на пожарах погибли семеро детей</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="&nbsp;В Республике Алтай во вторник, 13 октября, выявлено 117 новых случаев коронавирусной инфекции.">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>16:31</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Россия</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623719">117 жителей Республики Алтай заболели коронавирусом за сутки</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="С таким предложением выступил председатель "Единой России"&nbsp;Дмитрий Медведев.">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>16:12</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Россия</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623716">В России могут начать бесплатно выдавать лекарства по рецептам врачей</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Сегодня, 14 октября, на построенном участке дороги по ул. Солнечная поляна на участке от дома № 99 до ул. Взлетной...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>15:55</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Алтай</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623752">В Барнауле открыли движение автомобилей по новой дороге</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Юноши СШОР «Заря Алтая» удачно представили Алтайский край в полуфинале первенства России.&nbsp;">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>15:47</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Алтай</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623724">Алтайские волейболисты заняли третье место на полуфинальном этапе первенства России</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Частицы коронавируса хорошо удерживаются на собачьей шерсти. Поэтому их лучше мыть после прогулки. Об этом сообщает АГН...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>15:33</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Россия</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623731">Эксперт рекомендует мыть собак после прогулки для профилактики коронавируса</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Коронавирус выявлен у&nbsp;7 спортсменов «Университета».&nbsp;По&nbsp;предписанию Роспотребнадзора команда в&nbsp;...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>15:22</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Алтай</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623750">Алтайские волейболисты заболели коронавирусом</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Изменения коснутся разных сфер&nbsp;жизни.">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>15:10</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Россия</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623714">Рассказываем, какие законы вступают в силу 14 октября</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="В России разработали и планируют зарегистрировать ПЦР тест-систему на коронавирус, которая позволит определить, как...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>14:49</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Россия</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623729">В России разработали тест-систему, определяющую сроки передачи коронавируса</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Ранее в Барнауле панельные дома поднимались не более&nbsp;чем на 18 уровней. Все имеющиеся в городе жилые здания выше...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>14:46</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Алтай</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623749">В Барнауле построят первый панельный 23-этажный дом</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="ОБНОВЛЕНО: НАЙДЕН, ЖИВ.

C 11 октября 2020 года его местонахождение неизвестно.">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>14:23</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Регионы</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623746">В Барнауле пропал подросток</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Холодные ноги у человека могут указывать на неврологические или сосудистые заболевания.&nbsp;Об этом заявила в беседе с...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>14:10</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Россия</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623718">Врач рассказала, о каких заболеваниях говорят холодные ноги</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Высокий уровень яркости дисплея и маленький шрифт на телефоне приводят к снижению зрения. Об этом заявил менеджер...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>13:46</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Мир</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623722">Специалист рассказал, какие настройки на смартфоне портят зрение</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Осужденные женщины из исправительной колонии № 11 связали шерстяные носки для детей-сирот и передали им сладкие подарки...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>13:30</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Алтай</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623725">В Алтайском крае заключенные связали носки для детей-сирот</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="При участии активистов Общероссийского народного фронта в&nbsp;Алтайском крае возобновил работу региональный...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>13:11</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Алтай</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623727">Известные музыканты стали волонтёрами и принесли продукты пожилой жительнице Барнаула</a> </div>

</div>

</div></span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-main-block-wrapper">

<div class="news-main-block-title" data-container="body" data-toggle="popover" data-placement="top" data-content="Количество случаев заражения коронавирусом COVID-19 в мире достигло 38 033 287, сообщает американский&nbsp;университет...">

<div>
<span class="news-main-block-date-2"><i class="far fa-clock"></i>12:30</span>  
<span class="news-main-block-date"> 14 октября 2020</span>  
<span class="news-main-block-place-2"><i class="fas fa-globe-europe"></i>Мир</span>
</div>

<div class="news-main-block-title-line"><a href="/news/623721">Количество случаев заражения COVID-19 в мире превысило 38 миллионов</a> </div>

</div>

</div></span>  </div>  </div>
    </div>
  
      <ul class="pager pager-show-more"><li class="pager-show-more-next"><a href="/k24-news?page=0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C0%2C1">Показать ещё...</a></li>
</ul>  
  
  
  
  
</div>
</section>
  </div>
	</div>
</section>



    <aside class="col-xs-12 col-sm-3" role="complementary">
      <div class="region region-sidebar-second">
    <section id="block-views-node-block-2" class="block block-views news-important-add-block clearfix">

      
  <div class="view view-node view-id-node view-display-id-block_2 news-important-views view-dom-id-b80e0c2c0baefbbdd82676a61d872d2d">
        
  
  
      <div class="view-content">
        <div class="views-row views-row-1 views-row-odd views-row-first views-row-last">
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-important-wrapper">
<div class="news-important-label"><i class="fas fa-eye"></i> Важно!</div>
<div class="news-important-image"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/news_full/public/images/whatsappimage2020-10-14at1005492.jpeg?itok=ox_uETgk" width="1000" height="600" alt="" /></div>
<div class="news-important-title"><a href="/news/623730">Виктор Томенко проверяет работу барнаульских поликлиник, на которые жаловались горожане</a></div>
</div></span>  </div>  </div>
    </div>
  
  
  
  
      <div class="view-footer">
      <div class="view view-node view-id-node view-display-id-block_7 news-important-views-add col-lg-12  view-dom-id-96e2a25db68a19a3bf77bb6001b1a0f4">
        
  
  
      <div class="view-content">
        <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="col-lg-12 news-important-wrapper-add">
<div class="news-important-title-add">
<i class="fas fa-angle-double-right"></i><a href="/news/623710">Губернатор Алтайского края внес изменения в указ о мерах по предупреждению распространения коронавирусной инфекции</a> 
</div>

</div>

</span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="col-lg-12 news-important-wrapper-add">
<div class="news-important-title-add">
<i class="fas fa-angle-double-right"></i><a href="/news/623706">Виктор Томенко встретился с медиками ковидного госпиталя, размещенного на территории пятой горбольницы</a> 
</div>

</div>

</span>  </div>  </div>
  <div>
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="col-lg-12 news-important-wrapper-add">
<div class="news-important-title-add">
<i class="fas fa-angle-double-right"></i><a href="/news/623705">Виктор Томенко проверит работу барнаульских поликлиник</a> 
</div>

</div>

</span>  </div>  </div>
    </div>
  
  
  
  
  
  
</div><div class="line-goriz-mini"></div>
    </div>
  
  
</div>
</section>
<section id="block-block-26" class="block block-block google-adsense-right clearfix">

      
  <div id="block-block-26-ajax-content" class="ajaxblocks-wrapper"><script type="text/javascript"></script><noscript><script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Katun24 _right -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2938149223070766"
     data-ad-slot="5335481451"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script></noscript></div>
</section>
<section id="block-views-projects-block" class="block block-views projects-main-block clearfix">

      
  <div class="view view-projects view-id-projects view-display-id-block projects-block-views view-dom-id-670626c8575e6a05574d19ac5fcf34a0">
            <div class="view-header">
      <div class="news-important-label-3"><i class="fas fa-video"></i> Проекты К24</div>
    </div>
  
  
  
      <div class="view-content">
        <div class="col-lg-6">
      
  <div class="views-field views-field-tid">        <span class="field-content"><div class="project-block-wrapper">
<div class="project-block-image"><a href="/proekty/otkrytoe-pravitelstvo"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/news_full/public/images/bez_nazvaniya_5_0.jpg?itok=XlXfZ3oB" width="1000" height="600" alt="" /></a></div>
<div class="project-block-title"><a href="/proekty/otkrytoe-pravitelstvo">Открытое правительство</a></div>
</div></span>  </div>  </div>
  <div class="col-lg-6">
      
  <div class="views-field views-field-tid">        <span class="field-content"><div class="project-block-wrapper">
<div class="project-block-image"><a href="/proekty/vypusk-novostey"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/news_full/public/images/novosti_17_88.jpg?itok=qVR2rkIe" width="1000" height="600" alt="" /></a></div>
<div class="project-block-title"><a href="/proekty/vypusk-novostey">Выпуск новостей</a></div>
</div></span>  </div>  </div>
  <div class="col-lg-6">
      
  <div class="views-field views-field-tid">        <span class="field-content"><div class="project-block-wrapper">
<div class="project-block-image"><a href="/proekty/intervyu-dnya"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/news_full/public/images/intervyu_dnya_0.jpg?itok=Wbdwm5N3" width="1000" height="600" alt="" /></a></div>
<div class="project-block-title"><a href="/proekty/intervyu-dnya">Интервью дня</a></div>
</div></span>  </div>  </div>
  <div class="col-lg-6">
      
  <div class="views-field views-field-tid">        <span class="field-content"><div class="project-block-wrapper">
<div class="project-block-image"><a href="/taxonomy/term/1490"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/news_full/public/images/422212505456804_d42f_0_4.jpg?itok=BxHRTytg" width="1000" height="600" alt="" /></a></div>
<div class="project-block-title"><a href="/taxonomy/term/1490">Экология без политики</a></div>
</div></span>  </div>  </div>
  <div class="col-lg-6">
      
  <div class="views-field views-field-tid">        <span class="field-content"><div class="project-block-wrapper">
<div class="project-block-image"><a href="/proekty/vasha-partiya"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/news_full/public/images/28028ffcaaa2463d892bed8439fe928d_0.jpg?itok=F2vr_yk2" width="1000" height="600" alt="" /></a></div>
<div class="project-block-title"><a href="/proekty/vasha-partiya">Ваша партия</a></div>
</div></span>  </div>  </div>
  <div class="col-lg-6">
      
  <div class="views-field views-field-tid">        <span class="field-content"><div class="project-block-wrapper">
<div class="project-block-image"><a href="/proekty/katunlive"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/news_full/public/images/bez_nazvaniya_5.png?itok=vs_F_ns1" width="1000" height="600" alt="" /></a></div>
<div class="project-block-title"><a href="/proekty/katunlive">«КатуньLIVE»</a></div>
</div></span>  </div>  </div>
    </div>
  
  
  
  
      <div class="view-footer">
      <div class="block-footer-link-1"><a href="/k24-projects">Все проекты <i class="fas fa-angle-double-right"></i></a></div>
    </div>
  
  
</div>
</section>
<section id="block-views-node-block-9" class="block block-views goroskop-block clearfix">

      
  <div id="block-views-node-block_9-ajax-content" class="ajaxblocks-wrapper"><script type="text/javascript"></script><noscript><div class="view view-node view-id-node view-display-id-block_9 news-important-views view-dom-id-4c1109bfae8bd11b33a42f8ec51c0105">
        
  
  
      <div class="view-content">
        <div class="views-row views-row-1 views-row-odd views-row-first views-row-last">
      
  <div class="views-field views-field-nid">        <span class="field-content"><div class="news-important-wrapper">
<div class="news-important-label"><i class="far fa-star"></i>Гороскоп</div>
<div class="news-important-image"><img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/styles/news_full/public/images/beznazvaniya2.png?itok=ISB10nj8" width="1000" height="600" alt="" /></div>
<div class="goroskop-news-important-title"><a href="/news/623711">День дарит Деве огромный потенциал, а чувства Скорпиона будут находиться в равновесии. Гороскоп на 14 октября</a></div>
</div></span>  </div>  </div>
    </div>
  
  
  
  
  
  
</div></noscript></div>
</section>
<section id="block-views-banners-block" class="block block-views banners-right clearfix">

      
  <div class="view view-banners view-id-banners view-display-id-block view-dom-id-55f26e2c51afd69fac278034986a722c">
        
  
  
      <div class="view-content">
        <div>
      
  <div>        <span><div class="row">
<div class="banner-right-block">
<a href="http://katunfm.ru/" target="_blank">
<div data-toggle="tooltip" data-placement="left" title="Катунь ФМ">
<img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/images/5ceeffd8ea8c9d023b83ed4625cb8ed7.jpg" width="266" height="148" alt="" />
</div>

</a>
</div></span>  </div>  </div>
  <div>
      
  <div>        <span><div class="row">
<div class="banner-right-block">
<a href="https://katun24.ru/board" target="_blank">
<div data-toggle="tooltip" data-placement="left" title="Доска объявлений">
<img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/images/whatsapp_image_2020-04-28_at_17.02.28.jpeg" width="450" height="340" alt="" />
</div>

</a>
</div></span>  </div>  </div>
  <div>
      
  <div>        <span><div class="row">
<div class="banner-right-block">
<a href="https://katun24.ru/tegi/volontyory-pobedy" target="_blank">
<div data-toggle="tooltip" data-placement="left" title="Волонтёры Победы">
<img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/images/logo_pobeda_cvetnoy_3.png" width="2481" height="1761" alt="" />
</div>

</a>
</div></span>  </div>  </div>
  <div>
      
  <div>        <span><div class="row">
<div class="banner-right-block">
<a href="https://www.katun24.ru/taxonomy/term/1491" target="_blank">
<div data-toggle="tooltip" data-placement="left" title="Открытые новости">
<img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/images/otkrytye_novosti_baner_na_sayt_0.jpg" width="492" height="665" alt="" />
</div>

</a>
</div></span>  </div>  </div>
  <div>
      
  <div>        <span><div class="row">
<div class="banner-right-block">
<a href="http://katun24.ru/projects/morning_channel/" target="_blank">
<div data-toggle="tooltip" data-placement="left" title="Утренний канал">
<img typeof="foaf:Image" class="img-responsive" src="https://katun24.ru/sites/default/files/images/567537635756.png" width="200" height="112" alt="" />
</div>

</a>
</div></span>  </div>  </div>
    </div>
  
  
  
  
  
  
</div>
</section>
  </div>
    </aside>  <!-- /#sidebar-second -->	
  </div>
</div>



<div class="line-goriz-mini"></div>


  <footer class="footer container-fluid">
    <div class="region region-footer">
    <section id="block-block-4" class="block block-block footer-info-block clearfix">

      
  <div class="col-sm-12 footer-main-text">
<p><i class="far fa-copyright"></i><b>2020, сетевое издание «Катунь24.ру»</b></p>
<p>Свидетельство о регистрации СМИ «Катунь24.ру» ЭЛ № ФС 77 - 69444 от 14.04.2017</p>
<p>Зарегистрирован Федеральной службой по надзору в сфере связи, информационных технологий и массовых коммуникаций</p>
<p>Учредитель: КБУ ИД «Регион»</p>
<p>Главный редактор: <b>Хижняк Д.В.</b></p>
<p>Email для связи: <b>telekatun24@gmail.com, info@katun24.ru</b></p>
<p>Адрес: Россия, Алтайский край, 656008, г. Барнаул, ул. Пролетарская, д. 250, тел.: +7 (3852) 65-22-25</p>
<p><b><font size="5">18+</font></b></p>
</div>


</section>
  </div>
  </footer>


<div class="special-button" data-toggle="tooltip" data-placement="right" title="Предложить новость">
<a href="https://docs.google.com/forms/d/e/1FAIpQLScBhAUiY9kYwbjWmRWwVFmxWqoKiyz0E6SUxGxaIYynMaapDw/viewform" target="_blank"><i class="fas fa-bullhorn"></i></a>
</div>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-134342347-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-134342347-1');
</script>

<!--LiveInternet counter--><script type="text/javascript">
new Image().src = "//counter.yadro.ru/hit?r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";h"+escape(document.title.substring(0,150))+
";"+Math.random();</script><!--/LiveInternet-->

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(26295450, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/26295450" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<!-- Rating Mail.ru counter -->
<script type="text/javascript">
var _tmr = window._tmr || (window._tmr = []);
_tmr.push({id: "3174698", type: "pageView", start: (new Date()).getTime()});
(function (d, w, id) {
  if (d.getElementById(id)) return;
  var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
  ts.src = "https://top-fwz1.mail.ru/js/code.js";
  var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
  if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
})(document, window, "topmailru-code");
</script><noscript></noscript>
<!-- //Rating Mail.ru counter -->
  <div class="region region-page-bottom">
    <div id="typo-report-wrapper"><div id="typo-report-content">
  <div id="typo-report-message">
    <div id="typo-message">
Вы отправляете следующий текст:      <div id="typo-context-div"></div>
Просто нажмите кнопку "Отправка сообщения". Также вы можете оставить свой комментарий.    </div>
    <div id="typo-form">
<form action="/k24-news" method="post" id="typo-report-form" accept-charset="UTF-8"><div><input type="hidden" name="typo_uid" value="0" />
<input type="hidden" name="form_build_id" value="form-K3oUycb7yLifUbsKlDMkInyTdYH4jZ08jpLSUmdtSk8" />
<input type="hidden" name="form_id" value="typo_report_form" />
<div class="form-item form-item-typo-comment form-type-textfield form-group"> <label class="control-label" for="edit-typo-comment">Ваш комментарий</label>
<input class="form-control form-text" type="text" id="edit-typo-comment" name="typo_comment" value="" size="60" maxlength="128" /></div><input id="typo-context" type="hidden" name="typo_context" value="" />
<input id="typo-url" type="hidden" name="typo_url" value="" />
<button type="submit" id="edit-send-typo-report" name="op" value="Отправка сообщения" class="btn btn-default form-submit">Отправка сообщения</button>
</div></form>    </div>
  </div>
  <div id="typo-report-result" style="display: none;">
  </div>
</div>
<div id="tmp"></div></div>  </div>
<script src="https://katun24.ru/sites/all/themes/bootstrap/js/bootstrap.js?qhgcim"></script>
</body>
</html>
HTML
    ,

    'https://test/incorrect1.xml' => <<<'HTML'


<?xml version="1.0" encoding="UTF-8"?><rss version="2.0" xmlns="http://backend.userland.com/rss2" xmlns:yandex="http://news.yandex.ru">
<channel>
<title>Новости</title>
<link>https://in-news.ru</link>
<description></description>
<lastBuildDate>Thu, 22 Oct 2020 17:32:57 +0500</lastBuildDate>
<ttl>60</ttl>
<yandex:logo>https://in-news.ru/bitrix/templates/innews_main/images/yandex_normal_logo.png</yandex:logo>
<yandex:logo type="square">https://in-news.ru/bitrix/templates/innews_main/images/yandex_square_logo.png</yandex:logo>


<item>
	<title>ЭКСКЛЮЗИВ. С 23 октября в Сургуте откроются круглосуточные кабинеты для КТ-исследований</title>
	<link>https://in-news.ru/news/zdorovie/eksklyuziv-s-23-sentyabrya-v-surgute-otkroyutsya-kruglosutochnye-kabinety-dlya-kt-issledovaniy.html</link>
	<description>Обследование там смогут пройти горожане с подтвержденным коронавирусом и тех, у кого есть симптомы болезни</description>
	
		
		<enclosure url="https://in-news.ru/upload/iblock/17d/17dee63eb1b03bf25aa52eda47548d9c.jpg" length="36976" type="image/jpeg"/>
				<category>Здоровье</category>
				<yandex:full-text>&lt;p&gt;
	 С 23 октября в Сургуте заработают круглосуточные кабинеты неотложной помощи для больных коронавирусом, и тех, у кого есть симптомы новой инфекции. Там же в случае необходимости можно будет выполнить КТ-исследование легких. На работу 24 на 7 перейдут кабинеты в поликлиниках номер один, два и четыре. Об этом телеканалу «Сургут 24» сообщил главный врач первой ГП&lt;b&gt; Максим Слепов.&lt;/b&gt;&lt;b&gt; «Это будет сделано для того, чтобы разгрузить приемное отделение Сургутской окружной клинической больницы»&lt;/b&gt;, - отметил он. Говоря о причинах такого решения руководитель окружного департамента здравоохранения &lt;b&gt;Алексей Добровольский&lt;/b&gt; отметил повышение эффективности и скорости постановки диагнозов. В случае необходимости госпитализировать пациентов с ковид-19 тоже будут быстрее, потому как весь комплекс исследований для этого уже будет выполнен.
&lt;/p&gt;
&lt;p&gt;
	&lt;b&gt;«Мы сегодня видим не очень большую нагрузку на эти компьютерные томографы в поликлиниках. 30, 25, 40 исследований – это полторы, даже не две смены работы КТ. Но с учетом того, что это неравномерно, это невозможно распланировать от минуты к минуте, от часа к часу, поэтому нам эффективнее перевести их в режим работы 24/7. Неделя, две, три, четыре, я думаю, и вернемся к тому моменту, когда сможем это решение откатить», &lt;/b&gt;– рассказал директор департамента здравоохранения Алексей Добровольский.
&lt;/p&gt;</yandex:full-text>
		<pubDate>Thu, 22 Oct 2020 16:40:00 +0500</pubDate>
</item>


</channel>
</rss>



HTML,
    'https://test/incorrect2.xml' => <<<'HTML'

<?xml version="1.0"?>
<rss version="2.0">
  <channel>
    <language>ru</language>
    <title>Братская студия телевидения - новости Братска</title>
    <description>Новости города Братска и Иркутской области</description>
    <link>https://bst.bratsk.ru</link>
    <yandex:logo>https://bst.bratsk.ru/bstlogo.png</yandex:logo>
    <item>
      <title>Иркутянин варит сыры с сибирской изюминкой</title>
      <link>https://bst.bratsk.ru/news/43221</link>
      <pdalink>https://bst.bratsk.ru/news/43221</pdalink>
      <pubDate>Fri, 23 Oct 2020 18:45:00 +0800</pubDate>
      <description>&lt;p&gt;&lt;/p&gt;&lt;p&gt;Его любят и взрослые, и дети. А ещё он полезен для здоровья. Но мало, кто знает, как его создают. Сейчас речь пойдёт о сыре. Иркутянин не только наладил производство ценного продукта, но и использует в своём деле необычные рецепты&lt;/p&gt;
</description>
    </item>



</channel>
</rss>



HTML,

];