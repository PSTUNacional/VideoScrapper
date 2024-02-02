<?php

namespace Data\Repository;

class YoutubeRepository extends Repository
{
    // Lista todos os vídeos
    public function listAll()
    {
        $prepare = $this->conn->prepare("SELECT * FROM data_youtube_content");
        $prepare->execute();
        return $prepare->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }
    
    // Checa se um ID existe no BD
    public function checkVideo( string $id )
    {
        $prepare = $this->conn->prepare("SELECT `video_id` FROM data_youtube_content WHERE `video_id` = :id");
        $prepare->execute(["id"=>$id]);
        return count($prepare->fetchAll(\PDO::FETCH_ASSOC));
    }
    
    // Registra video no BD
    public function registerVideo(
        string $id,
        string $title,
        string $description,
        string $duration,
        string $channel_id,
        string $channel_name,
        string $date)
    {
        $sql = "INSERT INTO data_youtube_content (`video_id`,`title`,`description`, `duration`, `channel`, `channel_name`,`date`) VALUES (:id,:title,:description, :duration, :channelid, :channelname, :date)";
        $prepare = $this->conn->prepare($sql);
        $prepare->execute([
                "id" => $id,
                "title"=>$title,
                "description"=>$description,
                "duration" => $duration,
                "channelid" => $channel_id,
                "channelname" => $channel_name,
                "date"=>$date
            ]);
        return $prepare->fetchAll(\PDO::FETCH_ASSOC) ? : [];
    }
    
    // Lista os canais resistrados no BD
    public function getChannels()
    {
        $sql = "SELECT `channel` as `id`, `channel_name` as `name`, COUNT(*) as total FROM `data_youtube_content` GROUP BY `channel_name`; ";
        $prepare = $this->conn->prepare($sql);
        $prepare->execute();
        return $prepare->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    // Lista todos os vídeos para um canal específico
    public function listVideosByChannel( string $channel )
    {
        $sql = "SELECT * FROM `data_youtube_content` WHERE `channel` = :channel ORDER BY `date` DESC ";
        $prepare = $this->conn->prepare($sql);
        $prepare->execute([
                "channel" => $channel
            ]);
        return $prepare->fetchAll(\PDO::FETCH_ASSOC);
    }
}