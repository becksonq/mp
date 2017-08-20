# README #

Simple HTML Dom Parser for Yii2
+ дополнение cURL (!)

### Для чего этот репозиторий? ###

С помощью данного расширения для yii2 Вы получаете возможность парсить html-страницы "налету" и очень легко.

### Что за библиотека? ###

Данное расширение использует [Simple HTML DOM library](http://simplehtmldom.sourceforge.net)  

### Установка ###

Для установки с помощью composer напишите в консоли
```
#!
php composer.phar require --prefer-dist darkdrim/yii2-simplehtmldom "dev-default"
```

или добавьте
```
#!
"darkdrim/yii2-simplehtmldom": "dev-default"
```

В секцию require Вашего composer.json файла и запустите команду Install (Composer).

### Использование ###

После установки расширения Вы можете его использовать как показано в коде ниже:

```
#!php

<?= \darkdrim\simplehtmldom\SimpleHTMLDom::file_get_html('http://google.com'); ?>
```

или использовать use
```
#!php

<?php
use darkdrim\simplehtmldom\SimpleHTMLDom as SHD
$html_source = SHD::file_get_html('http://google.com'); 
?>
```

Обновил библиотеку - добавил поддержку cURL запросов.  
Пример работы при помощь cURL:
```
#!php

<?php
        $data = \darkdrim\simplehtmldom\SimpleHTMLDom::file_curl_get_html('http://google.ru');
	if(count($data->find('a'))){
		foreach($data->find('a') as $a){
			echo 'new [a href]: ' . $a->href . '<br />';
		}
	}
	$data->clear();
	unset($data);
?>
```
  
То есть сейчас вместо file_get_contents() можно использовать cURL (например, добавить куки, авторизацию, изменить user agent и другое).
  
Остальные примеры на официальном сайте: http://simplehtmldom.sourceforge.net/