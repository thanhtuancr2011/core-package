<?php 

namespace Comus\Core\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Comus\Core\Models\CollectionModel;
use App\Http\Controllers\Controller;
use Comus\Core\Http\Requests\CollectionFormRequest;
use Illuminate\Http\JsonResponse;
use Comus\Core\Services\FileService;

class CollectionController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Collection Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

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
     * Create a collection
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CollectionFormRequest $request)
    {
        $status = 0;
        /* Get all data input */
        $data = $request->all();
        /* Init collection model to call function in it */
        $collectionModel = new CollectionModel;
        /* Call function create new collection */
        $collection = $collectionModel->createNewCollection($data);
        /* If collection was created */

        if ($collection) {
            $status = 1;
        }

        /* Return user */
        return new JsonResponse(['collection' => $collection, 'status' => $status]);
    }

    /**
     * Create new image for collection
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Request $request Request
     * @return Int              Status true or false
     */
    public function createImageCollection($id, Request $request) 
    {
        $data = $request->all();
        $images = $data['fileUploaded'];
        $collection = CollectionModel::findOrFail($id);
        $status = $collection->createImageCollection($images);

        return new JsonResponse(['status' => $status, 'collection' => $collection]);
    }

    /**
     * Update a collection.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CollectionFormRequest $request, $id)
    {
        $status = 0;
        /* Get all data input */
        $data = $request->all();
        /* Find collection */
        $collection = CollectionModel::findOrFail($id);
        /* Call function create new collection */
        $result = $collection->updateCollection($data);

        /* If collection was created */
        if ($result) {
            $status = 1;
        }
        
        /* Return collection */
        return new JsonResponse(['collection' => $result, 'status' => $status]);
    }

    /**
     * Update image for collection
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Request $request Request
     * @return Int              Status true or false
     */
    public function updateImageCollection($id, Request $request) 
    {
        $status = 1;

        $data = $request->all();
        
        if (isset($data['fileUploaded'])) {
            $images = $data['fileUploaded'];
            $collection = CollectionModel::findOrFail($id);
            $status = $collection->updateImageCollection($images);
        }

        return new JsonResponse(['status' => $status]);
    }

    /**
     * Remove a collection.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* Find collection */
        $collection = CollectionModel::findOrFail($id);
        /* Delete collection */
        $status = $collection->delete();

        return new JsonResponse(['status' => $status]);
    }

    /**
     * Call function store file
     * @author Thanh Tuan <thanhtuancr2011@gmail.com> 
     * @return Int $status Status
     */
    public function storeImage () 
    {
        $model = new CollectionModel;
        
        $result = FileService::storeFile($model);

        if($result['status'] == 0){
            return new JsonResponse($result, 422);
        }else{
            return new JsonResponse($result);
        }
    }
}
