<?php

namespace App\Services;

use Spatie\MediaLibrary\UrlGenerator\BaseUrlGenerator;

class MediaLibraryUrlGenerator extends BaseUrlGenerator
{
    /**
     * Get the url of a media item.
     *
     */
    public function getUrl() :  string
    {
        return storage_path('media/') . $this->getPathRelativeToRoot();
    }
}