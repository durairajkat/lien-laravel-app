<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;


class ProjectDocumentResource extends JsonResource
{

    public function toArray($request)
    {

        $fileSizeBytes = $this->filename && Storage::disk('public')->exists($this->filename)
            ? Storage::disk('public')->size($this->filename)
            : 0;

        return [
            'id' => $this->id,

            'title' => $this->title,
            'notes' => $this->notes,
            'date' => $this->date,

            'filename' => $this->filename,

            'file_url' => $this->filename
                ? Storage::disk('public')->url($this->filename)
                : null,

            'file_name' => basename($this->filename),
            'file_size_bytes' => $fileSizeBytes,
            'file_size' => $this->formatFileSize($fileSizeBytes),

            'file_extension' => pathinfo($this->filename, PATHINFO_EXTENSION),
        ];
    }

    private function formatFileSize($bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }
}
