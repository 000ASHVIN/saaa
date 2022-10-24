<?php 

$finalUrl = \Request::getRequestUri();
$seoData = App\SeoData::where('route',$finalUrl)->orWhere('route',ltrim($finalUrl,"/"))->first();
?>
@if($seoData)
<title>{{ $seoData->meta_title }}</title> 
<meta name="description" content="{{ $seoData->meta_description }}">
<meta name="keyword" content="">
<meta name="Author" content=""/>
@endif