<?php

/*
Para debug de erros, habilitar essas definições

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

*/

// Autoload para fazer o import automático das classes
include($_SERVER['DOCUMENT_ROOT'].'/autoload.php');

use Data\Repository\YoutubeRepository;

$rep = new YoutubeRepository;
$max_videos = 60; // Esse parâmetro vai na API do YouTube

// Insira aqui sua chave de API do YouTube. Pode usar um DotEnv
$api_key = ___SUA_API_KEY___;

/*
Lista de ID dos Canais do YouTube.
Nesse caso:
PSTU, Orientação Marxista, Vera, Hertz
*/
$channelList = [
    'UCLAbqyxUoqm8eMvbWiai--A',
    'UCRLEkZpNRoZQBG8kUTBD8vQ',
    'UCn1OyvrwJq_hHfUPL4iIWvg',
    'UCWBk05FplU93JomQ345w24Q'
];

foreach ( $channelList as $channel_id )
{
    // Faz o loop na lista de canais
    $url_youtube = "https://youtube.googleapis.com/youtube/v3/search?part=snippet&channelId=".$channel_id."&order=date&maxResults=".$max_videos."&key=".$api_key;
    $json = file_get_contents($url_youtube);
    $json = json_decode($json,true);

    // Para cada canal, faz a checagem de vídeos
    foreach ( $json['items'] as $video )
    {
        // Para cada vídeo, faz a checagem se já existe no BD
        $check = $rep->checkVideo($id = $video['id']['videoId']);

        // Se não existir no BD
        if($check == 0)
        {
            // Comentários para fins de debug
            echo 'Vídeo novo encontrado.<br/>';

            // Lista de campos que serão armazenados no BD
            $id = $video['id']['videoId'];
            $title = $video['snippet']['title'];
            $description = $video['snippet']['description'];
            $date = $video['snippet']['publishTime'];
            $channel = $channel_id;
            $channel_name = $video['snippet']['channelTitle'];
            
            // Para calcular a duração do vídeo, é preciso fazer o fetch com o VIDEO ID
            $url = "https://youtube.googleapis.com/youtube/v3/videos?part=contentDetails&id=".$id."&key=".$api_key;
            $json = file_get_contents($url);
            $json = json_decode($json,true);
            
            // Calcula a duração
            $duration = $json['items'][0]['contentDetails']['duration'];
            $start = new DateTime('@0'); // Unix epoch
            $start->add(new DateInterval($duration));
            $duration = $start->format('H:i:s');
            
            // Comentários para fins de debug
            echo 'Registrando os dados:<br/>'.$id.'   /   '.$title.'   /   '.$description.'   /   '.$channel.'   /   '.$channel_name.'   /   '.$date.'<br/><br/>';
            
            // Registra no BD
            $rep->registerVideo($id, $title, $description, $duration, $channel, $channel_name, $date);

        } else {
            // Comentários para fins de debug
            echo 'Vídeo já registrado.<br/>';
        }

        echo '<hr/><br/>';
    }
}