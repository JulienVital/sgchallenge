<?php

function recursiveGlob($dir, $ext, $files = []) {
    $globFiles = glob("$dir//"."$ext");
    $globDirs  = glob("$dir//*", GLOB_ONLYDIR);

    foreach ($globDirs as $dir) {
        $files = recursiveGlob($dir, $ext, $files);
    }

    foreach ($globFiles as $file) {
        $files[]= $file;
    }
    return $files;
}
$files = recursiveGlob(__DIR__."/app", '*.php');

$replaces = [
    "/<\?php/" => '',
    "/namespace .+;/" => '',
    "/use .+;/" => '',
];

$content = '<?php' . PHP_EOL . '// Last compile time: ' . date('d/m/y G:i ') . PHP_EOL;

foreach ($files as $file) {
    $fileContent = file_get_contents($file,true);

    foreach ($replaces as $pattern => $replace) {
        $fileContent = preg_replace($pattern, $replace, $fileContent, -1, $count);
    }

    $content .= $fileContent;
}
$target = 'build/CodingGameResult.php';

file_put_contents($target, $content, FILE_USE_INCLUDE_PATH);

echo 'Code compilation finished!' . PHP_EOL;