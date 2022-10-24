<?php

use App\ImportProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ImportProvidersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        ImportProvider::create([
            'title' => 'Members',
            'model' => \App\Imports\MembersImportProvider::class,
            'view' => 'admin.imports.members',
            'menu_text' => 'Members',
            'validation_rules' => [
                'plan_id' => ['required']
            ]
        ]);
        Model::reguard();
    }
}
