<?php  

namespace Comus\Core\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Comus\Core\Models\UserModel;
use Comus\Core\Models\ImageModel;
use Comus\Core\Services\FileService;

class ArticleModel extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'alias_title', 'content', 'description', 'status', 'type', 'user_id'];

    /**
     * Get the article that owns the user..
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Comus\Core\Models\UserModel');
    }

    /**
     * Relationship with images.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Voids 
     */
    public function images()
    {
        return $this->morphMany('Comus\Core\Models\ImageModel', 'imageable');
    }

    /**
     * Get all Articles with type
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $type  $type item
     * @return Array Users
     */
    public function getAllItemWithType($type)
    {
        /* Get all articles */
        $articles = self::whereIn('type', $type)->get();
        
        $userModel = new UserModel;

        /* Get user create article */
        foreach ($articles as $key => &$value) {
            /* Get full name author */
            $value->author = $value->user->first_name . ' ' . $value->user->last_name;
            /* Get avatar for author */
            $value->authAvatar = $userModel->getAvatarUrl($value->user->avatar, '50x50');
            
            unset($value->user);
        }

        return $articles;
    }

    /**
     * Create New Article description
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function createNewArticle($data)
    {
        /* Set author id for article */
        $data['user_id'] = \Auth::user()->id;

        /* Create new article */
        $article = self::create($data);

        return $article;
    }

    /**
     * Create new images for article
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $images Images
     * @return Int           Status true or false
     */
    public function createImageArticle ($images) 
    {
        $status = 0;

        /* Each image */
        foreach ($images as $key => &$image) {
            $status = $this->images()->create($image);
        }

        return $status;
    }

    /**
     * Upload image article
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  File $files File
     * @return Array       File stored
     */
    public function uploadFiles($files)
    {
        /* Upload fail */
        if(empty($files) || !$files['tmp_name']){
            return ['status' => 0, 'message' => 'upload fail'];
        }

        $ext = pathinfo($files['name'], PATHINFO_EXTENSION);                // File extension
        $fileName = pathinfo($files['name'], PATHINFO_FILENAME);            // File name
        $hash = substr(explode('/',md5(uniqid().time()))[0], 0 ,10);        // Hash to create file name store and folder
        $stored_file_name = strtolower($fileName .'_'. $hash . '.' . $ext); // File name store        
        $storeDisk = 'local_article';                                       // Disk to store file  
        $folder = substr($hash , 0 ,2) .'/'. substr($hash , 2 ,2) .'/';     // Folder contain file

        try {
            $status = FileService::save($stored_file_name, file_get_contents($files['tmp_name']), false, $folder, null, $storeDisk); 
        } catch (Exception $e){
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        
        // Data file uploaded
        $data['folder'] = $folder;
        $data['name'] = $files['name'];
        $data['stored_file_name'] = $stored_file_name;
        $data['size'] = $files['size'];

        if ($status){
            return ['status' =>1, 'item' => $data];
        } else {
           return ['status'=>0,'message' => 'upload fail'];
        }
    }

    /**
     * Update Article
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $data  Data input 
     * @return Object       Article
     */
    public function updateArticle($data)
    {
        /* Update Article */
        $this->update($data);
        
        return $this;
    }

    /**
     * Update images for article
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $images Images
     * @return Int           Status true or false
     */
    public function updateImageArticle ($images) 
    {
        if (count($images) > 0) {
            foreach ($images as $key => $value) {
                if (isset($value['id'])) {
                    $imagesDelete = $this->images()->where('uniId', $value['uniId'])->get();
                    // Delete file images article 
                    $status = $this->deleteFileImagesArticle($imagesDelete);
                } else {
                    $status = $this->images()->create($value);
                }
            }
        } else {
            $imagesDelete = $this->images;
            $status = $this->deleteFileImagesArticle($imagesDelete);
        }

        return $status;
    }

    /**
     * Delete images article
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $imagesDelete Array images
     * @return Void               
     */
    public function deleteFileImagesArticle($imagesDelete) 
    {
        $status = 0;

        $storeDisk = 'local_article';

        // Each image want delete
        foreach ($imagesDelete as $key => $imageDelete) {
            // Folder contain image
            $folderName = $imageDelete->folder;
            // Folder delete
            $folderNameDelete = explode('/', $folderName);
            // Delete folder
            $status = FileService::deleteDirectory($folderNameDelete[0], $storeDisk);
            // Delete image in database
            $this->images()->where('uniId', $imageDelete->uniId)->delete();
        }

        return $status;
    }
}
