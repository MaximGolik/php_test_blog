<?php

namespace App\Services;

use DeepL\DeepLException;
use DeepL\Translator;

class TranslationService
{

    private static ?TranslationService $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(): TranslationService
    {
        if (self::$instance === null) {
            self::$instance = new TranslationService();
        }

        return self::$instance;
    }

    #todo в докере 504 ошибка, локально ок, разобраться почему

    public function translate(string $text, string $lang = 'ru'): string
    {
        $translator = new Translator(env('DEEPL_API_KEY'));

        $text = $translator->translateText($text, null, $lang);
        return $text . " (translated to $lang)";
    }

}
