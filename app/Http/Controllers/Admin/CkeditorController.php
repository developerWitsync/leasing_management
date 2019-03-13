<?php
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;



class CkeditorController extends Controller
{

    public function uploadImage(Request $request) {

		$CKEditor = $request->input('CKEditor');
		$funcNum  = $request->input('CKEditorFuncNum');
		$message  = $url = '';
		if (Input::hasFile('upload')) {
		$file = Input::file('upload');
		if ($file->isValid()) {
		$filename =rand(1000,9999).$file->getClientOriginalName();
		$file->move(public_path().'/uploads/', $filename);
		$url = url('uploads/' . $filename);
		} else {
		$message = 'An error occurred while uploading the file.';
		}
		} else {
		$message = 'No file uploaded.';
		}
		return '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';
	}
}
?>