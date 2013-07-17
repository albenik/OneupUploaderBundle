<?php

namespace Oneup\UploaderBundle\Uploader\Naming;

use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UniqidNamer implements NamerInterface
{
    /**
     * @inheritdoc
     */
    public function name(UploadedFile $file, Request $request)
    {
        return sprintf('%s.%s', uniqid(), $file->guessExtension());
    }
}
