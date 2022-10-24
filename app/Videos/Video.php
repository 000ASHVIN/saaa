<?php

namespace App\Videos;

abstract class Video
{
    protected $providerSource;
    public $reference;
    public $title;
    public $width;
    public $height;
    public $downloadLink;
    public $description;

    public function __construct($providerSource)
    {
        $this->providerSource = $providerSource;
        $this->setReference();
        $this->setTitle();
        $this->setWidth();
        $this->setHeight();
        $this->setDownloadLink();
        $this->setDescription();
        $this->setHours();
        $this->setTag();
        $this->setAmount();
    }

    abstract public function setReference();

    abstract public function setTitle();

    abstract public function setWidth();

    abstract public function setHeight();

    abstract public function setDownloadLink();

    abstract public function setDescription();

    abstract public function setHours();

    abstract public function setTag();

    abstract public function setAmount();
}