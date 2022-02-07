<?php

use Doctum\Doctum;
use Symfony\Component\Finder\Finder;
use Doctum\RemoteRepository\GitHubRemoteRepository;

function env($var, $default=false) {
    $env = getenv($var);
    return $env ? $env : $default;
}

$srcdir = env('DOCTUM_SRC_DIR', '/code');
$buildir = env('DOCTUM_BUILD_DIR', '/opt/doctum/build');
$cachedir = env('DOCTUM_CACHE_DIR', '/opt/doctum/cache');
$title = env('DOCTUM_TITLE', 'GOODFOOD API');
$lang = env('DOCTUM_LANG', 'en');

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude([
        'resources', 'tests', 'vendor', 'storage', 'public', 'database', 'config', 'bootstrap', 'routes'
    ])
    ->in($srcdir);

return new Doctum($iterator, [
    'title' => $title,
    'language' => $lang, // Could be 'fr'
    'build_dir' =>  $buildir, // __DIR__ . '/build',
    'cache_dir' => $cachedir, //__DIR__ . '/cache',
]);