<?php

namespace App\Imports;

use App\Http\Requests\ImportRequest;
use App\ImportProvider;

interface ImportProviderInterface
{
    public function import(ImportRequest $request);

    public function getActions();

    public function renderActionView($action, $import = null);

    public function performAction($action, $request, $import = null);

    public function setImportProvider(ImportProvider $provider);

    public function getViewData();


}