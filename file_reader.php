<?php

require ('FileDataSorter.php');

$column     = !empty($argv[1]) ? $argv[1] : 'ID';
$order      = !empty($argv[2]) ? $argv[2] : 'desc';
$fileName   = 'tab_delimited.tsv';

$fileDataSorter = new FileDataSorter($fileName);

try {
    $fileDataSorter
        ->getFileData()
        ->sort($column, $order)
        ->display();

} catch (Exception $e) {
    echo $e->getMessage();
}
