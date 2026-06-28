<?php

namespace App\Validators;

use App\Models\MovementKardex;
use App\Models\Supply;
use App\Models\Product;
use App\Models\EmbrionBatch;
use App\Models\SemenBatch;
use App\Models\Livestock;
use App\Models\ClinicHistory;
use App\Models\SupplyMovement;
use App\Models\Outcome;
use App\Models\ProductMovement;
use App\Models\Extraction;
use App\Models\Service;
use App\Models\Birth;
use App\Enums\MovementType;
use Illuminate\Support\Facades\Validator as FacadeValidator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class MovementKardexValidator extends Validator
{
    protected array $itemMap = [
        Supply::class,
        Product::class,
        EmbrionBatch::class,
        SemenBatch::class,
        Livestock::class,
    ];

    protected array $eventMap = [
        ClinicHistory::class,
        SupplyMovement::class,
        Outcome::class,
        ProductMovement::class,
        Extraction::class,
        Service::class,
        Birth::class,
    ];

    /**
     * Valida la integridad de negocio de un modelo MovementKardex.
     *
     * @throws ValidationException
     */
    public function validate(MovementKardex $movementKardex): void
    {
        $data = $movementKardex->toArray();

        $rules = [
            'item_type' => ['required', 'string', Rule::in($this->itemMap)],
            'item_id' => ['required', 'integer'],
            'event_type' => ['nullable', 'string', Rule::in($this->eventMap)],
            'event_id' => ['nullable', 'integer'],
            'type' => ['required', Rule::enum(MovementType::class)],
            'quantity' => ['required', 'integer', 'min:1'],
            'date' => ['required', 'date', 'before_or_equal:today'],
        ];

        $validator = FacadeValidator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $this->validateMorphExists($movementKardex, 'item');
        
        if ($movementKardex->event_type) {
            $this->validateMorphExists($movementKardex, 'event');
        }
    }

    private function validateMorphExists(MovementKardex $movementKardex, string $relation): void
    {
        $typeField = "{$relation}_type";
        $idField = "{$relation}_id";

        $modelClass = $movementKardex->$typeField;
        
        if (!class_exists($modelClass) || !$modelClass::where('id', $movementKardex->$idField)->exists()) {
            throw ValidationException::withMessages([
                $idField => ["El {$relation} seleccionado no existe."],
            ]);
        }
    }
}
