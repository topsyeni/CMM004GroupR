<?php

namespace App\Http\Controllers;

use App\Models\Questions;
use App\Models\Template;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TemplateController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

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
        $template->company_name = auth()->user()->company_name;
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

        return response()->json([
            'code'=> '200',
            'status'=> 'success',
            'data'=> $template
        ], 200);
    }

    public function getTemplate($id){
        $template = Template::where('id', $id)->where('company_name', auth()->user()->company_name)->with('questions')->first();
        if($template == null){
            return response([
                'code'      =>'404',
                'status'    => 'Not Found',
                'message'   => 'Template not found or deleted'
            ], 404);
        }

        return response([
            'code'      => '200',
            'status'    => 'success',
            'data'      => $template
        ], 200);
    }

    public function getTemplates(){
        $page_size = ($request->page_size ?? 10);
        $page_num = ($request->page_num ?? 1);
        $sort_by = ($request->sort_by ?? 'id');
        $sort_type = ($request->sort_type ?? 'ASC');
        $title = ($request->title ?? '');

        $templates = Template::whereRaw('title LIKE "%'. $title . '%"')
            ->where('company_name', auth()->user()->company_name)
            ->withCount('questions')
            ->orderBy($sort_by, $sort_type)
            ->paginate($page_size, ['*'], 'page', $page_num);

        return response()->json([
            'status'=>'success',
            'message'=>'Templates fetched successfully',
            'data' => $templates
        ], 200);
    }

    public function updateTemplate($id){
        $template = Template::find($id);
        if(!$template){
            return response()->json([
                'code'      => 404,
                'status'    => 'failed',
                'message'   => 'Template not found'
            ], 404);
        }
        if($template->company_name != auth()->user()->company_name)
            return response()->json([
                'code'      => 401,
                'status'    => 'unauthorized',
                'message'   => 'You are not authorized to perform this action'
            ], 404);

        if(isset(request()->title) && !empty(request()->title)) $template->title = request()->title;
        if(isset(request()->short_decription) && !empty(request()->short_decription)) $template->short_decription = request()->short_decription;
        $template->save();

        return response()->json([
            'code'=> '200',
            'status'=> 'success',
            'data'=> $template
        ], 200);
    }

    public function deleteTemplate($id = null){
        $template = Template::find($id);
        if(!$template){
            return response()->json([
                'code'      => 404,
                'status'    => 'failed',
                'message'   => 'Template not found'
            ], 404);
        }
        if($template->company_name != auth()->user()->company_name)
            return response()->json([
                'code'      => 401,
                'status'    => 'unauthorized',
                'message'   => 'You are not authorized to perform this action'
            ], 404);

        $questions = $template->questions;
        if($questions->isNotEmpty()){
            foreach ($questions as $question){
                $question->delete();
            }
        }

        $template->delete();

        return response()->json([
            'code'=> '200',
            'status'=> 'success',
            'message'=> 'Template deletion successful'
        ], 200);
    }

    public function updateQuestion($id){
        $question = Questions::find($id);
        if(!$question){
            return response()->json([
                'code'      => 404,
                'status'    => 'failed',
                'message'   => 'Question not found'
            ], 404);
        }
        if($question->template->company_name != auth()->user()->company_name)
            return response()->json([
                'code'      => 401,
                'status'    => 'unauthorized',
                'message'   => 'You are not authorized to perform this action'
            ], 404);

        if(isset(request()->question) && !empty(request()->question)) $question->question = request()->question;
        if(isset(request()->type) && !empty(request()->type)) $question->type = request()->type;
        if(isset(request()->options) && !empty(request()->options)) $question->options = json_encode(request()->options);
        $question->save();

        return response()->json([
            'code'=> '200',
            'status'=> 'success',
            'data'=> $question
        ], 200);
    }
}
