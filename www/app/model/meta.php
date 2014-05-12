<?php
### Функция создает список ключевых слов по тексту, а также краткое описание.
### Входящие параметры:
###   - $text - Сообственно текст для которого хотим получить ключевые слова
###   - $keywords - Дополнительные ключевые слова. Будут добавляться в начало полученных.
###   - $description - Дополнительное описание. Будет добавляться в начало полученного.
###                    (Учтите, описание не более 200 символов, поэтому полученное будет
###                     обрезаться с учетом длинны дополнительного)
###
### Выходные параметры:
###   - $meta['keywords'] - Соответственно ключевые слова
###   - $meta['description'] - и описание
###
### Использование:
###   - $meta=create_meta($text);
###   - $meta=create_meta($text, $keywords);
###   - $meta=create_meta($text, 'дополнительные,ключевые,слова');
###   - $meta=create_meta($text, $keywords, $description);
###   - $meta=create_meta($text, 'дополнительные,ключевые,слова', 'дополнительное,описание');
function meta_keywords($text, $keywords='', $description='') 
{
	### Нормализация текста
	$text=trim(stripslashes(preg_replace('/[\r\n\t]/i', ' ', strip_tags($text))));
### Формируем описание из текста, макс.200 за до первого знака пунктуации
$idx=200;
if(!empty($description)) {
	$description=trim($description).' ';
	$idx-=strlen($description);
}
while(!in_array($text[$idx], array('.', '!', '?')))$idx--;
$meta['description']=$description.substr($text, 0, $idx+1);

### Загружаем таблицу общих слов и удаляем эти слова из текста
$name='common-words.txt';
if(file_exists($name)) {
	if($file=fopen($name, 'r')) {
		$data='';
		while(!feof($file)){
			$word=trim(fgets($file));
			if($word[0]=='#')continue;
			$data.=' '.$word;
		}
		fclose($file);
		$data=str_replace(' ', '|', trim($data));
	}
	$text=preg_replace('/\b'.$data.'\b/i', '', $text);
}

### Удаляем из текста все знаки препинаний и пунктуации и преобразуем в массив слов
$text=split(' ', preg_replace('/[^\w]+/i', ' ', $text)); $data='';
foreach($text as $key=>$word) if(strlen($word)>4)$data.=' '.strtolower($word);
$text=split(' ', trim($data)); $size=count($text);
$arr1=array(); $arr2=array(); $arr3=array();

### Строим массив слов отсортированный по частоте вложений в тексте
for($i=0; $i<$size; $i++) {
$word=$text[$i];
if($arr1[$word])$arr1[$word]++; else $arr1[$word]=1;
}
arsort($arr1);
### Строим массив фраз состоящих из двух слов отсортированный по частоте вложений в тексте
for($i=0; $i<$size-1; $i++) {
$word=$text[$i].' '.$text[$i+1];
if($arr2[$word])$arr2[$word]++; else $arr2[$word]=1;
}
arsort($arr2);
### Строим массив фраз состоящих из трех слов отсортированный по частоте вложений в тексте
for($i=0; $i<$size-2; $i++) {
$word=$text[$i].' '.$text[$i+1].' '.$text[$i+2];
if($arr3[$word])$arr3[$word]++; else $arr3[$word]=1;
}
arsort($arr3);

### Выбираем 15 первых слов с максимальной частотой вложений
$data=array(); $i=0;
foreach($arr1 as $word=>$count) {
$data[$word]=$count;
if($i++==16)break;
}
### Выбираем 8 первых фраз состоящих из двух слов с максимальной частотой вложений
$i=0;
	foreach($arr2 as $word=>$count) {
	$data[$word]=$count;
	if($i++==8)break;
}
	### Выбираем 4 первых фраз состоящих из трех слов с максимальной частотой вложений
	$i=0;
	foreach($arr3 as $word=>$count) {
	$data[$word]=$count;
	if($i++==4)break;
	}
	arsort($data); $text='';

	### Переводим массив фраз в текст, опять таки с учетом частот вложений
	foreach($data as $word=>$count) $text.=','.$word; $text=substr($text, 1);
	if(!empty($keywords))$keywords=preg_replace('/,$/i', '', $keywords).',';
	$meta['keywords']=$keywords.$text;

	### Возвращаем полученный результат
	return $meta;
}
?>