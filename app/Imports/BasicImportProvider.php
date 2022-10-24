<?php

namespace App\Imports;

use App\ImportProvider;

trait BasicImportProvider
{
    protected $provider = null;

    public function getActions()
    {
        return $this->actions;
    }

    public function getViewData()
    {
        return [];
    }

    public function getActionValidationRules($action)
    {
        return $this->getActions()[$action]['validation_rules'];
    }

    public function renderActionView($action, $import = null)
    {
        return view($this->getActions()[$action]['view'], ['provider' => $this->provider, 'import' => $import, 'action' => $action]);
    }

    public function performAction($action, $request, $import = null)
    {
        return call_user_func_array([$this, $action], ['request' => $request, 'import' => $import]);
    }

    public function setImportProvider(ImportProvider $provider)
    {
        $this->provider = $provider;
        return $this;
    }
}