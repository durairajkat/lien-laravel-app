<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AiFileReaderController extends Controller
{
     public function readDocument(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png'
        ]);

        $file = $request->file('document');
        $base64 = base64_encode(file_get_contents($file->getRealPath()));

        $payload = [
            "model" => "gpt-4o",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "Extract structured data from the document"
                ],
                [
                    "role" => "user",
                    "content" => [
                        [
                            "type" => "input_text",
                            "text" => "Read this document and extract name, email, phone. Return ONLY valid JSON."
                        ],
                        [
                            "type" => "input_image",
                            "image_base64" => $base64
                        ]
                    ]
                ]
            ],
            "response_format" => [
                "type" => "json_object"
            ]
        ];

        $ch = curl_init("https://api.openai.com/v1/chat/completions");

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer " . env("OPENAI_API_KEY")
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);
        info($response);

        return response()->json([
            "data" => json_decode($response, true)
        ]);
    }
}
