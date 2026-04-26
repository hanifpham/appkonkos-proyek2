<?php

declare(strict_types=1);

namespace App\Support\MediaLibrary;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PublicStorageMirror
{
    public static function ensureFile(string $relativePath): void
    {
        $normalizedPath = self::normalizeRelativePath($relativePath);

        if ($normalizedPath === '') {
            return;
        }

        $sourcePath = Storage::disk('public')->path($normalizedPath);
        $publicPath = public_path('storage/'.$normalizedPath);

        if (! File::exists($sourcePath)) {
            return;
        }

        File::ensureDirectoryExists(dirname($publicPath));

        if (! File::exists($publicPath) || File::size($publicPath) !== File::size($sourcePath)) {
            File::copy($sourcePath, $publicPath);
        }
    }

    public static function deleteFile(string $relativePath): void
    {
        $normalizedPath = self::normalizeRelativePath($relativePath);

        if ($normalizedPath === '') {
            return;
        }

        File::delete(public_path('storage/'.$normalizedPath));
        self::deleteDirectoryIfEmpty(dirname($normalizedPath));
    }

    public static function deleteDirectoryIfEmpty(string $relativeDirectory): void
    {
        $normalizedDirectory = trim(str_replace('\\', '/', $relativeDirectory), '/');
        $rootPath = public_path('storage');

        if ($normalizedDirectory === '' || ! File::exists($rootPath)) {
            return;
        }

        $currentPath = $rootPath.'/'.$normalizedDirectory;

        while (str_starts_with($currentPath, $rootPath) && $currentPath !== $rootPath) {
            if (! File::isDirectory($currentPath)) {
                $currentPath = dirname($currentPath);

                continue;
            }

            $entries = array_values(array_diff(scandir($currentPath) ?: [], ['.', '..']));

            if ($entries !== []) {
                break;
            }

            File::deleteDirectory($currentPath);
            $currentPath = dirname($currentPath);
        }
    }

    public static function normalizeRelativePath(string $path): string
    {
        return trim(str_replace('\\', '/', ltrim($path, '/')), '/');
    }
}
