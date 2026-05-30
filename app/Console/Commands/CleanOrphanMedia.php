<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CleanOrphanMedia extends Command
{
    protected $signature = 'media:clean-orphans';
    protected $description = 'Delete media records where the parent model no longer exists';

    public function handle(): void
    {
        $deleted = 0;

        Media::all()->each(function (Media $media) use (&$deleted) {
            $modelClass = $media->model_type;
            $modelId = $media->model_id;

            if (!class_exists($modelClass)) {
                $media->delete();
                $deleted++;
                return;
            }

            if (!$modelClass::find($modelId)) {
                $media->delete();
                $deleted++;
            }
        });

        $this->info("Deleted {$deleted} orphan media records.");
    }
}
