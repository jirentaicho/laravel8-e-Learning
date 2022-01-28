<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserRepository;

class UserServiceImpl implements UserService{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;        
    }

    public function getUserProgressData(){

        $datas = $this->userRepository->getUserProgressData();

        $infos = $datas->groupBy('name')->toArray();

        $result = array();

        foreach($infos as $key =>$info){

            $count = array_reduce($info, function($arr, $item) {
                $arr += $item->done;
                return $arr;
            });

            $result += array($key => intval( $count / count($info) * 100));
        }

        return $result;
    }

}