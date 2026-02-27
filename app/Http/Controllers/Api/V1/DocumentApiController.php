<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Project\ProjectDocumentDetailCollectionResource;
use App\Http\Resources\ProjectDocumentResource;
use App\Models\ProjectDetail;
use App\Models\ProjectDocument;
use App\Services\Project\ProjectDocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class DocumentApiController extends Controller
{
    public function __construct(
        protected ProjectDocumentService $projectDocumentService
    ) {}

    public function index(Request $request)
    {
        $projectID = $request->query('project_id');
        $search = $request->query('search');

        $projects = ProjectDetail::query()
            ->whereHas('documents')
            ->when($projectID, function ($query) use ($projectID) {
                $query->where('id', $projectID);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('project_name', 'like', "%{$search}%");
            })
            ->with(['documents'])
            ->latest()
            ->get();

        return  ProjectDocumentDetailCollectionResource::collection($projects);
    }

    public function deleteDocument(Request $request)
    {
        $document = ProjectDocument::find($request->documentId);
        $projectId = $document?->project_id;

         if (!$projectId) {
            return response()->json([
                'message' => 'Project not found for the document'
            ], 404);
        }
        if (!$document) {
            return response()->json([
                'message' => 'Document not found'
            ], 404);
        }

        if ($document->filename) {
            Storage::disk('public')->delete($document->filename);
        }

        $document->delete();

        $documenta = ProjectDocument::where('project_id', $projectId)->get();

        return response()->json([
            'message' => 'Document deleted successfully',
            'status' => true,
            'data' => ProjectDocumentResource::collection($documenta)
        ], 200);
    }

    public function addDocument(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:project_details,id',
            'documents.*' => 'required|file|max:10240'
        ]);
        info('Received document upload request for project ID: ' . $request->project_id);   

        $uploaded = $this->projectDocumentService->storeDocument($request, $request->project_id);

        return response()->json([
            'message' => 'Documents uploaded successfully',
            'data' => $uploaded,
            "status" => true
        ], 201);
    }
}
