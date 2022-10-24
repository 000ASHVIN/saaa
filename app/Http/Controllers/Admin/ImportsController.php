<?php

namespace App\Http\Controllers\Admin;

use App\Import;
use App\ImportProvider;
use App\Mailer;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ImportsController extends Controller
{
    public function getProvider($providerId)
    {
        $importProvider = ImportProvider::findOrFail($providerId);
        $viewData = array_merge($importProvider->instance->getViewData(), compact('importProvider'));
        return view($importProvider->view, $viewData);
    }

    public function postProvider(Requests\ImportRequest $request, $providerId)
    {

        $importProvider = ImportProvider::findOrFail($providerId);
        $this->validate($request, $importProvider->validation_rules);

        $import = $importProvider->instance->import($request);

        return redirect()->route('admin.import.provider.import', [$providerId, $import->id]);
    }

    public function getImports($providerId)
    {
        $provider = ImportProvider::with(['imports'])->findOrFail($providerId);
        return view('admin.imports.index', compact('provider'));
    }

    public function getImport($providerId, $importId)
    {
        $provider = ImportProvider::findOrFail($providerId);
        $import = Import::findOrfail($importId);
        $users = $import->batches()->with(['importable'])->get();
        $actions = $provider->instance->getActions();
        return view('admin.imports.show', compact('provider', 'import', 'actions', 'users'));
    }

    public function getPerformProviderAction($providerId, $importId, $action)
    {
        $provider = ImportProvider::findOrFail($providerId);
        $import = Import::findOrfail($importId);
        return $provider->instance->renderActionView($action, $import);
    }

    public function postPerformProviderAction(Request $request, $providerId, $importId, $action)
    {
        $provider = ImportProvider::findOrFail($providerId);
        $import = Import::findOrfail($importId);
        return $provider->instance->performAction($action, $request, $import);
    }
}