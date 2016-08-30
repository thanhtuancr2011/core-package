<?php 

namespace Comus\Core\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Comus\Core\Models\ArticleModel;
use App\Http\Controllers\Controller;
use Comus\Core\Http\Requests\ArticleFormRequest;
use Illuminate\Http\JsonResponse;

class PageController extends Controller {

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
}
