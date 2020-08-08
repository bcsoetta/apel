<?php

//echo realpath($argv[1]);
//echo " | ";
//echo realpath($argv[2]);

$result = rename($argv[1], $argv[2]);
echo "result: $result\n";


