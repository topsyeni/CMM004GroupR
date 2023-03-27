<?php

namespace App\Http\Controllers;

use App\Models\Questions;
use App\Models\Template;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TemplateController extends Controller
{
    //
    public function createTemplate(){
        if(empty(request()->questions)) return \response()->json("A template must contain at least one question", 400);
        $validator = Validator::make(request()->all(), [
            'title' => 'required',
            'questions.*.question' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }


        $template = new Template();
        $template->title = request()->title;
        $template->short_description = request()->short_description ?? null;
        if($template->save()) {
            foreach (request()->questions as $quest){
                $question = new Questions();
                $question->question = $quest["question"];
                $question->template_id = $template->id;
                $question->type = $quest["type"] ?? "Text";
                $question->options = (!empty($quest["options"])) ? json_encode($quest["options"]) : null;
                $question->save();
            }
        }
        $id = $template->id;

//        $template = Template::find($id)->with('questions');

        return response()->json([
            'code'=> '200',
            'status'=> 'success',
            'data'=> $template
        ], 200);
    }
}
