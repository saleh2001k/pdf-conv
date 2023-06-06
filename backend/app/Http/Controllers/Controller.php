<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use PDF; // Example PDF to Word conversion library

class ConversionController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('file');
        // Perform any necessary validation on the file

        // Convert the PDF to Word
        $convertedFilePath = 'converted/' . $file->getClientOriginalName() . '.docx';
        \Barryvdh\DomPDF\Facade\Pdf::load($file)->save(storage_path('app/' . $convertedFilePath));

        // Return the file path of the converted Word document
        return response()->json(['filePath' => $convertedFilePath]);
    }

    public function download($filename)
    {
        $filePath = storage_path('app/converted/' . $filename . '.docx');

        // Check if the file exists
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Set appropriate headers for file download
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename=' . $filename . '.docx',
        ];

        // Stream the file as the response
        return response()->file($filePath, $headers);
    }
}
