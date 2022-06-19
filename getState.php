<?php

use App\Game\Parser;

require 'app/Game/Parser.php';

$parser = new Parser();
$stringToParse = file_get_contents('state.txt');
$parsedState = $parser->parse($stringToParse);

$isParsed = file_put_contents(
    'parse.json',
    json_encode($parsedState)

);
