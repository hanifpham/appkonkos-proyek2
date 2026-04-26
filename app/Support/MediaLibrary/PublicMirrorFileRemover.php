<?php

declare(strict_types=1);

namespace App\Support\MediaLibrary;

use Illuminate\Contracts\Filesystem\Factory;
use Spatie\MediaLibrary\MediaCollections\Filesystem;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\FileRemover\DefaultFileRemover;

class PublicMirrorFileRemover extends DefaultFileRemover
{
    public function __construct(Filesystem $mediaFileSystem, Factory $filesystem)
    {
        parent::__construct($mediaFileSystem, $filesystem);
    }

    public function removeAllFiles(Media $media): void
    {
        parent::removeAllFiles($media);

        PublicStorageMirror::deleteFile($media->getPathRelativeToRoot());

        foreach ($media->getMediaConversionNames() as $conversionName) {
            PublicStorageMirror::deleteFile($media->getPathRelativeToRoot($conversionName));
        }
    }

    public function removeResponsiveImages(Media $media, string $conversionName): void
    {
        parent::removeResponsiveImages($media, $conversionName);

        $baseDirectory = dirname(PublicStorageMirror::normalizeRelativePath($media->getPathRelativeToRoot()));

        if ($baseDirectory === '' || $baseDirectory === '.') {
            return;
        }

        foreach ($media->responsiveImages($conversionName)->getFilenames() as $filename) {
            PublicStorageMirror::deleteFile($baseDirectory.'/responsive-images/'.$filename);
        }
    }

    public function removeFile(string $path, string $disk): void
    {
        parent::removeFile($path, $disk);

        if ($disk === 'public') {
            PublicStorageMirror::deleteFile($path);
        }
    }
}
