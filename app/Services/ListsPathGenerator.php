<?php

namespace App\Services;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

class ListsPathGenerator extends DefaultPathGenerator
{
    public function getPath(Media $media): string
    {
        return '/uploads/';
    }
}
