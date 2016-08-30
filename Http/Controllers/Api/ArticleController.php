<?php 

namespace Comus\Core\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Comus\Core\Models\ArticleModel;
use App\Http\Controllers\Controller;
use Comus\Core\Http\Requests\ArticleFormRequest;
use Illuminate\Http\JsonResponse;
use Comus\Core\Services\FileService;

class ArticleController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Article Controller
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
     * Create a article
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleFormRequest $request)
    {
        $status = 0;
        /* Get all data input */
        $data = $request->all();
        /* Init article model to call function in it */
        $articleModel = new ArticleModel;
        /* Call function create new article */
        $article = $articleModel->createNewArticle($data);
        /* If article was created */

        if ($article) {
            $status = 1;
        }

        /* Return user */
        return new JsonResponse(['article' => $article, 'status' => $status]);
    }

    /**
     * Create new image for article
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Request $request Request
     * @return Int              Status true or false
     */
    public function createImageArticle($id, Request $request) 
    {
        $data = $request->all();
        $images = $data['fileUploaded'];
        $article = ArticleModel::findOrFail($id);
        $status = $article->createImageArticle($images);

        return new JsonResponse(['status' => $status, 'article' => $article]);
    }

    /**
     * Update a article.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleFormRequest $request, $id)
    {
        $status = 0;
        /* Get all data input */
        $data = $request->all();
        /* Find article */
        $article = ArticleModel::findOrFail($id);
        /* Call function create new article */
        $result = $article->updateArticle($data);

        /* If article was created */
        if ($result) {
            $status = 1;
        }
        
        /* Return article */
        return new JsonResponse(['article' => $result, 'status' => $status]);
    }

    /**
     * Update image for article
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Request $request Request
     * @return Int              Status true or false
     */
    public function updateImageArticle($id, Request $request) 
    {
        $status = 1;

        $data = $request->all();

        if (isset($data['fileUploaded'])) {
            $images = $data['fileUploaded'];
            $article = ArticleModel::findOrFail($id);
            $status = $article->updateImageArticle($images);
        }

        return new JsonResponse(['status' => $status]);
    }

    /**
     * Remove a article.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* Find article */
        $article = ArticleModel::findOrFail($id);
        /* Delete article */
        $status = $article->delete();

        return new JsonResponse(['status' => $status]);
    }

    /**
     * Call function store file
     * @author Thanh Tuan <thanhtuancr2011@gmail.com> 
     * @return Int $status Status
     */
    public function storeImage () 
    {
        $model = new ArticleModel;
        
        $result = FileService::storeFile($model);

        if($result['status'] == 0){
            return new JsonResponse($result, 422);
        }else{
            return new JsonResponse($result);
        }
    }
}
