<?php
namespace Contracts\Files;

use ImageLib;

interface ImagesContract
{
    public function __construct(ImageLib $imag);
    public function getRealPath();
}
