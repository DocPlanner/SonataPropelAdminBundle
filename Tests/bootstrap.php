<?php

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

$loader->add('Sonata\PropelAdminBundle\Tests', __DIR__);
$loader->add('Sonata\TestBundle', __DIR__.'/Fixtures/App/src');
$loader->add('', __DIR__.'/Fixtures/App/app');
