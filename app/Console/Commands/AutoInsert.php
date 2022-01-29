<?php

namespace App\Console\Commands;

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
        $dirs = array_diff(scandir($path), array('.','..'));
        try{
            DB::beginTransaction();
            // カテゴリの登録
            $this->registCategory($dirs);
            // 講義一覧の登録
            $this->registCourses($dirs);

            DB::commit();
        } catch(Throwable $e) {
            DB::rollBack();
            $this->error("Error Auto Insert." . $e);
            return Command::FAILURE;
        }
        $this->info("Success Auto Insert.");
        return Command::SUCCESS;
    }


    private function registCategory(array $dirs)
    {
        foreach($dirs as $dir){
            $categoryDir = resource_path('markdown' . '/' . $dir);
            if(!is_dir($categoryDir)){
                continue;
            }
            DB::table('categories')->updateOrInsert(['name' => $dir]);
        }
    }

    private function registCourses(array $dirs)
    {
        // 引数が空なら返す
        if(empty($dirs)){
            return;
        }
        // 1つ取り出す
        $current = array_shift($dirs);
        // フォルダのパスを取得する
        $categoryDir = resource_path('markdown' . '/' . $current);
        // パスからファイル一覧を取得する
        $files = File::files($categoryDir);
        foreach($files as $file)
        {
            // mdファイル以外は処理しない
            if($file->getExtension() !== "md"){
                continue;
            }
            // ファイルの先頭を取得する
            $splFileObject = new SplFileObject($file->getPathname());
            $line = str_replace(PHP_EOL,'',$splFileObject->fgets());

            //カテゴリーIDを取得する
            $id = $this->getCategroyIdByName($current);

            DB::table('courses')->updateOrInsert(
                [
                    'category_id' => $id,
                    'file' => $file->getFilename(),
                    'title' => $line,
                ]
            );
        }
        // 先頭を取り出した配列で再度、この処理を呼出す
        $this->registCourses($dirs);
    }

    private function getCategroyIdByName(string $name) : int
    {
        $category_id = DB::table('categories')
                        ->select('id')
                        ->where('name','=',$name)
                        ->first();
        return $category_id->id;
    }

}
