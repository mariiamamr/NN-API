<?php
namespace App\Container\Contracts\Files;

use App\Container\Contracts\Files\ImagesContract;
use ImageLib;

class ImagesEloquent
{
    private $image;

    public function __construct($image)
    {
        $this->image = $image;
    }

    public function getRealPath()
    {
       return $this->image->basePath();
    }
}
