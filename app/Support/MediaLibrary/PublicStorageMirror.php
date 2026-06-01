<?php

declare(strict_types=1);

namespace App\Support\MediaLibrary;

/**
 * No-op mirror — public/storage is a symlink to storage/app/public.
 * Files are already accessible without copying.
 */
class PublicStorageMirror
{
    public static function ensureFile(string $relativePath): void
    {
        // No-op: symlink handles file accessibility
    }

    public static function deleteFile(string $relativePath): void
    {
        // No-op: symlink handles file accessibility
    }

    public static function deleteDirectoryIfEmpty(string $relativeDirectory): void
    {
        // No-op: symlink handles file accessibility
    }

    public static function normalizeRelativePath(string $path): string
    {
        return trim(str_replace('\\', '/', ltrim($path, '/')), '/');
    }
}
