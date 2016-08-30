<?php

namespace Comus\Core\Models;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

use Comus\Core\Models\ImageModel;
use Comus\Core\Services\FileService;

class ProductModel extends Model
{

    protected $table = 'products';

    protected $fillable = [
        'name', 'description', 'meta_description', 'keywords',  'alias',
        'manufacturer', 'origin', 'availibility', 'size', 'color', 'sku',
        'weight', 'dimension', 'price', 'old_price', 'category_id'
    ];

    /**
     * Relationship with category
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Voids
     */
    public function categories()
    {
        return $this->belongsTo('Comus\Core\Models\CategoryModel');
    }

    /**
     * Relationship with images
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Voids
     */
    public function images()
    {
        return $this->morphMany('Comus\Core\Models\ImageModel', 'imageable');
    }

    /**
     * Create new product
     * @author Thanh Tuan <thanhtuan@cr2011@gmail.com>
     * @param  Array $data Data input
     * @return Array       Status
     */
    public function createNewProduct($data)
    {
        $color = '';

        if (isset($data['color']) && !empty($data['color'])) {
            foreach ($data['color'] as $key => $value) {
                if ($key < (count($data['color']) - 1)) {
                    $color .= $value . ',';
                } else {
                    $color .= $value;
                }
            }
        }

        $data['color'] = $color;

        $product = self::create($data);

        return $product;
    }

    /**
     * Create new images for product
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $images Images
     * @return Int           Status true or false
     */
    public function createImageProduct ($images)
    {
        $status = 0;

        foreach ($images as $key => &$image) {
            $status = $this->images()->create($image);
        }

        return $status;
    }

