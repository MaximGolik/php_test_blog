<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\ArticleNotTranslatedException;
use DeepL\Translator;

class TranslationService
{

    private static ?TranslationService $instance = null;

    private Translator $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public static function getInstance(): TranslationService
    {
        if (self::$instance === null) {
            $translator = new Translator(env('DEEPL_API_KEY'));
            self::$instance = new TranslationService($translator);
        }

        return self::$instance;
    }

    public function translate(string $text, string $lang = 'ru'): string
    {
        try {
            $text = $this->translator->translateText($text, null, $lang);
        } catch (\Throwable $e) {
            throw new ArticleNotTranslatedException($e->getMessage());
        }
        return $text . " (translated to $lang)";
    }

}
