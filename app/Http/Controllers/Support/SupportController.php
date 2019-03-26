<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 25/3/19
 * Time: 7:21 PM
 */

namespace App\Http\Controllers\Support;

use App\Mail\SupportTicketForAdmin;
use App\Mail\SupportTicketForUser;
use App\SupportTickets;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;

class SupportController extends Controller
{
    /**
     * create the support ticket and send an email to the INFO_EMAIL from env and to the user as well..
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Request $request){
        try{
            if($request->ajax()){

                $validator = Validator::make($request->all(),[
                    'subject' => 'required',
                    'message' => 'required',
                    'severity' => 'required',
                    'file' => config('settings.file_size_limits.file_rule')
                ],[
                    'file.max' => 'Maximum file size can be '.config('settings.file_size_limits.max_size_in_mbs').'.',
                    'file.uploaded' => 'Maximum file size can be '.config('settings.file_size_limits.max_size_in_mbs').'.'
                ]);

                if($validator->fails()){
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors()
                    ], 200);
                }

                $ticket_number = SupportTickets::query()->count() + 1;
                $ticket_number = str_pad($ticket_number, 6, '0', STR_PAD_LEFT);

                $uniqueFileName = null;

                if($request->hasFile('file')){
                    $file = $request->file('file');
                    $uniqueFileName = uniqid() . $file->getClientOriginalName();
                    $request->file('file')->move('uploads', $uniqueFileName);
                }

                $request->request->add([
                    'business_account_id' => getParentDetails()->id,
                    'ticket_number' => $ticket_number,
                    'attachment' => $uniqueFileName
                ]);

                $ticket = SupportTickets::create($request->except('_token'));

                if($ticket){
                    \Mail::to($ticket->user)->queue(new SupportTicketForUser($ticket)); //send an email to the user for generating the support ticket
                    \Mail::to(env('INFO_EMAIL'))->queue(new SupportTicketForAdmin($ticket)); //send an email to the admin for generating the support ticket
                    return response()->json([
                        'status' => true,
                        'message' => "<strong>Success!</strong> Your support ticket has been created successfully. Your ticket number is : <strong>{$ticket_number}</strong>. Our Support Team will contact you soon."
                    ], 200);
                }

            } else {
                return redirect('/');
            }
        } catch (\Exception $e){
            abort(404);
        }
    }
}