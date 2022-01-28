<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use SplFileObject;
use Throwable;

class AutoInsert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:autoinsert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'auto insert category and courses.';

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
     * @return int
     */
    public function handle()
    {
        $path = resource_path('markdown');
        $dirs = scandir($path);

        $exclusion = array('.', '..');

        try{
            DB::beginTransaction();

            foreach($dirs as $dir){
                if(in_array($dir, $exclusion)){
                    continue;
                }
                $categoryDir = resource_path('markdown' . '/' . $dir);
                if(!is_dir($categoryDir)){
                    continue;
                }

                DB::table('categories')->updateOrInsert(['name' => $dir]);
                $category_id = DB::table('categories')
                                    ->select('id')
                                    ->where('name','=',$dir)
                                    ->first();

                $files = File::files($categoryDir);
                foreach($files as $file){
                    if($file->getExtension() !== "md"){
                        continue;
                    }           

                    $splFileObject = new SplFileObject($file->getPathname());
                    $line = str_replace(PHP_EOL,'',$splFileObject->fgets());

                    DB::table('courses')->updateOrInsert(
                        [
                            'category_id' => $category_id->id,
                            'file' => $file->getFilename(),
                            'title' => $line,
                        ]
                    );
                }
            }
            DB::commit();
        } catch(Throwable $e) {
            DB::rollBack();
            $this->error("Error Auto Insert.");
            return Command::FAILURE;
        }

        $this->info("Success Auto Insert.");
        return Command::SUCCESS;
    }
}
