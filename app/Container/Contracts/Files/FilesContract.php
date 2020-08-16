<?php

/**
 * Created by PhpStorm.
 * User: Backend Dev
 * Date: 9/25/2018
 * Time: 4:14 PM
 */

namespace Contracts\Files;


use App\File;

interface FilesContract
{
    public function __construct(File $file);

    public function get($id);
    public function getAll();
    public function getPaginated();
    public function set($file, $watermark = null, $data = null);
    public function setByUrl($url);
    public function uploadSingle($file);
    public function encodeImage($image);
    public function update($file, $id);
    public function delete($id);
    public function uploadCertificate($file);
    public function uploadTeacherProfile($image);
}
