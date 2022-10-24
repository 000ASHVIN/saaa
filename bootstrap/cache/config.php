<?php return array (
  'app' => 
  array (
    'theme' => 'taxfaculty',
    'name' => 'The Tax Faculty',
    'email' => 'admin@gmail.com',
    'to_email' => 'sparsh1test@gmail.com',
    'debug' => true,
    'url' => 'http://localhost:8000/',
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'key' => 'xHHlN0Iy25EXGqhPFoZlaw33QUwoikox',
    'cipher' => 'AES-256-CBC',
    'log' => 'single',
    'providers' => 
    array (
      0 => 'Illuminate\\Foundation\\Providers\\ArtisanServiceProvider',
      1 => 'Illuminate\\Auth\\AuthServiceProvider',
      2 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      3 => 'Illuminate\\Bus\\BusServiceProvider',
      4 => 'Illuminate\\Cache\\CacheServiceProvider',
      5 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      6 => 'Illuminate\\Routing\\ControllerServiceProvider',
      7 => 'Illuminate\\Cookie\\CookieServiceProvider',
      8 => 'Illuminate\\Database\\DatabaseServiceProvider',
      9 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      10 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      11 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      12 => 'Illuminate\\Hashing\\HashServiceProvider',
      13 => 'Illuminate\\Mail\\MailServiceProvider',
      14 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      15 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      16 => 'Illuminate\\Queue\\QueueServiceProvider',
      17 => 'Illuminate\\Redis\\RedisServiceProvider',
      18 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      19 => 'Illuminate\\Session\\SessionServiceProvider',
      20 => 'Illuminate\\Translation\\TranslationServiceProvider',
      21 => 'Illuminate\\Validation\\ValidationServiceProvider',
      22 => 'Illuminate\\View\\ViewServiceProvider',
      23 => 'DaveJamesMiller\\Breadcrumbs\\ServiceProvider',
      24 => 'App\\Providers\\EventAddOnServiceProvider',
      25 => 'App\\Providers\\NewsCatergoryServiceProvider',
      26 => 'App\\Providers\\AppServiceProvider',
      27 => 'App\\Providers\\AuthServiceProvider',
      28 => 'App\\Providers\\EventServiceProvider',
      29 => 'App\\Providers\\CustomFileValidation',
      30 => 'App\\Providers\\RouteServiceProvider',
      31 => 'App\\Providers\\ComposerServiceProvider',
      32 => 'Artisaninweb\\SoapWrapper\\ServiceProvider',
      33 => 'Spatie\\Backup\\BackupServiceProvider',
      34 => 'Yajra\\Datatables\\DatatablesServiceProvider',
      35 => 'Webup\\LaravelSendinBlue\\SendinBlueServiceProvider',
      36 => 'eKutivaSolutions\\SymLinker\\Providers\\SymLinkServiceProvider',
      37 => 'MaddHatter\\LaravelFullcalendar\\ServiceProvider',
      38 => 'Intervention\\Image\\ImageServiceProvider',
      39 => 'Barryvdh\\Debugbar\\ServiceProvider',
      40 => 'Maatwebsite\\Excel\\ExcelServiceProvider',
      41 => 'Cviebrock\\EloquentSluggable\\SluggableServiceProvider',
      42 => 'UxWeb\\SweetAlert\\SweetAlertServiceProvider',
      43 => 'Former\\FormerServiceProvider',
      44 => 'Bican\\Roles\\RolesServiceProvider',
      45 => 'Collective\\Html\\HtmlServiceProvider',
      46 => 'Tymon\\JWTAuth\\Providers\\JWTAuthServiceProvider',
      47 => 'Barryvdh\\Cors\\ServiceProvider',
      48 => 'Barryvdh\\Snappy\\ServiceProvider',
      49 => 'Rutorika\\Sortable\\SortableServiceProvider',
      50 => 'YAAP\\Theme\\ThemeServiceProvider',
      51 => 'Rollbar\\Laravel\\RollbarServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Input' => 'Illuminate\\Support\\Facades\\Input',
      'Inspiring' => 'Illuminate\\Foundation\\Inspiring',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Redis' => 'Illuminate\\Support\\Facades\\Redis',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Image' => 'Intervention\\Image\\Facades\\Image',
      'Debugbar' => 'Barryvdh\\Debugbar\\Facade',
      'Excel' => 'Maatwebsite\\Excel\\Facades\\Excel',
      'Alert' => 'UxWeb\\SweetAlert\\SweetAlert',
      'Breadcrumbs' => 'DaveJamesMiller\\Breadcrumbs\\Facade',
      'SoapWrapper' => 'Artisaninweb\\SoapWrapper\\Facades\\SoapWrapper',
      'Former' => 'Former\\Facades\\Former',
      'PDF' => 'Barryvdh\\Snappy\\Facades\\SnappyPdf',
      'Calendar' => 'MaddHatter\\LaravelFullcalendar\\Facades\\Calendar',
      'Form' => 'Collective\\Html\\FormFacade',
      'Html' => 'Collective\\Html\\HtmlFacade',
      'JWTAuth' => 'Tymon\\JWTAuth\\Facades\\JWTAuth',
      'JWTFactory' => 'Tymon\\JWTAuth\\Facades\\JWTFactory',
      'SnappyImage' => 'Barryvdh\\Snappy\\Facades\\SnappyImage',
      'Datatables' => 'Yajra\\Datatables\\Facades\\Datatables',
      'Theme' => 'YAAP\\Theme\\Facades\\Theme',
      'AWS' => 'Aws\\Laravel\\AwsFacade',
      0 => 'PrettyRoutes\\ServiceProvider',
    ),
    'DefaultBuildYourOwn' => 
    array (
      0 => 'compliance-and-legislation-update-2019',
      1 => 'practice-management-update-2019',
      2 => 'monthly-tax-update-2019',
    ),
  ),
  'auth' => 
  array (
    'driver' => 'eloquent',
    'model' => 'App\\Users\\User',
    'table' => 'users',
    'password' => 
    array (
      'email' => 'emails.password',
      'table' => 'password_resets',
      'expire' => 60,
    ),
  ),
  'aws' => 
  array (
    'credentials' => 
    array (
      'key' => NULL,
      'secret' => NULL,
    ),
    'region' => 'us-east-1',
    'version' => 'latest',
  ),
  'billing' => 
  array (
    'vat_rate' => 15,
  ),
  'breadcrumbs' => 
  array (
    'view' => 'breadcrumbs::bootstrap3',
  ),
  'broadcasting' => 
  array (
    'default' => 'pusher',
    'connections' => 
    array (
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => 'f825643e4cc1609eea6e',
        'secret' => 'a723f86022b810826b41',
        'app_id' => '1298046',
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
    ),
  ),
  'bugsnag' => 
  array (
    'api_key' => NULL,
    'notify_release_stages' => NULL,
    'endpoint' => NULL,
    'filters' => 
    array (
      0 => 'password',
    ),
    'proxy' => NULL,
  ),
  'byo_default' => 
  array (
    0 => 'monthly-tax-update-2019',
    1 => 'compliance-and-legislation-update-2019',
    2 => 'practice-management-update-2019',
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => 'D:\\Projects\\saaa\\accounting-academy\\storage\\framework/cache',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
    ),
    'prefix' => 'laravel',
  ),
  'compile' => 
  array (
    'files' => 
    array (
    ),
    'providers' => 
    array (
    ),
  ),
  'cors' => 
  array (
    'supportsCredentials' => false,
    'allowedOrigins' => 
    array (
      0 => '*',
    ),
    'allowedHeaders' => 
    array (
      0 => '*',
    ),
    'allowedMethods' => 
    array (
      0 => '*',
    ),
    'exposedHeaders' => 
    array (
    ),
    'maxAge' => 0,
  ),
  'database' => 
  array (
    'fetch' => 8,
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'database' => 'D:\\Projects\\saaa\\accounting-academy\\storage\\database.sqlite',
        'prefix' => '',
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'saaa_new',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        'strict' => false,
      ),
      'handesk_mysql' => 
      array (
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'forge',
        'username' => 'forge',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        'strict' => false,
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'host' => 'localhost',
        'database' => 'saaa_new',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'schema' => 'public',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'host' => 'sqlsrv:server=(localdb)\\v11.0',
        'database' => 'MSSQLLocalDB',
        'username' => '',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'cluster' => false,
      'default' => 
      array (
        'host' => '127.0.0.1',
        'port' => 6379,
        'database' => 0,
      ),
    ),
  ),
  'datatables' => 
  array (
    'search' => 
    array (
      'smart' => true,
      'case_insensitive' => true,
      'use_wildcards' => false,
    ),
    'fractal' => 
    array (
      'includes' => 'include',
      'serializer' => 'League\\Fractal\\Serializer\\DataArraySerializer',
    ),
    'script_template' => 'datatables::script',
    'index_column' => 'DT_Row_Index',
    'namespace' => 
    array (
      'base' => 'DataTables',
      'model' => '',
    ),
    'pdf_generator' => 'excel',
    'snappy' => 
    array (
      'options' => 
      array (
        'no-outline' => true,
        'margin-left' => '0',
        'margin-right' => '0',
        'margin-top' => '10mm',
        'margin-bottom' => '10mm',
      ),
      'orientation' => 'landscape',
    ),
  ),
  'debugbar' => 
  array (
    'enabled' => NULL,
    'storage' => 
    array (
      'enabled' => true,
      'driver' => 'file',
      'path' => 'D:\\Projects\\saaa\\accounting-academy\\storage/debugbar',
      'connection' => NULL,
    ),
    'include_vendors' => true,
    'capture_ajax' => true,
    'add_ajax_timing' => false,
    'error_handler' => false,
    'clockwork' => false,
    'collectors' => 
    array (
      'phpinfo' => true,
      'messages' => true,
      'time' => true,
      'memory' => true,
      'exceptions' => true,
      'log' => true,
      'db' => true,
      'views' => true,
      'route' => true,
      'laravel' => false,
      'events' => false,
      'default_request' => false,
      'symfony_request' => true,
      'mail' => true,
      'logs' => false,
      'files' => false,
      'config' => false,
      'auth' => false,
      'session' => true,
    ),
    'options' => 
    array (
      'auth' => 
      array (
        'show_name' => false,
      ),
      'db' => 
      array (
        'with_params' => true,
        'timeline' => false,
        'backtrace' => false,
        'explain' => 
        array (
          'enabled' => false,
          'types' => 
          array (
            0 => 'SELECT',
          ),
        ),
        'hints' => true,
      ),
      'mail' => 
      array (
        'full_log' => false,
      ),
      'views' => 
      array (
        'data' => false,
      ),
      'route' => 
      array (
        'label' => true,
      ),
      'logs' => 
      array (
        'file' => NULL,
      ),
    ),
    'inject' => true,
    'route_prefix' => '_debugbar',
    'route_domain' => NULL,
  ),
  'dropbox' => 
  array (
    'default' => 'main',
    'connections' => 
    array (
      'main' => 
      array (
        'token' => 'cqreu8Ha6Z4AAAAAAABlOhmxTWVFO9Xwi8ZlnPzs7q7zGbHFoiEfmI3rwKtzAQen',
        'app' => 'cqreu8Ha6Z4AAAAAAABlOhmxTWVFO9Xwi8ZlnPzs7q7zGbHFoiEfmI3rwKtzAQen',
      ),
      'alternative' => 
      array (
        'token' => 'your-token',
        'app' => 'your-app',
      ),
    ),
  ),
  'email_addresses' => 
  array (
    'notifiable_email_accounts' => 
    array (
      0 => 'ttheunissen@accountingacademy.co.za',
      1 => 'dayaan@accountingacademy.co.za',
      2 => 'linky@accountingacademy.co.za',
      3 => 'michelle@accountingacademy.co.za',
      4 => 'agnes@accountingacademy.co.za',
    ),
  ),
  'entrust' => 
  array (
    'role' => 'App\\Users\\Role',
    'roles_table' => 'roles',
    'permission' => 'App\\Users\\Permission',
    'permissions_table' => 'permissions',
    'permission_role_table' => 'permission_role',
    'role_user_table' => 'role_user',
    'user_foreign_key' => 'user_id',
  ),
  'excel' => 
  array (
    'cache' => 
    array (
      'enable' => true,
      'driver' => 'memory',
      'settings' => 
      array (
        'memoryCacheSize' => '32MB',
        'cacheTime' => 600,
      ),
      'memcache' => 
      array (
        'host' => 'localhost',
        'port' => 11211,
      ),
      'dir' => 'D:\\Projects\\saaa\\accounting-academy\\storage\\cache',
    ),
    'properties' => 
    array (
      'creator' => 'Maatwebsite',
      'lastModifiedBy' => 'Maatwebsite',
      'title' => 'Spreadsheet',
      'description' => 'Default spreadsheet export',
      'subject' => 'Spreadsheet export',
      'keywords' => 'maatwebsite, excel, export',
      'category' => 'Excel',
      'manager' => 'Maatwebsite',
      'company' => 'Maatwebsite',
    ),
    'sheets' => 
    array (
      'pageSetup' => 
      array (
        'orientation' => 'portrait',
        'paperSize' => '9',
        'scale' => '100',
        'fitToPage' => false,
        'fitToHeight' => true,
        'fitToWidth' => true,
        'columnsToRepeatAtLeft' => 
        array (
          0 => '',
          1 => '',
        ),
        'rowsToRepeatAtTop' => 
        array (
          0 => 0,
          1 => 0,
        ),
        'horizontalCentered' => false,
        'verticalCentered' => false,
        'printArea' => NULL,
        'firstPageNumber' => NULL,
      ),
    ),
    'creator' => 'Maatwebsite',
    'csv' => 
    array (
      'delimiter' => ',',
      'enclosure' => '"',
      'line_ending' => '
',
    ),
    'export' => 
    array (
      'autosize' => true,
      'autosize-method' => 'approx',
      'generate_heading_by_indices' => true,
      'merged_cell_alignment' => 'left',
      'calculate' => false,
      'includeCharts' => false,
      'sheets' => 
      array (
        'page_margin' => false,
        'nullValue' => NULL,
        'startCell' => 'A1',
        'strictNullComparison' => false,
      ),
      'store' => 
      array (
        'path' => 'D:\\Projects\\saaa\\accounting-academy\\storage\\exports',
        'returnInfo' => false,
      ),
      'pdf' => 
      array (
        'driver' => 'DomPDF',
        'drivers' => 
        array (
          'DomPDF' => 
          array (
            'path' => 'D:\\Projects\\saaa\\accounting-academy\\vendor/dompdf/dompdf/',
          ),
          'tcPDF' => 
          array (
            'path' => 'D:\\Projects\\saaa\\accounting-academy\\vendor/tecnick.com/tcpdf/',
          ),
          'mPDF' => 
          array (
            'path' => 'D:\\Projects\\saaa\\accounting-academy\\vendor/mpdf/mpdf/',
          ),
        ),
      ),
    ),
    'filters' => 
    array (
      'registered' => 
      array (
        'chunk' => 'Maatwebsite\\Excel\\Filters\\ChunkReadFilter',
      ),
      'enabled' => 
      array (
      ),
    ),
    'import' => 
    array (
      'heading' => 'slugged',
      'startRow' => 1,
      'separator' => '_',
      'includeCharts' => false,
      'to_ascii' => true,
      'encoding' => 
      array (
        'input' => 'UTF-8',
        'output' => 'UTF-8',
      ),
      'calculate' => true,
      'ignoreEmpty' => false,
      'force_sheets_collection' => false,
      'dates' => 
      array (
        'enabled' => true,
        'format' => false,
        'columns' => 
        array (
        ),
      ),
      'sheets' => 
      array (
        'test' => 
        array (
          'firstname' => 'A2',
        ),
      ),
    ),
    'views' => 
    array (
      'styles' => 
      array (
        'th' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'strong' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'b' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'i' => 
        array (
          'font' => 
          array (
            'italic' => true,
            'size' => 12,
          ),
        ),
        'h1' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 24,
          ),
        ),
        'h2' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 18,
          ),
        ),
        'h3' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 13.5,
          ),
        ),
        'h4' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'h5' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 10,
          ),
        ),
        'h6' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 7.5,
          ),
        ),
        'a' => 
        array (
          'font' => 
          array (
            'underline' => true,
            'color' => 
            array (
              'argb' => 'FF0000FF',
            ),
          ),
        ),
        'hr' => 
        array (
          'borders' => 
          array (
            'bottom' => 
            array (
              'style' => 'thin',
              'color' => 
              array (
                0 => 'FF000000',
              ),
            ),
          ),
        ),
      ),
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'cloud' => 's3',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => 'D:\\Projects\\saaa\\accounting-academy\\storage\\app',
      ),
      'ftp' => 
      array (
        'driver' => 'ftp',
        'host' => 'ftp.example.com',
        'username' => 'your-username',
        'password' => 'your-password',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => 'your-key',
        'secret' => 'your-secret',
        'region' => 'your-region',
        'bucket' => 'your-bucket',
      ),
      'rackspace' => 
      array (
        'driver' => 'rackspace',
        'username' => 'your-username',
        'key' => 'your-key',
        'container' => 'your-container',
        'endpoint' => 'https://identity.api.rackspacecloud.com/v2.0/',
        'region' => 'IAD',
        'url_type' => 'publicURL',
      ),
      'dropbox' => 
      array (
        'driver' => 'dropbox',
        'key' => 'mnkwwaqzhbzlza9',
        'secret' => 'h895knz2mmpudid',
      ),
    ),
  ),
  'former' => 
  array (
    'automatic_label' => true,
    'default_form_type' => 'vertical',
    'fetch_errors' => true,
    'live_validation' => true,
    'error_messages' => true,
    'push_checkboxes' => false,
    'unchecked_value' => 0,
    'required_class' => 'required',
    'required_text' => '<sup>*</sup>',
    'translate_from' => 'validation.attributes',
    'capitalize_translations' => true,
    'translatable' => 
    array (
      0 => 'help',
      1 => 'inlineHelp',
      2 => 'blockHelp',
      3 => 'placeholder',
      4 => 'data_placeholder',
      5 => 'label',
    ),
    'framework' => 'TwitterBootstrap3',
    'TwitterBootstrap3' => 
    array (
      'viewports' => 
      array (
        'large' => 'lg',
        'medium' => 'md',
        'small' => 'sm',
        'mini' => 'xs',
      ),
      'labelWidths' => 
      array (
        'large' => 2,
        'small' => 4,
      ),
      'icon' => 
      array (
        'tag' => 'span',
        'set' => 'glyphicon',
        'prefix' => 'glyphicon',
      ),
    ),
    'Nude' => 
    array (
      'icon' => 
      array (
        'tag' => 'i',
        'set' => NULL,
        'prefix' => 'icon',
      ),
    ),
    'TwitterBootstrap' => 
    array (
      'icon' => 
      array (
        'tag' => 'i',
        'set' => NULL,
        'prefix' => 'icon',
      ),
    ),
    'ZurbFoundation5' => 
    array (
      'viewports' => 
      array (
        'large' => 'large',
        'medium' => NULL,
        'small' => 'small',
        'mini' => NULL,
      ),
      'labelWidths' => 
      array (
        'small' => 3,
      ),
      'wrappedLabelClasses' => 
      array (
        0 => 'right',
        1 => 'inline',
      ),
      'icon' => 
      array (
        'tag' => 'i',
        'set' => NULL,
        'prefix' => 'fi',
      ),
      'error_classes' => 
      array (
        'class' => 'error',
      ),
    ),
    'ZurbFoundation4' => 
    array (
      'viewports' => 
      array (
        'large' => 'large',
        'medium' => NULL,
        'small' => 'small',
        'mini' => NULL,
      ),
      'labelWidths' => 
      array (
        'small' => 3,
      ),
      'wrappedLabelClasses' => 
      array (
        0 => 'right',
        1 => 'inline',
      ),
      'icon' => 
      array (
        'tag' => 'i',
        'set' => 'general',
        'prefix' => 'foundicon',
      ),
      'error_classes' => 
      array (
        'class' => 'alert-box radius warning',
      ),
    ),
    'ZurbFoundation' => 
    array (
      'viewports' => 
      array (
        'large' => '',
        'medium' => NULL,
        'small' => 'mobile-',
        'mini' => NULL,
      ),
      'labelWidths' => 
      array (
        'large' => 2,
        'small' => 4,
      ),
      'wrappedLabelClasses' => 
      array (
        0 => 'right',
        1 => 'inline',
      ),
      'icon' => 
      array (
        'tag' => 'i',
        'set' => NULL,
        'prefix' => 'fi',
      ),
      'error_classes' => 
      array (
        'class' => 'alert-box alert error',
      ),
    ),
  ),
  'image' => 
  array (
    'driver' => 'gd',
  ),
  'jwt' => 
  array (
    'secret' => 'fJKXMdbrxYklaIzw8mkn1ZFyxgzBrASm',
    'ttl' => 535680,
    'refresh_ttl' => 20160,
    'algo' => 'HS256',
    'user' => 'App\\Users\\User',
    'identifier' => 'id',
    'required_claims' => 
    array (
      0 => 'iss',
      1 => 'iat',
      2 => 'exp',
      3 => 'nbf',
      4 => 'sub',
      5 => 'jti',
    ),
    'blacklist_enabled' => true,
    'providers' => 
    array (
      'user' => 'Tymon\\JWTAuth\\Providers\\User\\EloquentUserAdapter',
      'jwt' => 'Tymon\\JWTAuth\\Providers\\JWT\\NamshiAdapter',
      'auth' => 'Tymon\\JWTAuth\\Providers\\Auth\\IlluminateAuthAdapter',
      'storage' => 'Tymon\\JWTAuth\\Providers\\Storage\\IlluminateCacheAdapter',
    ),
  ),
  'laravel-backup' => 
  array (
    'source' => 
    array (
      'files' => 
      array (
        'include' => 
        array (
          0 => 'D:\\Projects\\saaa\\accounting-academy',
        ),
        'exclude' => 
        array (
          0 => 'D:\\Projects\\saaa\\accounting-academy\\storage',
          1 => 'D:\\Projects\\saaa\\accounting-academy\\vendor',
        ),
      ),
      'backup-db' => true,
    ),
    'destination' => 
    array (
      'filesystem' => 
      array (
        0 => 'local',
      ),
      'path' => 'backups',
      'prefix' => '',
      'suffix' => '',
    ),
    'clean' => 
    array (
      'maxAgeInDays' => 90,
    ),
    'mysql' => 
    array (
      'dump_command_path' => '',
      'useExtendedInsert' => false,
      'timeoutInSeconds' => 600,
    ),
    'pgsql' => 
    array (
      'dump_command_path' => '',
      'use_copy' => true,
      'timeoutInSeconds' => 60,
    ),
  ),
  'mail' => 
  array (
    'driver' => 'smtp',
    'host' => 'smtp.gmail.com',
    'port' => '587',
    'from' => 
    array (
      'address' => 'admin@gmail.com',
      'name' => 'The Tax Faculty',
    ),
    'encryption' => 'tls',
    'username' => 'sparsh1test@gmail.com',
    'password' => 'plbboiztoudgqsff',
    'sendmail' => '/usr/sbin/sendmail -bs',
    'pretend' => false,
  ),
  'pretty-routes' => 
  array (
    'url' => 'routes',
    'middlewares' => 
    array (
    ),
    'debug_only' => true,
    'hide_methods' => 
    array (
      0 => 'HEAD',
    ),
    'hide_matching' => 
    array (
      0 => '#^_debugbar#',
      1 => '#^routes$#',
    ),
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'sys_jobs',
        'queue' => 'default',
        'expire' => 600,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'ttr' => 60,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => 'your-public-key',
        'secret' => 'your-secret-key',
        'queue' => 'your-queue-url',
        'region' => 'us-east-1',
      ),
      'iron' => 
      array (
        'driver' => 'iron',
        'host' => 'mq-aws-us-east-1.iron.io',
        'token' => 'your-token',
        'project' => 'your-project-id',
        'queue' => 'your-queue-name',
        'encrypt' => true,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'expire' => 60,
      ),
    ),
    'failed' => 
    array (
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'roles' => 
  array (
    'connection' => NULL,
    'separator' => '.',
    'models' => 
    array (
      'role' => 'Bican\\Roles\\Models\\Role',
      'permission' => 'Bican\\Roles\\Models\\Permission',
    ),
    'pretend' => 
    array (
      'enabled' => false,
      'options' => 
      array (
        'is' => true,
        'can' => true,
        'allowed' => true,
      ),
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
    ),
    'mandrill' => 
    array (
      'secret' => NULL,
    ),
    'ses' => 
    array (
      'key' => NULL,
      'secret' => NULL,
      'region' => 'us-east-1',
    ),
    'stripe' => 
    array (
      'model' => 'App\\User',
      'key' => NULL,
      'secret' => NULL,
    ),
    'peach' => 
    array (
      'user_id' => '8ac7a4c866e29f5c0166ed879d610e5a',
      'password' => 'gW8HeQ3eNK',
      'entityId' => '8ac7a4c8758e148801758e39089e0258',
      'recurringId' => '8ac7a4c8758e148801758e39089e0258',
      'endpoint' => 'httpstest.oppwa.com',
      'token' => 'OGFjN2E0Yzg3NThlMTQ4ODAxNzU4ZTM4ZWY1YjAyNTF8dHQyeEY3UnR5Rw==',
    ),
    'pusher' => 
    array (
      'public' => 'f825643e4cc1609eea6e',
      'secret' => 'a723f86022b810826b41',
      'app_id' => '1298046',
    ),
    'sendinblue' => 
    array (
      'url' => 'https://api.sendinblue.com/v2.0',
      'key' => NULL,
    ),
    'handesk' => 
    array (
      'web_url' => 'httpweb8.anasource.comteam4handeskhandeskpublic',
      'url' => 'http192.168.10.8team4handeskhandeskpublicapi',
      'token' => '12345',
      'default_password' => '12345',
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => 120,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => 'D:\\Projects\\saaa\\accounting-academy\\storage\\framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'laravel_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => false,
  ),
  'signup' => 
  array (
    'interest' => 
    array (
      'Corporate Tax' => 'Corporate Tax',
      'Customs and Excise' => 'Customs and Excise',
      'Individuals Tax' => 'Individuals Tax',
      'International Tax' => 'International Tax',
      'Payroll' => 'Payroll',
      'Tax Administration' => 'Tax Administration',
      'Transfer Pricing' => 'Transfer Pricing',
      'VAT' => 'VAT',
    ),
    'employment' => 
    array (
      'Registered tax practitioner' => 'Registered tax practitioner',
      'Employed (private sector)' => 'Employed (private sector)',
      'Employed (public sector)' => 'Employed (public sector)',
      'Employed (academia)' => 'Employed (academia)',
    ),
    'industry' => 
    array (
      'Agriculture, Forestry, Fishing and Hunting' => 'Agriculture, Forestry, Fishing and Hunting',
      'Automotive' => 'Automotive',
      'Aviation' => 'Aviation',
      'Banking' => 'Banking',
      'Business & Professional Services' => 'Business & Professional Services',
      'Chemical' => 'Chemical',
      'Construction & Building' => 'Construction & Building',
      'Education and Training' => 'Education and Training',
      'Energy & utilities' => 'Energy & utilities',
      'Financial and Accounting Services' => 'Financial and Accounting Services',
      'Government Organization' => 'Government Organization',
      'Health and Welfare' => 'Health and Welfare',
      'Information and Communication Technologies' => 'Information and Communication Technologies',
      'Insurance' => 'Insurance',
      'Legal' => 'Legal',
      'Leisure and Hospitality' => 'Leisure and Hospitality',
      'Manufacturing and Engineering' => 'Manufacturing and Engineering',
      'Media, Advertising & Communications' => 'Media, Advertising & Communications',
      'Mining, Quarrying and Oil & Gas' => 'Mining, Quarrying and Oil & Gas',
      'NGOs, NPOs & Body Corporates' => 'NGOs, NPOs & Body Corporates',
      'Real Estate and Rental and Leasing' => 'Real Estate and Rental and Leasing',
      'Restaurant, Food & Beverages' => 'Restaurant, Food & Beverages',
      'Retail' => 'Retail',
      'Security' => 'Security',
      'Tourism & Events' => 'Tourism & Events',
      'Transportation, Logistics and Warehousing' => 'Transportation, Logistics and Warehousing',
      'Water, Waste & Sanitation' => 'Water, Waste & Sanitation',
      'Wholesale' => 'Wholesale',
      'Other' => 'Other',
    ),
  ),
  'sluggable' => 
  array (
    'build_from' => NULL,
    'save_to' => 'slug',
    'max_length' => NULL,
    'method' => NULL,
    'separator' => '-',
    'unique' => true,
    'include_trashed' => false,
    'on_update' => false,
    'reserved' => NULL,
  ),
  'snappy' => 
  array (
    'pdf' => 
    array (
      'enabled' => true,
      'binary' => 'D:\\Projects\\saaa\\accounting-academy\\vendor\\wemersonjanuario\\wkhtmltopdf-windows\\bin\\64bit\\wkhtmltopdf',
      'timeout' => false,
      'disable-smart-shrinking' => true,
      'options' => 
      array (
        'dpi' => 300,
        'page-size' => 'A4',
        'margin-top' => '5mm',
        'margin-bottom' => '5mm',
        'margin-left' => '5mm',
        'margin-right' => '5mm',
        'zoom' => '1.25',
      ),
    ),
    'image' => 
    array (
      'enabled' => true,
      'binary' => 'D:\\Projects\\saaa\\accounting-academy\\vendor\\wemersonjanuario\\wkhtmltopdf-windows\\bin\\64bit\\wkhtmltopdf',
      'timeout' => false,
      'disable-smart-shrinking' => true,
      'options' => 
      array (
        'dpi' => 300,
        'page-size' => 'A4',
        'margin-top' => '5mm',
        'margin-bottom' => '5mm',
        'margin-left' => '5mm',
        'margin-right' => '5mm',
        'zoom' => '1.25',
      ),
    ),
  ),
  'sortable' => 
  array (
    'entities' => 
    array (
      'presenters' => '\\App\\Presenters\\Presenter',
    ),
  ),
  'sponsor_questions' => 
  array (
    1 => 'name',
    2 => 'email',
    3 => 'contact_number',
    4 => 'product',
    5 => 'age',
    6 => 'accountant_type',
    7 => 'income',
    8 => 'gender',
    9 => 'race',
    10 => 'level_of_management',
    11 => 'registered_professional_accountancy_body',
    12 => 'professional_body_name',
    13 => 'other_professional_body_name',
    14 => 'do_you_adhere_to_a_code_of_conduct',
    15 => 'are_your_cpd_hours_up_to_date',
    16 => 'do_you_use_engagement_letters',
    17 => 'latest_technical_knowledge_or_library',
    18 => 'do_you_have_the_required_infrastructure',
    19 => 'do_you_or_your_firm_perform_reviews_of_all_work',
    20 => 'do_you_apply_relevant_auditing_and_assurance_standards',
    21 => 'do_you_use_the_latest_technology_and_software',
    22 => 'quote',
    23 => 'email',
    24 => 'company_trading_name',
    25 => 'physical_business_address',
    26 => 'vat_number',
    27 => 'first_name',
    28 => 'surname',
    29 => 'contact_number',
    30 => 'id_or_passport',
    31 => 'type_of_subscription',
    32 => 'professional_body',
    33 => 'number_of_licenses',
    34 => 'applies_to_you',
    35 => 'type_of_business',
    36 => 'adviser_to_contact_me',
    37 => 'being_a_referral_agent',
    38 => 'date_of_birth',
  ),
  'store' => 
  array (
    'listings_per_page' => 8,
  ),
  'sweet-alert' => 
  array (
    'autoclose' => 1800,
  ),
  'theme' => 
  array (
    'path' => 'D:\\Projects\\saaa\\accounting-academy\\resources/themes',
    'assets_path' => 'assets/themes',
    'containerDir' => 
    array (
      'layout' => 'layouts',
      'partial' => 'partials',
      'view' => 'views',
    ),
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => 'D:\\Projects\\saaa\\accounting-academy\\resources\\views',
    ),
    'compiled' => 'D:\\Projects\\saaa\\accounting-academy\\storage\\framework\\views',
  ),
  0 => 'config/sweet-alert.php',
);
