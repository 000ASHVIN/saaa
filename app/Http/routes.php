<?php

\YAAP\Theme\Facades\Theme::init(config('app.theme'));

require_once "routes/auth.php";

require_once "routes/api.php";

require_once "routes/admin_routes.php";

Route::group(['middleware' => 'checkForCellVerification'], function () {
    
require_once "routes/pi_insurance.php";

require_once "routes/profession_and_competition.php";

require_once "routes/invoices.php";

require_once "routes/dashboard_routes.php";

require_once "routes/events.php";

require_once "routes/newsletter.php";

require_once "routes/mygate.php";

require_once "routes/static.php";

require_once "routes/pages.php";

require_once "routes/store.php";

require_once "routes/webinars_on_demand.php";

require_once "routes/tickets.php";

require_once "routes/resource_centre.php";

require_once "routes/courses.php";

});