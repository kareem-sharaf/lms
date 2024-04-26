<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Year;
use App\Models\Stage;
use App\Models\SubjectYear;

 use App\Http\Responses\ApiSuccessResponse;
 use App\Http\Responses\ApiErrorResponse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\TeachersController;

class SubjectController extends Controller
{

    //**********************************************************************************************\/
    //show all subject in the class
  public function show_all_subjects(Request $request)
  {
      $class_id = $request->query('class_id');
      $subject = Subject::where('class_id', $class_id)
      ->get();
      return new ApiSuccessResponse(
        'this is the all subjects in the class.',
        $subject,
       201,
    );
  }
//**********************************************************************************************
//show all subjects in the class education
  public function all_subjects_in_year(Request $request)
    {
        $year_id = $request->query('year_id');
        $subject = Subject::whereHas('years_teachers', function($q) use ($year_id) {
            $q->where('year_id', $year_id);
        })->get();
        $message = "this is the all subjects";
        return response()->json([
            'message' => $message,
            'data' => $subject]);
    }
    //***********************************************************************************************************************\\
    public function search_to_subject(Request $request)
{
    $request->validate([
        'class_id' => 'integer',
        'year_id' => 'integer',
        'name' => 'required|string',
    ]);

    $class_id = $request->query('class_id');
    $year_id = $request->query('year_id');
    $name = $request->query('name');

    if ($class_id == 1) { // if the class is educational
        $subjects = Subject::where('name', 'like', '%' . $name . '%')
            ->whereHas('years_teachers', function ($q) use ($year_id) {
                $q->where('year_id', $year_id);
            })->get();
    } else {
        $subjects = Subject::where('name', 'like', '%' . $name . '%')
           // ->where('class_id', $class_id)//serach in one class
           ->where('class_id', '!=', 1)//search in all classes without educational
            ->get();
    }

    if ($subjects->isEmpty()) {
        return new ApiErrorResponse(
        'subject does not exist.',
       404,
        );

    }

    return response()->json([
        'message' => " this is the subjects .",
        'data' => $subjects,
    ]);
}

    //***********************************************************************************************************************\\
    public function add_subject(Request $request)
{
    $user = auth()->user();
    $request->validate([
        'class_id' => 'required',
        'name' => 'required',
        'price' => 'required',
        'description' => 'required',
        'image_data' ,
        'video_id' => 'integer',
        'file_id' => 'integer',
        'teachers_content' => 'required|array',
        'teachers_content.*.teacher_id' => 'required|integer',
        'years_content.*.year_id' => 'integer',
    ]);

    $subject = Subject::create([
        'name' => $request->name,
        'price' => $request->price,
        'description' => $request->description,
        'image_data' => $request->image_data,
        'video_id' => $request->video_id,
        'file_id' => $request->file_id,
        'class_id' => $request->class_id,
    ]);

    if ($request->class_id == 1) {//if the class is educational
        $yearsContent = $request->years_content;
        $teachersContent = $request->teachers_content;

        foreach ($teachersContent as $teacher) {
            foreach ($yearsContent as $year) {
                $subject->years_teachers()->attach($teacher['teacher_id'], ['year_id' => $year['year_id']]);
            }
        }
    } else {
        foreach ($request->teachers_content as $teacher) {
            $subject->years_teachers()->attach($teacher['teacher_id']);
        }
    }

    return response()->json([
        'message' => 'Subject added successfully.',
        'data' => $subject,
    ]);
}

    //***********************************************************************************************************************\\
    public function edit_subject(Request $request)
    {
        $request->validate([
            'subject_id' => 'required',
            'class_id' => 'required',
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'image_data' ,
            'video_id' => 'integer',
            'file_id' => 'integer',
            'teachers_content' => 'required|array',
            'teachers_content.*.teacher_id' => 'required|integer',
            'years_content.*.year_id' => 'integer',
        ]);

        $subject_id = $request->subject_id;
        $subject = Subject::find($subject_id);

        $subject->name = $request->name;
        $subject->price = $request->price;
        $subject->description = $request->description;
        $subject->class_id = $request->class_id;
        $subject->image_data = $request->image_data;
        $subject->video_id = $request->video_id;
        $subject->file_id = $request->file_id;
        $subject->save();


        if ($request->class_id == 1) { // if the class is educational
            $yearsContent = $request->years_content;
            $teachersContent = $request->teachers_content;

            $subject->years_teachers()->detach();

            foreach ($teachersContent as $teacher) {
                foreach ($yearsContent as $year) {
                    $subject->years_teachers()->attach($teacher['teacher_id'], ['year_id' => $year['year_id']]);
                }
            }
        } else {
            $subject->years_teachers()->detach();

            foreach ($request->teachers_content as $teacher) {
                $subject->years_teachers()->attach($teacher['teacher_id']);
            }
        }

        return response()->json([
            'message' => 'subject updated successfully',
            'data' => $subject,
        ]);
    }
    //***********************************************************************************************************************\\
 public function delete_subject($subject_id)
    {
        $user = auth()->user();
        $subject = Subject::find($subject_id);
        if (!$subject) {
            $message = "The subject doesn't exist.";
            return response()->json([
                'message' => $message,
            ]);
        }

        $subject->years_teachers()->detach();
        $subject->delete();

        $message = "The subject deleted successfully.";
        return response()->json([
            'message' => $message,
        ]);

    }
}
    //***********************************************************************************************************************\\
