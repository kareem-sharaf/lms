<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Stage;
use App\Models\Year;
use App\Models\TeacherSubjectYear;
use App\Models\SubjectYear;
use App\Models\User;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{

//********************************************************************************************** */
    public function show_all_teachers()
    {
        $teacher = User::where('role_id','3')->get();
        $teacher_id = $teacher->id;
        
        $message = "this is the all teachers.";
        return response()->json([
            'message' => $message,
            'data' => $teacher,
        ]);
    }
    //********************************************************************************************** */
    public function teachers_in_category($category_id)
    {
        $teachers = User::where('role_id', '3')
                        ->whereHas('subjects', function ($query) use ($category_id) {
                            $query->where('category_id', $category_id);
                        })
                        ->get();

        $message = "These are the teachers in category " . $category_id;

        return response()->json([
            'message' => $message,
            'data' => $teachers,
        ]);
    }
    //********************************************************************************************** */
    public function show_one_teacher(Request $request)
{
    $user_id = $request->query('user_id');

    $user = User::where('id', $user_id)->first();
    $courses = [];

    if($user && $user->role_id == 3){
        $courses = Subject::whereHas('years_users', function($q) use ($user_id) {
            $q->where('user_id', $user_id);
        })->get();
        $message = "this is the user.";

        return response()->json([
            'message' => $message,
            'data' => $user,
            'courses' => $courses,
        ]);
    } else {
        $message = "Invalid user role or user not found.";

        return response()->json([
            'message' => $message
        ], 404);
    }
}
    //********************************************************************************************** */
    public function show_one_student(Request $request)
    {
        $user_id = $request->query('user_id');

        $user = User::where('id', $user_id)->first();
        $courses=[];
        if($user && $user->role_id == 4){
            $message = "this is the user.";

            return response()->json([
                'message' => $message,
                'data' => $user,
                'courses' => $courses,
            ]);
        } else {
            $message = "Invalid user role or user not found.";

            return response()->json([
                'message' => $message
            ], 404);
        }
    }
    //********************************************************************************************** */
    public function show_teachers_in_subject(Request $request)
    {
        $subject_id = $request->query('subject_id');

        $teachers = User::whereHas('subjects', function($q) use ($subject_id) {
            $q->where('subject_id', $subject_id);
        })->get();
        $message = "this is the teachers.";
        return response()->json([
            'message' => $message,
            'data' => $teachers,
        ]);
    }
//****************************************************************************************************** */
public function search_in_teacher(Request $request)
    {

        $name = $request->query('name');

        $teachers= [];
        if($name){
        $teachers = User::where('name', 'like', '%' . $name . '%')
                    ->where('role_id', 3)->get();
        return response()->json([
            'message' => "These are the teachers.",
            'teachers' =>$teachers,
        ]);
    }else{
        return response()->json([
            'message' => "These are the teachers.",
            'teachers' =>$teachers,
        ]);
    }
    }
    //****************************************************************************************************** */

}
