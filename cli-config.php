<?php


use Doctrine\ORM\Tools\Console\ConsoleRunner;
use LaravelDoctrine\ORM\IlluminateRegistry;

$registry = app(IlluminateRegistry::class);
$entityManager = $registry->getManager();

return ConsoleRunner::createHelperSet($entityManager);
