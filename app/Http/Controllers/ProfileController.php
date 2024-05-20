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
        $message = "this is the all teachers.";
        return response()->json([
            'message' => $message,
            'data' => $teacher,
        ]);
    }
    //********************************************************************************************** */
    public function show_one_user($user_id)
    {
        $user = User::where('id', $user_id)->first();
        $courses=null;
        if($user->role_id == 3){
            $courses = Subject::whereHas('years_users', function($q) use ($user_id) {
                $q->where('user_id', $user_id);
            })->get();
        }
        $message = "this is the user.";
        return response()->json([
            'message' => $message,
            'data' => $user,
            'courses'=>$courses,
        ]);
    }
    //********************************************************************************************** */
    public function show_subject_teachers($subject_id)
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
    //********************************************************************************************** */
    public function search_to_teacher(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);
        $input = $request->all();
        $teacher = Teacher::where('name', 'like', '%' . $input['name'] . '%')
                            ->get();
        if ($teacher->isEmpty()) {
            $message = "The teacher doesn't exist.";
            return response()->json([
                'message' => $message,
            ]);
        }
        $message = "This is the teacher.";
        return response()->json([
            'message' => $message,
            'data' => $teacher,
        ]);
    }
//****************************************************************************************************** */
    public function add_teacher(Request $request)
    {
        $user = auth()->user();
        $input = $request->all();
        $request->validate([
            'class_id'=>'required|integer',
            'name' => 'required',
             'image_data' => 'required',
            'description' => 'required',
            ]);
        $teacher = Teacher::create([
            'class_id' => $request->class_id,
            'name' => $request->name,
            'image_data' => $request->image_data,
            'description' => $request->description,
        ]);
        $message = "teacher added successfully.";
        return response()->json([
            'message' => $message,
            'data' => $teacher
        ]);
    }
//********************************************************************************************************** */
    public function edit_teacher(Request $request)
    {
        $user = auth()->user();
        $input = $request->all();
        $request->validate([
            'teacher_id'=>'required|integer',
            'class_id'=>'required|integer',
            'name' => 'required',
            // 'image_data' => 'required',
            'description' => 'required',
            ]);
            $teacher_id = $request->teacher_id;
            $teacher = Teacher::find($teacher_id);

            $teacher->class_id = $request->class_id;
            $teacher->name = $request->name;
            $teacher->image_data = $request->image_data;
            $teacher->description = $request->description;
            $teacher->save();
    $message = "The teacher updated successfully.";
    return response()->json([
        'message' => $message,
        'data' => $teacher
    ]);
}
//*************************************************************************************************** */
    public function delete_teacher($teacher_id)
    {
        $user = auth()->user();
        $teacher = Teacher::find($teacher_id);
        if (!$teacher) {
            $message = "The teacher doesn't exist.";
            return response()->json([
                'message' => $message,
            ]);
        }

        $teacher->subjects()->detach();
        $teacher->delete();

        $message = "The teacher deleted successfully.";
        return response()->json([
            'message' => $message,
        ]);
    }
}
//******************************************************************************************************** */