<?php

namespace App\Domain\Course\Service;

use App\Domain\Course\Repository\CourseRepository;
use Exception;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\File;

class CourseServiceImpl implements CourseService{

    private CourseRepository $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getCourseByCategroy($categroy){
        $courses =  $this->courseRepository->getCourseByCategroy($categroy);
    
        return array(
            'category' => $categroy,
            'courses' => $courses,
        );
    
    }

    public function getCourseDetail($id){

        $info = $this->courseRepository->getCourseDetail($id);

        $path = resource_path('markdown' . '/' . $info->name);

        try{
            $file = File::get($path . '/' . $info->file);
        } catch(Exception $e) {
            dd("エラーです");
        };

        $html = Markdown::parse($file)->toHtml();

        return array(
            'info' => $info,
            'html' => $html,
        );

    }

}