    /**
     * Upload image
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  File $files File
     * @return Array       File stored
     */
    public function uploadFiles($files)
    {
        // If not exists files
        if(empty($files) || !$files['tmp_name']){
            return ['status' => 0, 'message' => 'upload fail'];
        }

        $ext = pathinfo($files['name'], PATHINFO_EXTENSION);                // File extension
        $fileName = pathinfo($files['name'], PATHINFO_FILENAME);            // File name
        $hash = substr(explode('/',md5(uniqid().time()))[0], 0 ,10);        // Hash to create file name store and folder
        $stored_file_name = strtolower($fileName .'_'. $hash . '.' . $ext); // File name store

        $ext = pathinfo($files['name'], PATHINFO_EXTENSION);                // File extension
        $fileName = pathinfo($files['name'], PATHINFO_FILENAME);            // File name
        $hash = substr(explode('/',md5(uniqid().time()))[0], 0 ,10);        // Hash to create file name store and folder
        $stored_file_name = strtolower($fileName .'_'. $hash . '.' . $ext); // File name store
        $storeDisk = 'local_product';                                      // Disk to store file
        $folder = substr($hash , 0 ,2) .'/'. substr($hash , 2 ,2) .'/';     // Folder contain file

        try {
            $status = FileService::save($stored_file_name, file_get_contents($files['tmp_name']), false, $folder, null, $storeDisk);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        // Data file uploaded
        $data['folder'] = $folder;
        $data['name'] = $files['name'];
        $data['stored_file_name'] = $stored_file_name;
        $data['size'] = $files['size'];

        if($status){
            return ['status' => 1, 'item' => $data];
        }else{
           return ['status' => 0,'message' => 'upload fail'];
        }
    }

    /**
     * Update product
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $data Data input
     * @return Object      Product
     */
    public function updateProduct($data)
    {
        $color = '';

        if (isset($data['color']) && !empty($data['color'])) {
            foreach ($data['color'] as $key => $value) {
                if ($key < (count($data['color']) - 1)) {
                    $color .= $value . ',';
                } else {
                    $color .= $value;
                }
            }
        }

        $data['color'] = $color;
        $status = $this->update($data);

        return $status;
    }

    /**
     * Update images for product
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $images Images
     * @return Int           Status true or false
     */
    public function updateImageProduct ($images) 
    {
        if (count($images) > 0) {

            $status = 0;

            $productImage = new ImageModel;

            // File uploaded
            $filesUpload = $images;

            // Contain all uniIds of files uploaded
            $uniIdsFile = array_pluck($filesUpload, 'uniId');

            // Get all images of product
            $images = $this->images->toArray();

            // Contain all uniIds of product images
            $uniIdsProduct = array_pluck($images, 'uniId');

            $uniIdsDelete = array_diff($uniIdsProduct, $uniIdsFile);

            $imagesDelete = $productImage->whereIn('uniId', $uniIdsDelete)->get();

            // Delete file images product 
            $status = $this->deleteFileImagesProduct($imagesDelete);

            foreach ($filesUpload as $key => $file) {
                $image = $this->images()->where('uniId', $file['uniId'])->first();
                if (!$image) {
                    $status = $this->images()->create($file);
                }
            }
        } else {
            $imagesDelete = $this->images;
            // Delete file images product 
            $status = $this->deleteFileImagesProduct($imagesDelete);
        }

        return $status;
    }

    /**
     * Delete images product
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $imagesDelete Array images
     * @return Void
     */
    public function deleteFileImagesProduct($imagesDelete)
    {
        $status = 0;

        $storeDisk = 'local_product';

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
     * Get list products map with category id
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Array Array prodcut
     */
    public function getListProductMapCategoryId()
    {
        $listMapProductWithCategoryId = [];

        $products = self::select('id', 'name', 'price', 'old_price', 'category_id')->get();

        foreach ($products as $key => &$product) {
            $product->images = $product->images()
                            ->select('folder', 'stored_file_name')
                            ->where('name', 'like', '1%')->first();

            $listMapProductWithCategoryId[$product->category_id][] = $product;
        }

        return $listMapProductWithCategoryId;
    }

    /**
     * get sale prodcuts
     * @author Thanh Tuan  <thanhtuancr2011@gmail.com>
     * @return Array Products
     */
    public function getSaleProducts()
    {
        // Get product has old_price bigger than price
        $saleProducts = self::select('id', 'name', 'price', 'old_price', 'category_id')->whereRaw('old_price > price')->get();

        foreach ($saleProducts as $key => &$product) {
            $product->images = $product->images()
                            ->select('folder', 'stored_file_name')
                            ->where('name', 'like', '1%')->first();

            $listMapProductWithCategoryId[$product->category_id][] = $product;
        }

        return $saleProducts;
    }

    /**
     * Get new products
     * @author Thanh Tuan  <thanhtuancr2011@gmail.com>
     * @return Array Products
     */
    public function getNewProducts()
    {
        $newProducts = ProductModel::select('id', 'name', 'price', 'old_price', 'category_id')->orderBy('created_at', 'desc')->limit(4)->get();

        foreach ($newProducts as $key => &$product) {
            $product->images = $product->images()
                            ->select('folder', 'stored_file_name')
                            ->where('name', 'like', '1%')->first();
            $listMapProductWithCategoryId[$product->category_id][] = $product;
        }

        return $newProducts;
    }

    /**
     * [getProductsWithCategoryId description]
     * @author Thanh Tuan  <thanhtuancr2011@gmail.com>
     * @param  String $categoryId Id of category
     * @return Array              Products
     */
    public function getProductsWithCategoryId($categoryId)
    {
        $products = self::select('id', 'name', 'price', 'old_price', 'category_id')->where('category_id', $categoryId)->get();

        foreach ($products as $key => &$product) {
            $product->images = $product->images()
                            ->select('folder', 'stored_file_name')
                            ->where('name', 'like', '1%')->first();
        }

        return $products;
    }

    public function getProductWithId ($productId)
    {
        $product = self::select('id', 'name', 'price', 'old_price', 'category_id', 'meta_description')->findOrFail($productId);
        $product->images = $product->images()
                            ->select('folder', 'stored_file_name')
                            ->where('name', 'like', '1%')->first();

        return $product;
    }

    /**
     * Delete Product
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Bool Status
     */
    public function deleteProduct()
    {
        $imagesDelete = $this->images;

        // Delete file images
        $this->deleteFileImagesProduct($imagesDelete);

        $this->images()->delete();

        $status = $this->delete();

        return $status;
    }

    /**
     * Get products with name
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String $name Search name
     * @return Array        Products
     */
    public function getProductWithName ($name)
    {
        $products = self::where('name', 'like', '%'.$name.'%')
                        ->select('id', 'name', 'price', 'old_price', 'category_id')
                        ->get();

        foreach ($products as $key => &$product) {
            $product->images = $product->images()
                             ->select('folder', 'stored_file_name')
                             ->where('name', 'like', '1%')->first();
        }

        return $products;
    }
}
