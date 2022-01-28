<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SampleController extends Controller
{
    public function sample()
    {
        return Inertia::render('Mypage/Sample',[]);
    }

    public function testpost(Request $request)
    {

        $request->validate([
            'name' => 'required',
        ]);

        dd($request);
    }


    public function dashbord()
    {
        // ユーザー情報の取得(モック)
        $info = array(
            'user_name' => 'misaka',
            'stage' => '一般アカウント',
            'done' => false,
            'scores' => array( 'level1' => 80, 'level2' => 100, 'level3' => 90, 'level4' => 100, 'level5' => 0 ),
        );
        // 取得したユーザー情報をvueにpropsに渡す
        return Inertia::render('Mypage/Dashbord',[
            'info' => $info,
        ]);
    }

}
