<?php

declare(strict_types=1);

namespace App\Support\MediaLibrary;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class StableMediaPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return $this->basePath($media).'/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->basePath($media).'/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->basePath($media).'/responsive-images/';
    }

    protected function basePath(Media $media): string
    {
        $modelSegment = Str::kebab(class_basename((string) $media->model_type));
        $collectionSegment = Str::of($media->collection_name)
            ->replace('_', '-')
            ->slug('-')
            ->value();

        return implode('/', array_filter([
            'media',
            $modelSegment,
            (string) $media->model_id,
            $collectionSegment,
        ]));
    }
}
