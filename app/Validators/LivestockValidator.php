<?php

namespace App\Validators;

use App\Models\Livestock;
use Illuminate\Support\Facades\Validator as FacadeValidator;

use Illuminate\Validation\ValidationException;
use App\Rules\Livestock\ValidParentAnimalCategory;
use App\Rules\Livestock\ParentOlderThanChild;
use App\Rules\Livestock\BirthDatePrecedesEntry;
use App\Rules\Livestock\MaleCannotHaveTits;

class LivestockValidator extends Validator
{
    /**
     * Valida la integridad de negocio de un modelo Livestock.
     *
     * @throws ValidationException
     */
    public function validate(Livestock $livestock): void
    {
        $data = $livestock->toArray();

        $rules = [
            'birth_date' => [new BirthDatePrecedesEntry($livestock->entry_date)],

            'tits' => [new MaleCannotHaveTits()],

            'father_id' => [
                'nullable',
                new ValidParentAnimalCategory('father'),
                new ParentOlderThanChild($livestock->birth_date)
            ],

            'mother_id' => [
                'nullable',
                new ValidParentAnimalCategory('mother'),
                new ParentOlderThanChild($livestock->birth_date)
            ],

            'brand_number' => ['required', 'string'],
        ];

        $validator = FacadeValidator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
