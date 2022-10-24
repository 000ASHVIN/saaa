<?php
use App\Config;
use App\Repositories\Sendinblue\SendingblueRepository;
use App\Unsubscribe;
use Carbon\Carbon;

function calculateDiscount($amount, $discount, $discount_type = 'percentage')
{
    if ($discount_type == 'percentage' && (!is_string($discount) || !str_contains($discount_type, '%')))
        $discount = $discount . '%';

    if (!is_numeric($amount))
        throw new Exception('Non-numeric amount');

    $amount = doubleval($amount);
    if ($amount == 0)
        return 0;

    if ($discount === 0 || $discount === '0%')
        return $amount;

    $is_percentage = false;
    if (is_string($discount)) {
        $parsed = doubleval(trim(str_replace("%", "", $discount)));

        if (str_contains($discount, "%")) {
            if ($parsed > 100 || $parsed < 0)
                throw new Exception('Invalid discount percentage value');

            $is_percentage = true;
        }

        $discount = $parsed;
    }

    if (is_numeric($discount)) {
        if ($is_percentage) {
            if ($discount > 100 || $discount < 0)
                throw new Exception("Percentage discount can't be more than 100 or less than 0");
            return $amount * ($discount / 100);
        } else
            return  $discount;
    }

    throw new Exception('Non-numeric discount');
}

function discount($amount, $discount, $discount_type = 'percentage')
{
    return $amount - calculateDiscount($amount, $discount, $discount_type);
}
function getClasses($array,$model){
    foreach($array as $k=>$arr){
        if(get_class($arr) == get_class($model)){
            return $k;
        }
    }
}
function getSEO()
{
        $finalUrl = \Request::getRequestUri();
        $seoData = App\SeoData::where('route',$finalUrl)->orWhere('route',ltrim($finalUrl,"/"))->first();
        $data = request()->all();
        if($seoData){
            $seoData->meta_title = generateString($seoData->meta_title,$data);
            $seoData->meta_description = generateString($seoData->meta_description,$data);
            return $seoData;
        }
        if(!$seoData){
            $seoData = App\SeoData::where('route',$finalUrl)->orWhere('route',ltrim(\Request::path(),"/"))->orderBy('id','desc')->first();
            $data = request()->all();
            if($seoData){
                $seoData->meta_title = generateString($seoData->meta_title,$data);
                $seoData->meta_description = generateString($seoData->meta_description,$data);
                return $seoData;
            }
            return false;
        }
        return $seoData;
        
}
function generateString($string,$data){
   
        if (preg_match_all('/\#(.*?)\#/', $string, $matches)) {
           
        $subject_variables=[];
        foreach ($matches[1] as $kk => $vv) {
            if(isset($data[$vv]) && !empty($data[$vv])) {
                $subject_variables['#' . $vv . '#'] = $data[$vv];
            }
        }
       
        $string = str_replace(array_keys($subject_variables), array_values($subject_variables), $string);
        $string = str_replace(array_values($matches[0]), '', $string);
       
        return $string;
        }
        return $string;
}
function discountString($discount, $discount_type)
{
    $string = '-';
    if ($discount_type == 'percentage') {
        $string .= $discount . '%';
    } else {
        $string .= 'R' . $discount;
    }
    return $string;
}

function currency($amount, $prefix = 'R&nbsp;')
{
        return $prefix . money_format('%.2n', $amount);
}

function calculateVATComponent($amount, $vat = 14)
{
    return $amount - excludingVAT($amount, $vat);
}

function excludingVAT($amount, $vat = 14)
{
    return ($amount / (100 + $vat)) * 100;
}

/**
 * Format cell number to international format
 * @param $cell
 * @return mixed
 */
function formatCell($cell)
{

    $val1 = str_replace('(', '', $cell);
    $val2 = str_replace(')', '', $val1);
    $val3 = str_replace('-', '', $val2);
    $cell = str_replace(' ', '', $val3);

    return $cell;
}

/**
 * Is link active
 * @param $routes
 * @param bool|false $recurrence
 * @return string
 */
