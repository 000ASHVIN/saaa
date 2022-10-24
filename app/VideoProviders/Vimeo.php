<?php

namespace App\VideoProviders;

class Vimeo implements VideoProvider
{
    public function getVideos()
    {
        $clientId = '30e848ddb2793916a09323ee5fc1663a923d4082';
        $clientSecret = 'mdMDsVKOF8Zj4pwFoJjZuXSB0dZhvcBKb8Xx9452RYzPQ14Uh7u4rB2lC2lrXvvmW8yJLjdxT23Ah+JnMX6a+pTHwhq/y6sTbBGicf/I3dfFYLWLEs24nRwWai45TBoy';
        $accessToken = '6da4a32ea8cfed021534b95772dcb861';
        $albumId = '3766499';
        $lib = new \Vimeo\Vimeo($clientId, $clientSecret);
        $lib->setToken($accessToken);
        $response = $lib->request('/me/albums/' . $albumId . '/videos');
        $videos = collect([]);
        foreach ($response['body']['data'] as $video) {
            $videos->push(new \App\Videos\Vimeo($video));
        }
        return $videos;
    }

    public function renderVideo($video)
    {
        return view($this->attributes['player_view'], compact('video'));
    }
}