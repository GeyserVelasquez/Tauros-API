<?php

namespace App\Http\Controllers;

use App\Http\Requests\Certificate\StoreCertificateRequest;
use App\Http\Requests\Certificate\UpdateCertificateRequest;
use App\Http\Resources\CertificateResource;
use App\Models\Certificate;
use App\Services\CertificateService;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(Certificate::class, $request);

        $certificates = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return CertificateResource::collection($certificates);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCertificateRequest $request, CertificateService $service)
    {
        $data = $request->validated();

        // Si se subió un archivo físico, guardarlo en el disco público y guardar la ruta
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('certificates', 'public');
            $data['file_path'] = $path;
        }

        $certificate = Certificate::create($data);

        // Delegar la asignación (lote o individual) al servicio
        $service->assign($certificate, $data);

        return new CertificateResource($certificate);
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate, Request $request)
    {
        $certificate = $this->queryBuilderService->buildForModel($certificate, $request)->first();
        
        return new CertificateResource($certificate);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCertificateRequest $request, Certificate $certificate, CertificateService $service)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            // Eliminar el archivo físico anterior
            if ($certificate->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($certificate->file_path);
            }
            $path = $request->file('file')->store('certificates', 'public');
            $data['file_path'] = $path;
        }

        $certificate->update($data);

        // Si viene asignación, actualizamos la relación
        if (isset($data['assign_by'])) {
            $service->assign($certificate, $data);
        }

        return new CertificateResource($certificate);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return response(null, 204);
    }
}
