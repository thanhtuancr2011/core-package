<?php 
namespace Comus\Core\Models;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

use Comus\Core\Models\ImageModel;
use Comus\Core\Models\ProductModel;
use Comus\Core\Services\FileService;

class CategoryModel extends Model
{
    protected $table = 'categories';

    protected $fillable = ['name', 'alias', 'sort_order', 'parent_id', 'ancestor_ids', 'keywords', 'description'];

    /**
     * Relationship with categories.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Voids 
     */
    public function childrenCategories()
    {
        return $this->hasMany('Comus\Core\Models\CategoryModel', 'parent_id');
    }

    /**
     * Relationship with products.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Voids 
     */
    public function products()
    {
        return $this->hasMany('Comus\Core\Models\ProductModel', 'category_id');
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
     * Get categories with tree format.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  integer $parent_id Id parent of category
     * @return Array              Hierachy categories
     */
    public function getCategoriesTree ($parent_id = 0)
    {
    	$categories = self::orderBy('sort_order', 'asc')->get()->toArray();

        /* Array contain array categories reference */ 
        $referenceCategories = [];
        foreach ($categories as &$category) {
            $category['subFolder'] = [];
            $category['ancestor_ids'] = explode(',', $category['ancestor_ids']);
            $referenceCategories[$category['id']] = $category;
        }

        /* Put a folder to property subFolder of a parent folder that it should belong to */ 
        foreach ($categories as &$category) {
            if(!empty($category['parent_id'])){
                $referenceCategories[$category['parent_id']]['subFolder'][] = &$referenceCategories[$category['id']];
            }
        }

        /* Get root folders */ 
        $hierachyCategories = $referenceCategories;
        foreach($hierachyCategories as $key => $hierachyCategory){
            if(!empty($hierachyCategory['parent_id']) && ($hierachyCategory['parent_id']!= '0')){
                unset($hierachyCategories[$key]);
            }
        }

        return array_values($hierachyCategories);
    }

    /**
     * Create new category
     * @author Thanh Tuan <thanhtuan@cr2011@gmail.com>
     * @param  Array $data Data input
     * @return Array       Status
     */
    public function createNewCategory($data)
    {   
        /* Contain ancestor_ids */
        $data['ancestor_ids'] = '';

        /* Set parent_id if data input not has */ 
        if (!isset($data['parent_id'])) {
            /* Get category root */
            $categoryRoot = self::where('parent_id', 0)->first();
            $data['parent_id'] = $categoryRoot->id;
            $data['ancestor_ids'] = $categoryRoot->id . ',' . $categoryRoot->parent_id;
        } else {
            $category = self::findOrFail($data['parent_id']);
            $data['ancestor_ids'] .= $data['parent_id'] . ',' . $category->ancestor_ids;
        }

        /* Set alias */ 
        $data['alias'] = str_slug($data['name'], '_');

        $category = self::create($data);

        return $category;
    }

    /**
     * Create new images for category
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $images Images
     * @return Int           Status true or false
     */
    public function createImageCategory ($images) 
    {
        $status = 0;

        /* Each image */
        foreach ($images as $key => &$image) {
            $status = $this->images()->create($image);
        }

        return $status;
    }

    /**
     * Update category
     * @author Thanh Tuan <thanhtuan@cr2011@gmail.com>
     * @param  Array $data Data input
     * @return Array       Status
     */
    public function updateCategory($data)
    {   
        /* Contain ancestor_ids */
        $data['ancestor_ids'] = '';

        /* Set parent_id if data input not has */ 
        if (!isset($data['parent_id'])) {
            /* Get category root */
            $categoryRoot = self::where('parent_id', 0)->first();
            $data['parent_id'] = $categoryRoot->id;
            $data['ancestor_ids'] = $categoryRoot->id . ',' . $categoryRoot->parent_id;
        } else {
            $category = self::findOrFail($data['parent_id']);
            $data['ancestor_ids'] .= $data['parent_id'] . ',' . $category->ancestor_ids;
        }   

        // Set alias
        $data['alias'] = str_slug($data['name'], '_');

        // Set keywords
        if (isset($data['keywords'])) {
            $data['keywords'] = str_slug($data['keywords'], '_');
        }

        $status = $this->update($data);

        return $status;
    }

    /**
     * Update images for category
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $images Images
     * @return Int           Status true or false
     */
    public function updateImageCategory ($images) 
    {
        if (count($images) > 0) {
            foreach ($images as $key => $value) {
                if (isset($value['id'])) {
                    $imagesDelete = $this->images()->where('uniId', $value['uniId'])->get();
                    // Delete file images category 
                    $status = $this->deleteFileImagesCategory($imagesDelete);
                } else {
                    $status = $this->images()->create($value);
                }
            }
        } else {
            $imagesDelete = $this->images;
            $status = $this->deleteFileImagesCategory($imagesDelete);
        }

        return $status;
    }

    /**
     * Delete images category
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $imagesDelete Array images
     * @return Void               
     */
    public function deleteFileImagesCategory($imagesDelete) 
    {
        $status = 0;

        $storeDisk = 'local_category';

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

    /**
     * Delete category and all child category of category want delete
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Int $id Category id
     * @return Void     
     */
    public function deleteCategory($id)
    {
        // Find Category
        $category = self::find($id);

        // Find all child
        $childrenCategories = $category->childrenCategories;

        // If category has child
        if (!empty($childrenCategories)) {
            // Each child
            foreach ($childrenCategories as $childrenCategory) {
                // Call function delete category
                $this->deleteCategory($childrenCategory->id);
            }
        }

        $imagesDelete = $category->images;

        // Delete file images
        $this->deleteFileImagesCategory($imagesDelete);

        $category->images()->delete();

        // Products of category
        $products = $category->products;

        // Each product -> call function delete product
        foreach ($products as $product) {
            $product->deleteProduct();
        }

        $category->delete();
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
        $storeDisk = 'local_category';                                      // Disk to store file  
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
     * Get category with categoryId is 0
     *
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * 
     * @return Object Category
     */
    public function getRootCategory()
    {
        return self::where('parent_id', 0)->first();
    }
}