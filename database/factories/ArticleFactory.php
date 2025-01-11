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

        $title = $attributes['title'] ?? $faker->sentence;
        $article->setTitle($title);

        $content = $attributes['content'] ?? $faker->paragraphs(3, true);
        $article->setContent($content);

        $user = $attributes['user'] ?? UserFactory::create();
        $article->setUser($user);

        return $article;
    }
}
