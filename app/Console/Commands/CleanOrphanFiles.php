<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanOrphanFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-orphan-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean physical files that no longer have records in the database (profile-photos and spatie-media)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting orphan file cleanup...');

        $deletedProfilePhotos = $this->cleanProfilePhotos();
        $deletedMediaFolders = $this->cleanMediaLibrary();

        $this->newLine();
        $this->info("Cleanup completed!");
        $this->info("- Deleted Profile Photos: $deletedProfilePhotos");
        $this->info("- Deleted Media Folders: $deletedMediaFolders");

        return self::SUCCESS;
    }

    /**
     * Clean orphan profile photos.
     */
    protected function cleanProfilePhotos(): int
    {
        $this->comment('Scanning profile-photos folder...');
        
        $disk = 'public';
        $folder = 'profile-photos';
        
        if (!Storage::disk($disk)->exists($folder)) {
            $this->warn("Folder $folder does not exist on disk $disk.");
            return 0;
        }

        $allFiles = Storage::disk($disk)->files($folder);
        $dbPaths = \App\Models\User::whereNotNull('profile_photo_path')->pluck('profile_photo_path')->toArray();
        
        $deletedCount = 0;
        foreach ($allFiles as $file) {
            // Avoid deleting .gitignore or other system files if any
            if (basename($file) === '.gitignore') continue;

            if (!in_array($file, $dbPaths)) {
                Storage::disk($disk)->delete($file);
                $deletedCount++;
                if ($this->getOutput()->isVerbose()) {
                    $this->line("Deleted orphan photo: $file");
                }
            }
        }

        return $deletedCount;
    }

    /**
     * Clean orphan Spatie media library folders.
     */
    protected function cleanMediaLibrary(): int
    {
        $this->comment('Scanning media folder...');
        
        $mediaPath = storage_path('app/public/media');
        
        if (!File::exists($mediaPath)) {
            $this->warn("Folder $mediaPath does not exist.");
            return 0;
        }

        $directories = File::directories($mediaPath);
        
        // Ensure media table exists before querying
        if (!Schema::hasTable('media')) {
            $this->error('Table "media" does not exist. Skipping media cleanup.');
            return 0;
        }

        $dbMediaIds = DB::table('media')->pluck('id')->toArray();
        
        $deletedCount = 0;
        foreach ($directories as $dir) {
            $folderName = basename($dir);
            
            // Spatie media library uses numeric folder names for IDs
            if (is_numeric($folderName)) {
                if (!in_array((int)$folderName, $dbMediaIds)) {
                    File::deleteDirectory($dir);
                    $deletedCount++;
                    if ($this->getOutput()->isVerbose()) {
                        $this->line("Deleted orphan media folder ID: $folderName");
                    }
                }
            }
        }

        return $deletedCount;
    }
}
