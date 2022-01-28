<?php

namespace App\Http\Controllers;

use App\Domain\Course\Service\CourseService;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CourseController extends Controller
{

    private CourseService $couseService;

    public function __construct(CourseService $couseService)
    {
        $this->couseService = $couseService;
    }

    public function index($category)
    {

        $info = $this->couseService->getCourseByCategroy($category);

        // 取得した情報をvueのpropsに渡す
        return Inertia::render('Mypage/CourseIndex',[
            'info' => $info,
        ]);

    }
    
    public function detail($id)
    {
        $detail = $this->couseService->getCourseDetail($id);

        return Inertia::render('Mypage/CourseDetail',[
            'info' => $detail['info'],
            'detail' => $detail['html'],
        ]);

    }

    public function done(Request $request)
    {

        $progress = Progress::updateOrCreate(
            ['user_id' => Auth::id(), 'course_id' => $request->course_id],
            ['done' => true]
        );

        return back();
    }


}

