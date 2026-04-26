<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Support\MediaLibrary\PublicStorageMirror;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait ResolvesMediaUrls
{
    public function getMediaDisplayUrl(string $collectionName, string $preferredConversion = 'webp'): string
    {
        $media = $this->getFirstMedia($collectionName);

        if (! $media instanceof Media) {
            return '';
        }

        if ($media->disk !== 'public') {
            return $preferredConversion !== '' && $media->hasGeneratedConversion($preferredConversion)
                ? $media->getUrl($preferredConversion)
                : $media->getUrl();
        }

        $relativePath = $preferredConversion !== '' && $media->hasGeneratedConversion($preferredConversion)
            ? $media->getPathRelativeToRoot($preferredConversion)
            : $media->getPathRelativeToRoot();

        PublicStorageMirror::ensureFile($relativePath);

        $encodedPath = collect(explode('/', str_replace('\\', '/', ltrim($relativePath, '/'))))
            ->filter(static fn (string $segment): bool => $segment !== '')
            ->map(static fn (string $segment): string => rawurlencode($segment))
            ->implode('/');

        return $encodedPath === ''
            ? ''
            : url('storage/'.$encodedPath);
    }
}