function isActive($routes, $recurrence = false)
{
    if (is_array($routes)) {
        foreach ($routes as $route) {
            $request = str_replace('.', '/', $route);
            if (Request::route()->getName() == $route)
                return 'active';

            if ($recurrence) {
                if (Request::is($request . '/*'))
                    return 'active';
            }
        }
    } else {
        $request = str_replace('.', '/', $routes);
        if (Request::is($request))
            return 'active';

        if ($recurrence) {
            if (Request::is($request . '/*'))
                return 'active';
        }
    }

    return '';
}
function settings($config=""){
    $config = Config::where('options',$config)->first();
    if($config){
        return $config->value;        
    }
    return "";
}
if (! function_exists('mix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param string $path
     * @param string $manifestDirectory
     * @return string
     *
     * @throws \Exception
     */
    function mix($path, $manifestDirectory = '')
    {
        static $manifest;
        $publicFolder = '';
        $rootPath = $_SERVER['DOCUMENT_ROOT'];
        $publicPath = $rootPath . $publicFolder;
        if ($manifestDirectory && ! starts_with($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }
        if (! $manifest) {
            if (! file_exists($manifestPath = ($rootPath . $manifestDirectory.'/mix-manifest.json') )) {
                throw new Exception('The Mix manifest does not exist.');
            }
            $manifest = json_decode(file_get_contents($manifestPath), true);
        }
        if (! starts_with($path, '/')) {
            $path = "/{$path}";
        }
        $path = $publicFolder . $path;

        if (! array_key_exists($path, $manifest)) {
            throw new Exception(
                "Unable to locate Mix file: {$path}. Please check your ".
                'webpack.mix.js output paths and try again.'
            );
        }
        return file_exists($publicPath . ($manifestDirectory.'/hot'))
                    ? "http://localhost:8080{$manifest[$path]}"
                    : $manifestDirectory.$manifest[$path];
    }
}

function getThreadPriorities() {
    return [
        '1' => 'Low',
        '2' => 'Normal',
        '3' => 'High',
        '4' => 'Blocker'
    ];
}

function getThreadStatuses() {
    return [
        'new' => 'New',
        'open' => 'Open',
        'pending' => 'Pending',
        'solved' => 'Solved',
        'closed' => 'Closed',
        'merged' => 'Merged',
        'spam' => 'Spam'
    ];
}

function convertToHandeskThreadStatus($status) {
    $statuses = [
        'new' => 1,
        'open' => 2,
        'pending' => 3,
        'solved' => 4,
        'closed' => 5,
        'merged' => 6,
        'spam' => 7
    ];

    if(isset($statuses[$status])) {
        return $statuses[$status];
    }
    return 1;
}

function convertToWebsiteThreadStatus($status) {
    $statuses = [
        '1' => 'new',
        '2' => 'open',
        '3' => 'pending',
        '4' => 'solved',
        '5' => 'closed',
        '6' => 'merged',
        '7' => 'spam'
    ];

    if(isset($statuses[$status])) {
        return $statuses[$status];
    }
    return 1;
}

function getBankBranches() {

    return $bankCodes = [
        '632005'=>'ABSA Bank',
        '410506'=>'Bank of Athens',
        '481972'=>'Bank Windhoek',
        '462005'=>'Bidvest',
        '470010'=>'Capitec  ',
        '679000'=>'Discovery Bank',
        '250655'=>'First National Bank (FNB)/RMB',
        '280061'=>'First National Bank Lesotho',
        '282672'=>'First National Bank Namibia',
        '287364'=>'First National Bank Swaziland',
        '587000'=>'HSBC',
        '580105'=>'Investec',
        '450105'=>'Mercantile Bank',
        '198765'=>'Nedbank ',
        '460005'=>'Postbank (SAPO)',
        '683000'=>'Sasfin Bank',
        '51001'=>'Standard Bank',
        '87373'=>'Standard Bank Namibia',
        '064967'=>'Stanbic Bank Botswana - Fairgrounds'
        
    ];
}
function url_encode($url)
{
    $url = str_replace("/","#",$url);
    return urlencode($url);
}
function url_decode($url)
{
    $url = urldecode($url);
    $url = str_replace("#","/",$url);
    return $url;
}
function isPractice($invoice,$plans=[])
{
    $items = $invoice->items->where('type','subscription')->pluck('item_id')->toArray();
    $isPractice = false;
    foreach($items as $item)
    {
        if(in_array($item,$plans))
        {
            $isPractice = true;
        }
    }
    return $isPractice;
}


function isJson($string) {
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
 }
 
function trimString($str, $length) {

    if(strlen($str) > $length) {
        $str = substr($str, 0, $length);
        $str .= '...';
    }
    return $str;

}

function getCalenderLink($event,$webinar, $type = null){
    if($type == 'outlook_live') {
        $from = DateTime::createFromFormat('Y-m-d H:i', date_format($event->start_date, 'Y-m-d') . $event->Start_time)->format('Y-m-d\Th:i:s\Z');
        $to = DateTime::createFromFormat('Y-m-d H:i', date_format($event->end_date, 'Y-m-d') . $event->end_time)->format('Y-m-d\Th:i:s\Z');
        return "https://outlook.live.com/calendar/deeplink/compose?path=/calendar/action/compose&rru=addevent&startdt=".urlencode($from)."&enddt=".urlencode($to)."&subject=".urlencode($event->name)."&body=".urlencode($event->short_description)."&location=".urlencode($webinar->first()->url);
    }
    return "";
}

function userHasAccess($user){
    if($user->hasRole('sales')){
        if($user->hasRole('admin') || $user->hasRole('super')){
            return true;
        }
        return false;
    }
    
    return true;
}

function getIcsFileContent($event,$webinar){
    
    $dtstart = DateTime::createFromFormat('Y-m-d H:i', date_format($event->start_date, 'Y-m-d') . $event->Start_time)->format('Ymd\THis');
    $dtend = DateTime::createFromFormat('Y-m-d H:i', date_format($event->end_date, 'Y-m-d') . $event->end_time)->format('Ymd\THis');
    $todaystamp = Carbon::now()->format('Ymd\This\Z');
    $description = strip_tags($event->short_description);
    $titulo_invite = $event->name;
    
    // ICS
    $mail[0]  = "BEGIN:VCALENDAR";
    $mail[1] = "PRODID:-//Google Inc//Google Calendar 70.9054//EN";
    $mail[2] = "VERSION:2.0";
    $mail[3] = "CALSCALE:GREGORIAN";
    $mail[4] = "METHOD:REQUEST";
    $mail[5] = "BEGIN:VEVENT";
    $mail[6] = "DTSTART;TZID=Africa/Johannesburg:" . $dtstart;
    $mail[7] = "DTEND;TZID=Africa/Johannesburg:" . $dtend;
    $mail[8] = "DTSTAMP;TZID=Africa/Johannesburg:" . $todaystamp;
    $mail[9] = "CREATED:" . $todaystamp;
    $mail[10] = "DESCRIPTION:" . $description;
    $mail[11] = "LAST-MODIFIED:" . $todaystamp;
    $mail[12] = "LOCATION:" . ($webinar->first())?$webinar->first()->url:'';
    $mail[13] = "SEQUENCE:0";
    $mail[14] = "STATUS:CONFIRMED";
    $mail[15] = "SUMMARY:" . $titulo_invite;
    $mail[16] = "TRANSP:OPAQUE";
    $mail[17] = "END:VEVENT";
    $mail[18] = "END:VCALENDAR";

    return implode("\r\n", $mail);
}

function blacklistedInSendinblue($email) {
    $sendingblueRepository = new SendingblueRepository();
    $contact = $sendingblueRepository->getContactInfo($email);
    if(isset($contact['id'])) {
        if($contact['emailBlacklisted']) {
            return true;
        }
    }
    return false;
}

function unsubscribed($email) {
    $unsubscribed = Unsubscribe::where('email', $email)->first();
    if($unsubscribed) {
        return true;
    }
    return false;
}

function sendMailOrNot($user, $template, $type = 'system') {
    if($type == 'payment') {
        return true;
    }
    
    $folder_file = explode('.', $template);
    
    if($folder_file[0] == 'invoices' || $folder_file[0] == 'orders') {
        return true;
    }

    $email = null;
    if(is_object($user)) {
        $email = $user->email;
    } elseif(is_array($user)) {
        $email = $user['email'];
    } elseif(is_string($user)) {
        $email = $user;
    }
    
    if($email) {
        if(blacklistedInSendinblue($email) || unsubscribed($email)) {
            return false;
        }    
    }
    
    return true;
}