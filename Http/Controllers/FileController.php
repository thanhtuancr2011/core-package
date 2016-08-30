<?php 

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Models\CategoryModel;
use App\Models\UserModel;
use File;
use Crypt;

class FileCategoryController extends Controller 
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		ini_set('memory_limit', '-1');

		$categoryModel = new CategoryModel;

		if (!empty($_FILES)) {
			
			if(empty($_FILES['file'])){
				$message = 'Max file size is '. ini_get("upload_max_filesize");
					return new JsonResponse(['status'=>0, 'message'=> $message], 422);
			} else {
				if ($_FILES['file']['error'] > 0) {
					$error = $this->codeToMessage($_FILES['file']['error']);
					return new JsonResponse(['status'=>0, 'message'=> $error], 422);
				}
			}

			$result = $categoryModel->uploadFiles($_FILES['file']);

			if($result['status'] == 0){
				return new JsonResponse($result, 422);
			}else{
				return new JsonResponse($result);
			}
		} else {
			return new JsonResponse(['status' => 1]);
		}
	}

	private function codeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = 'Max file size is '. ini_get("upload_max_filesize");
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$file = CategoryModel::find($id);
		$fileService = new FileService;
		return $fileService->view( strtolower($file['stored_file_name']) , true, $file['folder_file'], 'default');
	}
	/**
	 * [download]
	 * @param  [type] $id file need download
	 * @return [type]     [description]
	 */
	public function download($id)
	{
		$file = CategoryModel::find($id);
		$fileService = new FileService;
		return $fileService->download( strtolower($file['stored_file_name']) , true, $file['folder_file'], 'default');
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$file = CategoryModel::find($id);

		$status = 0;

		if(!empty($file)){
			$status = $file->delete();
			if($status != 0){
				$fileService = new FileService;
				$fileService->delete($file['stored_file_name'], $file['folder_file'],'default');
			}
		}

		return new JsonResponse(['status'=>$status]);
	}





}

