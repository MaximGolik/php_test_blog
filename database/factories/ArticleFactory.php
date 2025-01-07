<?php

namespace Database\Factories;

use App\Entities\Article;
use Faker\Factory as Faker;

class ArticleFactory
{

    public static function create(array $attributes = []): Article
    {
        $faker = Faker::create();

        $article = new Article();

        // Установка заголовка
        $title = $attributes['title'] ?? $faker->sentence;
        $article->setTitle($title);

        // Установка контента
        $content = $attributes['content'] ?? $faker->paragraphs(3, true);
        $article->setContent($content);

        // Установка пользователя
        $user = $attributes['user'] ?? UserFactory::create();
        $article->setUser($user);

        return $article;
    }
}
