<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function courseEnrollment(Request $request)
    {
        // validate
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'total_videos' => 'required',
        ]);

        $course = new Course();
        $course->user_id = auth()->user()->id;
        $course->title = $request->title;
        $course->description = $request->description;
        $course->total_videos = $request->total_videos;

        $course->save();

        return response()->json([
            'status' => 1,
            'message' => 'Course created successfully',
        ]);
    }

    public function totalCourse()
    {
        $id = auth()->user()->id;
        $courses = User::find($id)->courses;

        return response()->json([
            'status' => 0,
            'message' => 'Total Course',
            'courses' => $courses
        ]);
    }

    public function deleteCourse($id)
    {
        // user id
        $user = auth()->user()->id;
        // course id
        if (Course::where([
            'id' => $id,
            'user_id' => $user
        ])->exists()) {
            $course = Course::find($id);
            $course->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Course deleted successfully'
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Course not found'
            ]);
        }
        // course table
    }
}
