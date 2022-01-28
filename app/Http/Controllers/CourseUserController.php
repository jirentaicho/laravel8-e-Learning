<?php

namespace App\Http\Controllers;

use App\Domain\User\Service\UserService;
use App\Events\CourseFinished;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CourseUserController extends Controller
{

    private UserService $userSerivce;

    public function __construct(UserService $userSerivce)
    {
        $this->userSerivce = $userSerivce;
    }

    public function dashbord()
    {
        $info = $this->userSerivce->getUserProgressData();

        // dd(preg_grep("/[^100]/",$info));

        $done = empty(preg_grep("/^\d{1,2}$/",$info)) ? true : false ;
    
        return Inertia::render('Mypage/Dashbord',[
            'info' => $info,
            'done' => $done,
            'user_name' => Auth::user()->name,
        ]);
    }

    public function allDone()
    {
        CourseFinished::dispatch(Auth::user()->name);
        return back();
    }


}
