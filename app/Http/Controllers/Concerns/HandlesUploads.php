<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HandlesUploads
{
    protected function storeUploadedFile(Request $request, string $field, string $directory): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        return $request->file($field)->store($directory, 'public');
    }

    protected function replaceUploadedFile(Request $request, string $field, string $directory, ?string $oldPath): ?string
    {
        $path = $this->storeUploadedFile($request, $field, $directory);

        if ($path && $oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return $path;
    }

    protected function deleteUploadedFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
