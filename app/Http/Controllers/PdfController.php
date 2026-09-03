<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CompanySetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class PdfController extends Controller
{
    public function download(): Response
    {
        $categories = Category::with(['products' => function ($q) {
            $q->where('is_active', true)->orderBy('name');
        }])
            ->whereHas('products', function ($q) {
                $q->where('is_active', true);
            })
            ->orderBy('name')
            ->get();

        $company = CompanySetting::getSettings();

        // Convert all product images to safe base64 format for Dompdf (handles WebP, external URLs, local files)
        foreach ($categories as $cat) {
            foreach ($cat->products as $prod) {
                $prod->pdf_image_base64 = $this->getImageBase64($prod->image_url ?: $prod->image);
            }
        }

        // Safe logo conversion
        $companyLogoBase64 = null;
        if (! empty($company->logo)) {
            $companyLogoBase64 = $this->getImageBase64($company->logo);
        }

        $pdf = Pdf::loadView('pdf.catalog', compact('categories', 'company', 'companyLogoBase64'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);

        return $pdf->download('Catalogo-de-Productos.pdf');
    }

    /**
     * Convert an image path or URL into a safe base64 JPEG/PNG string for Dompdf.
     */
    private function getImageBase64(?string $src): ?string
    {
        if (empty($src)) {
            return null;
        }

        try {
            $rawContent = null;
            $mime = 'image/jpeg';

            if (filter_var($src, FILTER_VALIDATE_URL)) {
                $response = Http::timeout(3)->get($src);
                if ($response->successful()) {
                    $rawContent = $response->body();
                    $mime = $response->header('Content-Type') ?: 'image/jpeg';
                }
            } else {
                $localPath = public_path('storage/'.$src);
                if (file_exists($localPath)) {
                    $rawContent = file_get_contents($localPath);
                    $mime = mime_content_type($localPath) ?: 'image/jpeg';
                }
            }

            if (! $rawContent) {
                return null;
            }

            // If WebP image format, convert to JPEG via GD if supported, or skip if unsupported
            if (str_contains(strtolower($mime), 'webp') || str_starts_with($rawContent, 'RIFF')) {
                if (function_exists('imagecreatefromstring')) {
                    $img = @imagecreatefromstring($rawContent);
                    if ($img !== false) {
                        ob_start();
                        imagejpeg($img, null, 85);
                        $rawContent = ob_get_clean();
                        imagedestroy($img);
                        $mime = 'image/jpeg';
                    } else {
                        return null;
                    }
                } else {
                    return null;
                }
            }

            // Ensure MIME is a Dompdf-supported format
            if (! in_array($mime, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'])) {
                if (function_exists('imagecreatefromstring')) {
                    $img = @imagecreatefromstring($rawContent);
                    if ($img !== false) {
                        ob_start();
                        imagejpeg($img, null, 85);
                        $rawContent = ob_get_clean();
                        imagedestroy($img);
                        $mime = 'image/jpeg';
                    } else {
                        return null;
                    }
                } else {
                    return null;
                }
            }

            return 'data:'.$mime.';base64,'.base64_encode($rawContent);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
