<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log; // Import the Log facade
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $convertedFileName = 'converted';

                // Save the uploaded PDF file temporarily
                $pdfPath = $file->storeAs('temp', $convertedFileName . '.pdf');

                // Convert PDF to Word
                $wordContent = shell_exec('libreoffice --convert-to docx --outdir ' . storage_path('app/temp') . ' ' . storage_path('app/' . $pdfPath));

                // Save the converted Word document
                $wordFilePath = storage_path('app/temp/' . $convertedFileName . '.docx');
                file_put_contents($wordFilePath, $wordContent);

                // Return the converted Word document for download
                return Response::download($wordFilePath, $convertedFileName . '.docx')->deleteFileAfterSend(true);
            }

            return response()->json(['error' => 'No file uploaded'], 400);
        } catch (\Exception $e) {
            // Log the error
            Log::error('An error occurred while processing the file: ' . $e->getMessage());

            return response()->json(['error' => 'An error occurred while processing the file'], 500);
        }
    }
}
