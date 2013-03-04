<?php
use Symfony\CS\FixerInterface;

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->notName('README.md')
    ->notName('.php_cs')
    ->notName('composer.*')
    ->notName('*.phar')
    ->notName('autoload_*')
    ->exclude('vendor')
    ->in(__DIR__);

return Symfony\CS\Config\Config::create()->finder($finder);
