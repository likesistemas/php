#!/usr/bin/env php
<?php

function testWriteFile($folder, $createFolder=false, $content='123') {
    if( !file_exists($folder) && $createFolder ) {
        mkdir($folder, 0777, true);
    }

    $src = $folder . "file.txt";
    $myfile = fopen($src, "w") or die("Não conseguiu abrir o arquivo: '{$src}'.");
    fwrite($myfile, $content);
    fclose($myfile);

    $writed = file_get_contents($src) or die("Não conseguiu ler o arquivo: '{$src}'.");
    if($writed != $content) {
        throw new Exception("Conteudo não é identico ao gravado. '{$writed}'");
    }
}

testWriteFile(__DIR__ . "/./temp/");