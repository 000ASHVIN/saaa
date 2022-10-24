<?php

namespace App\VideoProviders;

interface VideoProvider
{
    public function getVideos();

    public function renderVideo($video);
}