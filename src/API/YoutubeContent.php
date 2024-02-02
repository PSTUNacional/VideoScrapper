You<?php

include($_SERVER['DOCUMENT_ROOT'].'/autoload.php');

use Data\Repository\YoutubeRepository;

$api = new YoutubeRepository;

if($_GET['method'] == 'listall'){
    $result = $api->listAll();
}

if($_GET['method'] == 'listchannels'){
    $result = $api->getChannels();
}

if($_GET['method'] == 'listvideos' && $_GET['channel']){
    $result = $api->listVideosByChannel($_GET['channel']);
}

header('Content-Type: application/json;');
echo json_encode($result);