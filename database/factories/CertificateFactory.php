<?php

namespace Database\Factories;

use App\Models\Certificate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends Factory<Certificate>
 */
class CertificateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Parsear el string de Faker a un objeto Carbon
        $issue_date = Carbon::parse($this->faker->date());

        // Usar copy() para evitar modificar $issue_date y sumar 10 meses
        $expire_date = $issue_date->copy()->addMonths(10);

        return [
            'certificate_number' => $this->faker->unique()->bothify('CERT-#####'),
            'issue_date' => $issue_date->format('Y-m-d'), // Formatear de vuelta a string para la BD
            'expiry_date' => $expire_date->format('Y-m-d'),
            'file_path' => $this->faker->filePath(),
        ];
    }
}
