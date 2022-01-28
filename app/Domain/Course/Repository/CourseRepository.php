<?php

namespace App\Domain\Course\Repository;

interface CourseRepository{

    function getCourseByCategroy($category);

    public function getCourseDetail($id);

}