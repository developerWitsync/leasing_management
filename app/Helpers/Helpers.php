<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 14/11/18
 * Time: 4:36 PM
 */
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

/**
 * upload the images to the path passed and create the thumnail if required
 * @param UploadedFile $originalImage
 * @param string $path
 * @param bool $thumbnail_required
 * @param bool $remove_path
 * @return string
 */
function uploadImage(UploadedFile $originalImage, $path = '', $thumbnail_required = false, $remove_path = false){

    $time = time();

    $thumbnailImage = Image::make($originalImage);

    $thumbnailPath  = "{$path}/thumbnail/";

    $originalPath   = $path;

    if($remove_path) {
        File::deleteDirectory($originalPath);
    }

    if(!file_exists($thumbnailPath)) {
        mkdir($thumbnailPath, 0777, true);
    }

    $thumbnailImage->save($originalPath.$time.$originalImage->getClientOriginalName());

    if($thumbnail_required) {

        $thumbnailImage->resize(null, 150, function ($constraint) {
            $constraint->aspectRatio();
        });

        $thumbnailImage->save($thumbnailPath.$time.$originalImage->getClientOriginalName());
    }

    $file_name = $time.$originalImage->getClientOriginalName();

    return $file_name;
}

/**
 * returns the path for the users profile pic and if the $return_thumbnail is true than returns the path for the thumbnail image for the user
 * @param null $user_id
 * @param null $profile_pic
 * @param bool $return_thumbnail
 * @return bool|string
 */
function getUserProfileImageSrc($user_id = null, $profile_pic = null, $return_thumbnail = false){
    if($user_id) {
        $path = "user/{$user_id}/profile_pic/";
        if($return_thumbnail) {
            $path .= 'thumbnail/';
        }

        if($profile_pic) {
            return asset($path.$profile_pic);
        } else {
            return asset('assets/images/avatars/avatar1.png');
        }

    } else {
        return false;
    }
}

/**
 * returns the ids for the dependent childrens as well
 * since the super_admin can also make the changes to the leases added by his/her sub-users
 * @return array
 */

function getDependentUserIds(){
    $userIdsWithChildrens = Auth::user()->childrens->pluck('id')->toArray();
    if(auth()->user()->parent_id != 0){
        $userIdsWithChildrens = array_merge($userIdsWithChildrens, [auth()->user()->parent_id]);
    }
    return array_merge($userIdsWithChildrens, [auth()->user()->id]);
}
