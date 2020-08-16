<?php
namespace Repos\Files;

use Contracts\Files\ImagesContract;
use ImageLib;

class ImagesRepo
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
