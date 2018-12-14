<?php
/**
 * Created by PhpStorm.
 * User: flexsin
 * Date: 30/10/18
 * Time: 9:46 AM
 */

namespace App\Http\Controllers\Admin;


use App\EmailTemplates;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class EmailTemplatesController extends Controller
{
    public function index(){
        return view('admin.email-template.index');
    }

    /**
     * Fetches and returns the json to be rendered on the datatable.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetch(Request $request){
        try{
            if ($request->ajax()) {

                $model = EmailTemplates::query();

                return datatables()->eloquent($model)
                    ->filter(function ($query) use ($request){
                        if ($request->has('search') && trim($request->search["value"])!="") {
                            $query->where('title', 'like', "%" . $request->search["value"] . "%");
                        }
                    })

                    ->toJson();

            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            return redirect()->back();
        }
    }

    /**
     * fetches the template and renders the view with the preview
     * @param $template_code
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function preview($template_code, Request $request){
        try{
            $template = EmailTemplates::query()->where("template_code", '=', strtoupper($template_code))->first();
            if($template){
                return view('admin.email-template.preview', compact('template'));
            } else {
                abort('404');
            }
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    /**
     * renders the update form for the email template and if the request method is post than in that case save the attributes to the database as well.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id, Request $request){
        try{
            $template = EmailTemplates::query()->find($id);

            if($template){

                if($request->isMethod('post')){

                    //since we will not allow to update the template code and special variables, hence adding the fields from here
                    $request->request->add(['template_special_variables' => $template->template_special_variables ]);

                    $request->request->add(['template_code' => $template->template_code]);

                    $validator = Validator::make($request->except("_token"), [
                        "title" => 'required',
                        'template_subject' => 'required',
                        'template_code' => 'required|unique:email_templates,template_code,'.$id.',id',
                        'template_body' => 'required',
                        'template_special_variables'    => 'required'
                    ]);

                    if($validator->fails()){
                        return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                    }

                    $template->setRawAttributes($request->except("_token"));

                    $template->save();

                    return redirect(route('admin.emailtemplates.index'))->with('success', 'Email template has been updated successfully.');
                }

                return view('admin.email-template.update',compact('template'));

            } else {
                abort('404');
            }
        } catch (\Exception $e) {
            abort('404');
        }
    }
}