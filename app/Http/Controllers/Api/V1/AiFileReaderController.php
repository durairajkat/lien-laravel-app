<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessAiDocument;
use App\Services\Project\AiDocumentProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use Smalot\PdfParser\Parser;



class AiFileReaderController extends Controller
{
    public function readDocument(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,docx'
        ]);

        $path = $request->file('document')->store('ai-documents');
        $fullPath = storage_path('app/' . $path);

        // Extract text (same as before)
        $extension = $request->file('document')->getClientOriginalExtension();

        $documentText = match ($extension) {
            'docx' => $this->extractDocxText($fullPath),
            'pdf'  => $this->extractPdfText($fullPath),
            default => ''
        };

        if (empty(trim($documentText))) {
            return response()->json([
                'success' => false,
                'message' => 'No readable text found'
            ], 422);
        }

        // Decide processing strategy
        if (strlen($documentText) > 50000) {
            //LARGE → BACKGROUND JOB
            ProcessAiDocument::dispatch($path, $documentText);

            return response()->json([
                'success' => true,
                'mode' => 'async',
                'message' => 'Document is large. Processing in background.',
                'fileId' => $path
            ]);
        }

        //SMALL → PROCESS IMMEDIATELY
        $result = app(AiDocumentProcessor::class)->process($documentText);

        Storage::delete($path);

        return response()->json([
            'success' => true,
            'mode' => 'sync',
            'data' => $result
        ]);
    }


    private function extractDocxText(string $filePath): string
    {
        $phpWord = IOFactory::load($filePath);
        $text = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $text .= $this->readElement($element);
            }
        }

        return trim($text);
    }

    /**
     * Recursive DOCX element reader
     */
    private function readElement($element): string
    {
        $content = '';

        if ($element instanceof Text) {
            return $element->getText() . "\n";
        }

        if ($element instanceof TextRun) {
            foreach ($element->getElements() as $child) {
                $content .= $this->readElement($child);
            }
            return $content . "\n";
        }

        if (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $child) {
                $content .= $this->readElement($child);
            }
        }

        return $content;
    }

    private function extractPdfText(string $filePath): string
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);

        return trim($pdf->getText());
    }

    public function getResult(string $fileId)
    {
        $resultPath = "ai-results/{$fileId}.json";

        if (!Storage::exists($resultPath)) {
            return response()->json([
                'status' => 'processing'
            ]);
        }

        return response()->json([
            'status' => 'done',
            'data' => json_decode(Storage::get($resultPath), true)
        ]);
    }
}
