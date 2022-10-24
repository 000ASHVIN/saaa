<?php

namespace App\Videos;

class Vimeo extends Video
{

    public function setReference()
    {
        $this->reference = str_replace('/videos/', '', $this->providerSource['uri']);
    }

    public function setTitle()
    {
        $this->title = $this->providerSource['name'];
    }

    public function setWidth()
    {
        $this->width = $this->providerSource['width'];
    }

    public function setHeight()
    {
        $this->height = $this->providerSource['height'];
    }

    public function setDescription()
    {
        $this->description = $this->providerSource['description'];
    }

    public function setDownloadLink()
    {
        if ($this->providerSource['download'] && count($this->providerSource['download']) > 0) {
            if (count($this->providerSource['download']) > 1)
                $this->downloadLink = $this->providerSource['download'][1]['link']; //SD
            else
                $this->downloadLink = $this->providerSource['download'][0]['link']; //Default
        }
    }
}