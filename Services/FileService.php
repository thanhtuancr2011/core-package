<?php 

namespace Comus\Core\Services;

use File;
use Storage;
use Crypt;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Routing\ResponseFactory;

class FileService{
    static $paths = [
        'default'  => 'files/',
        'user'     => 'users/',
    ];

    const CIPHER = MCRYPT_RIJNDAEL_128; // Rijndael-128 is AES
    const MODE   = MCRYPT_MODE_CBC;

    /**
     * Save file
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String  $filename File name
     * @param  Object  $contents File content
     * @param  boolean $crypt    Is crypt
     * @param  string  $folder   Folder name to contain file
     * @param  string  $keyPath  Location disk to contain file
     * @param  string  $disk     [description]
     * @return boolean           Status
     */
    public static function save($filename, $contents, $crypt = false, $folder = 'default', $keyPath = 'default', $disk = 'null')
    {
        // Crypt file
        if($crypt){
            $contents = Crypt::encrypt($contents);
        }

        $status = Storage::disk($disk)->put($folder.$filename, $contents);

        return $status;
    }

    /**
     * Get content of file
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String $filename File name
     * @param  String $keyPath  Path
     * @return Object           Content file
     */
    public static function get($filename, $folder, $keyPath)
    {
        $contents = Storage::get(self::$paths[$keyPath].$folder.$filename);
        return $contents;
    }

    /**
     * Delete directory
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String $filename File name
     * @param  String $keyPath  Path
     */
    public static function deleteDirectory($folder, $keyPath)
    {
        $status = Storage::disk($keyPath)->deleteDirectory($folder);

        return $status;
    }

    /**
     * Render file from url
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String $filename File name
     * @param  String $keyPath  Path
     * @return [type]           [description]
     */
    public static function view($filename, $crypt, $folder, $keyPath){

        $contents = self::get($filename, $folder, $keyPath);

        $payload = json_decode(base64_decode($contents), true);

        if($crypt && $payload){
            $contents = Crypt::decrypt($contents);
        }

        return (new Response($contents, 200))->header('Content-Type', self::mime_content_type($filename));

    }
    
    /**
     * Download file
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String $filename File name
     * @param  Bollen $crypt    Has crypt
     * @param  String $folder   Folder name
     * @param  String $keyPath  Path
     */
    public function download($filename, $crypt, $folder, $keyPath)
    {
        $contents = self::get($filename, $folder, $keyPath);
        $payload = json_decode(base64_decode($contents), true);
        if($payload){
            $contents = Crypt::decrypt($contents);
            Storage::put(self::$paths[$keyPath].$folder.$filename, $contents);
        }
        return response()->download(storage_path().'/app/'.self::$paths[$keyPath].$folder.$filename);
    }

    /**
     * Mime content type
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String $filename File name
     */
    public static function mime_content_type($filename) {

        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
       
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        return @$mime_types[$ext];
    }
    /**
     * Save avatar
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String $content Content of file
     * @param  Int    $userId  User id
     * @return String          File name
     */
    public static function saveAvatar($content, $userId)
    {
        $time = time();

        $fileName = substr(Crypt::encrypt( uniqid(). $time.'-avatar-'.$userId), 6, 16).'.png';

        $pathImage = public_path().'/core/avatars/'.$fileName;

        try {
            Storage::disk('local_avatar')->put($fileName, $content);
                    $image = \Image::make($pathImage);
                    $image->crop($image->width(), $image->width(), 0)
                        ->save(public_path().'/core/avatars/'.$fileName);
                    $image->resize(160, 160)
                        ->save(public_path().'/core/avatars/160x160_'.$fileName);
                    $image->resize(100, 100)
                        ->save(public_path().'/core/avatars/100x100_'.$fileName);
                    $image->resize(50, 50)
                        ->save(public_path().'/core/avatars/50x50_'.$fileName);
                    $image->resize(30, 30)
                        ->save(public_path().'/core/avatars/30x30_'.$fileName);
        } catch(Exeption $e) {

            return array('error'=>$e->getMessage());
        }

        return $fileName;
    }

    /**
     * Delete avatar
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String $filename File name
     */
    public static function deleteAvatar($filename){

        $exists = Storage::disk('local_avatar')->exists($filename);

        if($exists){
            Storage::disk('local_avatar')->delete($filename);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Response
     */
    public static function storeFile($model)
    {
        ini_set('memory_limit', '-1');

        if (!empty($_FILES)) {

            if(empty($_FILES['file'])){
                $message = 'Max file size is '. ini_get("upload_max_filesize");
                    return new JsonResponse(['status' => 0, 'message' => $message], 422);
            } else {
                if ($_FILES['file']['error'] > 0) {
                    $error = $this->codeToMessage($_FILES['file']['error']);
                    return new JsonResponse(['status' => 0, 'message' => $error], 422);
                }
            }
            
            $result = $model->uploadFiles($_FILES['file']);

            return $result;

        }
    }

    /**
     * Set message when upload file
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return String
     */
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
}