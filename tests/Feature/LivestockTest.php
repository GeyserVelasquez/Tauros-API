<?php

namespace Tests\Feature;

use App\Models\Livestock;
use Tests\TestCase;

class LivestockTest extends TestCase
{

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_users_can_get_a_list_of_livestock(): void
    {

        $livestock = Livestock::factory(3)->create();

        $route = route('livestock.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');

        $response->assertJsonFragment([
            'brand_number' => $livestock->first()->brand_number,
            'electronic_code' => $livestock->first()->electronic_code,
        ]);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'brand_number',
                    'electronic_code',
                    'name',
                    'entry_date',
                    'birth_date',
                    'general_comment',
                    'tits',
                    'is_enabled',
                    'is_alive',
                    'entry_cause_id',
                    'state_id',
                    'animal_category',
                    'breed_id',
                    'color_id',
                    'classification_id',
                    'owner_id',
                    'technician_id',
                    'father_id',
                    'mother_id',
                    'adoptive_mother_id',
                    'receiving_mother_id',
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_livestock(): void
    {
        $livestock = Livestock::factory()->create();

        $route = route('livestock.show', $livestock);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'brand_number' => $livestock->brand_number,
            'electronic_code' => $livestock->electronic_code,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'brand_number',
                'electronic_code',
                'name',
                'entry_date',
                'birth_date',
                'general_comment',
                'tits',
                'is_enabled',
                'is_alive',
                'entry_cause_id',
                'state_id',
                'animal_category',
                'breed_id',
                'color_id',
                'classification_id',
                'owner_id',
                'technician_id',
                'father_id',
                'mother_id',
                'adoptive_mother_id',
                'receiving_mother_id',
            ]
        ]);

    }

    public function test_users_can_create_a_new_livestock(): void
    {
        $payload = Livestock::factory()->raw();

        $route = route('livestock.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('livestock', [
            'brand_number' => $payload['brand_number'],
        ]);
    }

    public function test_users_cannot_create_a_new_livestock_with_missing_parameters(): void
    {
        $payload = Livestock::factory()->raw();

        $payload['brand_number'] = null;

        $route = route('livestock.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('livestock', [
            'name' => $payload['name'],
        ]);
    }

    public function test_users_cannot_create_a_new_livestock_with_duplicated_brand_number(): void
    {
        $alredyStoredLivestock = Livestock::factory()->create();

        $payloadWithDuplicatedBrandNumber = Livestock::factory(['brand_number' => $alredyStoredLivestock->brand_number])->raw();

        $route = route('livestock.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payloadWithDuplicatedBrandNumber);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('livestock', [
            'name' => $payloadWithDuplicatedBrandNumber['name'],
        ]);
    }

    public function test_users_can_create_a_new_livestock_with_pedigree(): void
    {
        $pedigreeLevel = 3;
        $livestockCountInDatabase = (2**($pedigreeLevel+1)-1);

        $payload = Livestock::factory()->withPedigree($pedigreeLevel)->make()->toArray();

        $postRoute = route('livestock.store');

        $postResponse = $this->actingAs($this->user)
            ->postJson($postRoute, $payload);

        $postResponse->assertStatus(201);

        $this->assertDatabaseHas('livestock', [
            'brand_number' => $payload['brand_number'],
        ]);

        $getRoute = route('livestock.index');

        $getResponse = $this->actingAs($this->user)
            ->getJson($getRoute);

        $getResponse->assertStatus(200);

        $getResponse->assertJsonCount($livestockCountInDatabase, 'data');


    }

    public function test_users_can_update_a_livestock(): void
    {
        $livestock = Livestock::factory()->create();

        $payload = $livestock->make(['brand_number' => '0416'])->toArray();

        $route = route('livestock.update', $livestock);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('livestock', [
            'brand_number' => $payload['brand_number'],
        ]);
    }

    public function test_users_can_delete_a_livestock(): void
    {
        $livestock = Livestock::factory()->create();

        $route = route('livestock.destroy', $livestock);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($livestock);
    }

    public function test_users_cannot_create_a_new_male_livestock_with_tits(): void
    {
        $payload = Livestock::factory()->asBull()->raw();

        $payload['tits'] = '4';

        $route = route('livestock.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseMissing('livestock', [
            'brand_number' => $payload['brand_number'],
            'tits' => $payload['tits']
        ]);
    }

    public function test_unauthenticated_users_cannot_access_breeds_endpoints(): void
    {
        $livestock = Livestock::factory()->create();

        $routeIndex = route('livestock.index');
        $responseIndex = $this->getJson($routeIndex);
        $responseIndex->assertStatus(401);

        $routeShow = route('breeds.show', $livestock);
        $responseShow = $this->getJson($routeShow);
        $responseShow->assertStatus(401);

        $storePayload = [
            'brand_number' => '785-895',
            'name' => 'Juanito Alimaña'
        ];
        $routeStore = route('livestock.store');
        $responseStore = $this->postJson($routeStore, $storePayload);
        $responseStore->assertStatus(401);
        $this->assertDatabaseMissing('livestock', [
            'name' => 'Juanito Alimaña'
        ]);


        $updatePayload = [
            'name' => 'Juanito Paisano'
        ];
        $routeUpdate = route('livestock.update', $livestock);
        $responseUpdate = $this->putJson($routeUpdate, $updatePayload);
        $responseUpdate->assertStatus(401);

        $routeDestroy = route('livestock.destroy', $livestock);
        $responseDestroy = $this->deleteJson($routeDestroy);
        $responseDestroy->assertStatus(401);
        $this->assertDatabaseMissing('livestock', [
            'name' => 'Juanito Paisano'
        ]);
    }

    public function test_users_can_add_a_comment_to_a_livestock(): void
    {
        $livestock = Livestock::factory()->create();

        $payload = [
            'text' => 'La vaca mariposa tuvo un terné',
        ];

        $route = route('livestocks.store');

    }

}
