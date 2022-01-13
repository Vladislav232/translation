<?php

class Translation
{
    const DETECT_YA_URL = 'https://translate.yandex.net/api/v1.5/tr.json/detect';

    const TRANSLATE_YA_URL = 'https://translate.yandex.net/api/v1.5/tr.json/translate';


    public $key = "AIza1yCf2zgkmk-nRxdbB4gg49M9GZhmFei55uo";
// ключ лучше держать в приватной переменной

    public function init(){
        parent::init();
        // Не вижу смысла в "parent::init();" так, как perent это ключевое слово, 
        //которое используется в дочернем классе для доступа к методу родительского класса
        //а мы и так в родительском классе.

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
    // Предполагаю, что в translate_text($format="text") нужно просто оставить $format
    {
        if (empty($this->key)){
            throw new InvalidConfigException("Field <b>$key</b> is required");      
        }

        $values = array(
            'key'     => $this->key,
            'text'    => $_GET['text'],
            'lang'    => $_GET['lang'],
            'format'  => $format == "text" ? 'plain' : $format
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
        return $data;

    }
    

}