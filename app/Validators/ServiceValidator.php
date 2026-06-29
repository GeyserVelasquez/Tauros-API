<?php

namespace App\Validators;

use App\Models\Service;
use App\Models\Livestock;
use App\Models\SemenBatch;
use App\Models\EmbrionBatch;
use Illuminate\Support\Facades\Validator as FacadeValidator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Relations\Relation;

class ServiceValidator extends Validator
{
    /**
     * Mapeo de tipos de parentales permitidos.
     */
    protected array $parentableMap = [
        Livestock::class,
        SemenBatch::class,
        EmbrionBatch::class,
    ];

    /**
     * Valida la integridad de negocio de un modelo Service.
     *
     * @throws ValidationException
     */
    public function validate(Service $service): void
    {
        $data = $service->toArray();

        $rules = [
            'female_id' => ['required', 'exists:livestock,id'],
            'parentable_type' => ['required', 'string'],
            'parentable_id' => ['required', 'integer'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
            'service_type_id' => ['required', 'exists:service_types,id'],
            'made_at' => ['required', 'date', 'before_or_equal:today'],
        ];

        $validator = FacadeValidator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $this->validateBiologicalRules($service);
    }

    /**
     * Orquestador de reglas biológicas y de integridad polimórfica.
     */
    private function validateBiologicalRules(Service $service): void
    {
        $this->validateParentableType($service);
        $this->validateParentableExists($service);
        $this->validateFemaleGender($service);
        $this->validateMaleGenderIfLivestock($service);
    }

    private function validateParentableType(Service $service): void
    {
        $actualClass = Relation::getMorphedModel($service->parentable_type) ?? $service->parentable_type;

        if (!in_array($actualClass, $this->parentableMap)) {
            throw ValidationException::withMessages([
                'parentable_type' => ['El tipo de parental no es válido para un servicio.'],
            ]);
        }
    }

    private function validateParentableExists(Service $service): void
    {
        $modelClass = Relation::getMorphedModel($service->parentable_type) ?? $service->parentable_type;

        if (!class_exists($modelClass) || !$modelClass::where('id', $service->parentable_id)->exists()) {
            throw ValidationException::withMessages([
                'parentable_id' => ['El parental seleccionado no existe.'],
            ]);
        }
    }

    private function validateFemaleGender(Service $service): void
    {
        $female = $service->female;

        if ($female && !$female->animal_category->isFemale()) {
            throw ValidationException::withMessages([
                'female_id' => ['La hembra debe ser de categoría femenina.'],
            ]);
        }
    }

    private function validateMaleGenderIfLivestock(Service $service): void
    {
        // Solo validamos género si el parental es un animal vivo (Livestock)
        if ($service->parentable_type === Livestock::class) {
            $male = $service->parentable;

            if ($male && !$male->animal_category->isMale()) {
                throw ValidationException::withMessages([
                    'parentable_id' => ['El semental debe ser de categoría masculina.'],
                ]);
            }
        }
    }
}
