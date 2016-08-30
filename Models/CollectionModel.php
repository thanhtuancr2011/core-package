<?php  

namespace Comus\Core\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Comus\Core\Models\UserModel;
use Comus\Core\Models\ImageModel;
use Comus\Core\Services\FileService;

class CollectionModel extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'collections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'alias_title', 'content', 'description', 'status', 'type', 'user_id'];

    /**
     * Get the collection that owns the user..
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
     * Create New Collection description
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function createNewCollection($data)
    {
        /* Set author id for collection */
        $data['user_id'] = \Auth::user()->id;

        /* Create new collection */
        $collection = self::create($data);

        return $collection;
    }

    /**
     * Create new images for collection
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $images Images
     * @return Int           Status true or false
     */
    public function createImageCollection ($images) 
    {
        $status = 0;
        foreach ($images as $key => &$image) {
            $status = $this->images()->create($image);
        }

        return $status;
    }

    /**
     * Upload image category
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
        $storeDisk = 'local_collection';                                    // Disk to store file  
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
     * Update Collection
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $data  Data input 
     * @return Object       Collection
     */
    public function updateCollection($data)
    {
        /* Update Collection */
        $this->update($data);
        
        return $this;
    }

    /**
     * Update images for collection
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $images Images
     * @return Int           Status true or false
     */
    public function updateImageCollection ($images) 
    {
        if (count($images) > 0) {
            $status = 0;

            $collectionImage = new ImageModel;

            // File uploaded
            $filesUpload = $images;

            // Contain all uniIds of files uploaded
            $uniIdsFile = array_pluck($filesUpload, 'uniId');

            // Get all images of collection
            $images = $this->images->toArray();

            // Contain all uniIds of collection images
            $uniIdsCollection = array_pluck($images, 'uniId');

            $uniIdsDelete = array_diff($uniIdsCollection, $uniIdsFile);

            $imagesDelete = $collectionImage->whereIn('uniId', $uniIdsDelete)->get();

            // Delete file images collection 
            $status = $this->deleteFileImagesCollection($imagesDelete);

            foreach ($filesUpload as $key => $file) {
                $image = $this->images()->where('uniId', $file['uniId'])->first();
                if (!$image) {
                    $status = $this->images()->create($file);
                }
            }
        } else {
            $imagesDelete = $this->images;
            // Delete file images collection 
            $status = $this->deleteFileImagesCollection($imagesDelete);
        }

        return $status;
    }

    /**
     * Delete images collection
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $imagesDelete Array images
     * @return Void               
     */
    public function deleteFileImagesCollection($imagesDelete) 
    {
        $status = 0;

        $storeDisk = 'local_collection';

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
