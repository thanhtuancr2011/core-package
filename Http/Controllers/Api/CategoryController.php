<?php 

namespace Comus\Core\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Requests;
use Comus\Core\Models\CategoryModel;
use App\Http\Controllers\Controller;
use Comus\Core\Services\FileService;
use Comus\Core\Http\Requests\CategoryFormRequest;

class CategoryController extends Controller 
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
	 * Create new category
	 * @author Thanh Tuan <thanhtuancr2011@gmail.com>
	 * @param  CategoryFormRequest $request Form Request
	 * @return Response
	 */
	public function store(CategoryFormRequest $request)
	{
		$status = 0;
		$data = $request->all();
		$categoryModel = new CategoryModel;
		$category = $categoryModel->createNewCategory($data);

		if ($category) {
			$status = 1;
		}

		return new JsonResponse(['status' => $status, 'category' => $category]);
	}

	/**
     * Create new image for category
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Request $request Request
     * @return Int              Status true or false
     */
    public function createImageCategory($id, Request $request) 
    {
        $data = $request->all();
        $images = $data['fileUploaded'];
        $category = CategoryModel::findOrFail($id);
        $status = $category->createImageCategory($images);

        return new JsonResponse(['status' => $status, 'category' => $category]);
    }

	/**
	 * Edit category
	 * @author Thanh Tuan <thanhtuancr2011@gmail.com>
	 * @param  Int                 $id      Category id
	 * @param  CategoryFormRequest $request Form request
	 * @return Response                       
	 */
	public function update($id, CategoryFormRequest $request)
	{
		$status = 0;
        $data = $request->all();
		$category = CategoryModel::findOrFail($id);
		$status = $category->updateCategory($data);
		
		return new JsonResponse(['status' => $status, 'category' => $category]);
	}
    
    /**
     * Update image for category
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Request $request Request
     * @return Int              Status true or false
     */
    public function updateImageCategory($id, Request $request) 
    {
        $status = 1;

        $data = $request->all();

        if (isset($data['fileUploaded'])) {
            $images = $data['fileUploaded'];
            $category = CategoryModel::findOrFail($id);
            $status = $category->updateImageCategory($images);
        }

        return new JsonResponse(['status' => $status]);
    }

	/**
     * Remove the category from storage.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy ($id)
    {
        $status = 0;

        $categoryModel = new CategoryModel;

        $categoryModel->deleteCategory($id);

        return new JsonResponse(['status' => 1]);
    }

    /**
     * Call function store file
     * @author Thanh Tuan <thanhtuancr2011@gmail.com> 
     * @return Int $status Status
     */
    public function storeImage () 
    {
    	$model = new CategoryModel;
        
    	$result = FileService::storeFile($model);

        if($result['status'] == 0){
            return new JsonResponse($result, 422);
        }else{
            return new JsonResponse($result);
        }
    }
}
