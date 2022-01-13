<?php

class Translation
{
    const DETECT_YA_URL = 'https://translate.yandex.net/api/v1.5/tr.json/detect';

    const TRANSLATE_YA_URL = 'https://translate.yandex.net/api/v1.5/tr.json/translate';


    public $key = "AIza1yCf2zgkmk-nRxdbB4gg49M9GZhmFei55uo";
// лучше - создать типовой config.php и хранить такие вещи в нем
// и назвать его другим нестандартным именем 

    public function init(){
        parent::init();
        	

        if (empty($this->key))
        {
            throw new InvalidConfigException("Field <b>$key</b> is required");

        }
    }

    /**
     * @param $format text format need to translate
     * @return string
     */
    public static function translate_text($format="text")
	// лучше задать значение по умолчанию так  $format=$debug?" Не указана входная переменная для translate_text":""
	// и переменной дебаг можно регулировать вывод отладочных сообщений
    {
        if (empty($this->key)){
            throw new InvalidConfigException("Field <b>$key</b> is required");
            // такая ошибка маловероятно, наличие ключа мы проверили при инициализации, а если объект инициализирован успешно
            // то и ключ есть, если вызвать метод объекта без инициализации ошибка будет другой, с указанием того что объект не инициализирован			
        }

        $values = array(
            'key'     => $this->key,
            'text'    => $_GET['text'],// обратить внимание на разницу между гет и пут - в зависимости от настроек сервера, от размера текста выбрать нужный вариант
            'lang'    => $_GET['lang'],//
            'format'  => $format == "text" ? 'plain' : $format /// ??? так не проще сразу в параметрах входа было передать plain?? а тут не пришлось бы сравнивать 
        );
        //'format'  => $format == "text" ? 'plain' : $format,
        // Лишняя запятая после формат

        $formData = http_build_query($values);

        $ch = curl_init(self::TRANSLATE_YA_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);

        $json = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($json, true);
        if($data['code']==200)
        {
            return $data['text'];
        }
        return $data;// это сообщение будет понятно только разработчику, нужно его расшифровать или подавить своим типа return "Упс, что-то пошло не так ".$data

    }
    

}