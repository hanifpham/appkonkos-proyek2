<?php

declare(strict_types=1);

namespace App\Observers;

use Spatie\MediaLibrary\HasMedia;

class MediaCleanupObserver
{
    public function deleting(HasMedia $model): void
    {
        $model->media->each->delete();
    }
}
