<?php

namespace App\Domain\Course\Repository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseRepositoryImpl implements CourseRepository{

    function getCourseByCategroy($category){

        $result = DB::table('courses')
                    ->select('courses.id','courses.title', 'courses.file')
                    ->selectRaw("case when progress.done IS NULL then 0 else progress.done END AS done")
                    ->leftJoin('progress', function($join){
                        $join->on('courses.id' , '=', 'progress.course_id');
                        $join->where('progress.user_id', '=', Auth::id());
                    })
                    ->where('courses.category_id', '=', function($query) use($category){
                        $query->selectRaw('id')->from('categories')->where('name', '=', $category);
                    })
                    ->get();
        return $result;
    }

    public function getCourseDetail($id){
        return DB::table('courses')
            ->select('courses.id','categories.name','courses.title', 'courses.file')
            ->selectRaw("case when progress.done IS NULL then 0 else progress.done END AS done")
            ->leftJoin('progress', function($join){
                $join->on('courses.id', '=' ,'progress.course_id');
                $join->where('progress.user_id' , '=', Auth::id());
            })
            ->leftJoin('categories', 'courses.category_id', '=', 'categories.id')
            ->where('courses.id', '=', $id)
            ->get()
            ->first();
    }

}