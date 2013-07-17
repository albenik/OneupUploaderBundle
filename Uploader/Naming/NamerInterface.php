<?php

namespace Oneup\UploaderBundle\Uploader\Naming;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

interface NamerInterface
{
    /**
     * @param UploadedFile $file
     * @param Request      $request
     * @return string
     */
    public function name(UploadedFile $file, Request $request);
}
