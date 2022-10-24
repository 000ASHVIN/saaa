<?php

namespace App\Console\Commands;

use App\Freshdesk;
use App\Jobs\CreateSolutionArticles;
use App\SolutionFolder;
use App\SolutionSubFolder;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class PullSolutions extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:solutions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will sync the freshdesk solutions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $solutions = Freshdesk::list_solution_categories();

        foreach ($solutions as $folder) {

            $bool = SolutionFolder::where('folder_id', $folder->id)->exists();

            if ($bool === true) {
                $folder = SolutionFolder::where('folder_id', $folder->id)->first();

                $folder->update([
                    'name' => $folder->name,
                    'description' => $folder->description,
                ]);

            } else {
                SolutionFolder::create([
                    'folder_id' => $folder->id,
                    'name' => $folder->name,
                    'description' => $folder->description,
                ]);
            }
        }

        $folders = SolutionFolder::all();

        foreach ($folders as $fol) {
            $categorySolutions = Freshdesk::list_category_solutions($fol->folder_id);

            foreach ($categorySolutions as $sol) {
                $bool = SolutionSubFolder::where('sub_folder_id', $sol->id)->exists();

                if ($bool === true) {
                    $folder = SolutionSubFolder::where('sub_folder_id', $sol->id)->first();

                    $folder->update([
                        'name' => $sol->name,
                        'sub_folder_id' => $sol->id,
                        'visibility' => $sol->visibility,
                        'solution_folder_id' => $fol->id,
                        'description' => ($sol->description ?: "")
                    ]);
                } else {
                    $folder = SolutionSubFolder::create([
                        'name' => $sol->name,
                        'sub_folder_id' => $sol->id,
                        'visibility' => $sol->visibility,
                        'solution_folder_id' => $fol->id,
                        'description' => ($sol->description ?: "")
                    ]);
                }

                $this->dispatch(new CreateSolutionArticles($folder->sub_folder_id));
            }
        }
    }
}
