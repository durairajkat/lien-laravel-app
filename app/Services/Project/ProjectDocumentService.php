<?php

namespace App\Services\Project;

use App\Models\ProjectDocument;
use Illuminate\Support\Str;

class ProjectDocumentService
{
    public function storeDocument($request, $projectId)
    {
        $uploaded = [];
            ProjectDocument::where('project_id', $projectId)->delete(); // this is now used for create, if this used in edit need to modify this delete
            foreach ($request->file('documents') as $file) {

                //  Create folder name
                $folderName = 'project_' . $projectId;

                //  Generate unique file name
                $uniqueName = Str::uuid() . '.' . $file->getClientOriginalExtension();

                //  Store file inside project folder
                $path = $file->storeAs(
                    $folderName,
                    $uniqueName,
                    'public'
                );

                // Save in database
                $document = ProjectDocument::create([
                    'project_id' => $projectId,
                    'title' => $file->getClientOriginalName(), // original name
                    'notes' => $request->notes,
                    'date' => now()->toDateString(),
                    'filename' => $path, // project_89/uuid.pdf
                ]);

                $uploaded[] = $document;
            }
    }
}