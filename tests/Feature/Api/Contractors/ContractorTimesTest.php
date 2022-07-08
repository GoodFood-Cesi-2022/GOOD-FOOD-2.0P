<?php

namespace Tests\Feature\Api\Contractors;

use App\Models\Contractor;
use App\Models\ContractorTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Api\ApiCase;

class ContractorTimesTest extends ApiCase
{


    const BASE_PATH = "/api/contractors/{contractor_id}/times";


    /**
     * Test l'ajout des horaires d'un franchisé 
     *
     * @group contractor
     * @group contractor-times
     * @return void
     */
    public function test_create_contractor_times() : void
    {
        $user = $this->actingAsContractor();
        
        $contractor = Contractor::factory()->create([
            'owned_by' => $user->id
        ]);

        $data = $this->getDataTimes();

        $response = $this->postJson($this->getBasePath($contractor), $data->toArray());

        $response->assertCreated()->assertJsonStructure([
            'monday' => [
                'lunch' => [
                    'opened_at',
                    'closed_at'
                ],
                'night' => [
                    'opened_at',
                    'closed_at'
                ]
            ],
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday'
        ]);

        $this->assertDatabaseHas('contractor_times', [
            'contractor_id' => $contractor->id
        ]);

        $this->assertDatabaseCount('contractor_times', 1);

    }

    /**
     * Test la mise à jour des horraires d'un franchisé
     * 
     * @group contractor
     * @group contractor-times
     * @return void
     */
    public function test_update_contractor_times() : void
    {
        $user = $this->actingAsContractor();

        $contractor = Contractor::factory()->create([
            'owned_by' => $user->id
        ]);

        $contractor_time = ContractorTime::factory()->make([
            'contractor_id' => $contractor->id
        ]);

        $contractor->times()->create($contractor_time->toArray());

        $data = $this->getDataTimes()->toArray();

        $response = $this->putJson($this->getBasePath($contractor), $data);

        $response->assertNoContent();

        $this->assertTrue($contractor->times()->count() === 1);

    }

    /**
     * Test pour retrouver les horaires d'un franchisé
     *
     * @group contractor
     * @group contractor-times
     * @return void
     */
    public function test_get_contractor_times() : void {

        $contractor = Contractor::factory()->create();

        $contractor_time = ContractorTime::factory()->make([
            'contractor_id' => $contractor->id
        ]);

        $contractor->times()->create($contractor_time->toArray());

        $this->actingAsClient();
        
        $response = $this->get($this->getBasePath($contractor));

        $response->assertOk();

    }

    /**
     * Test pour retrouver les horaires non paramétrés d'un franchisé
     *
     * @group contractor
     * @group contractor-times
     * @return void
     */
    public function test_retreive_contractor_times_not_set() {

        $contractor = Contractor::factory()->create();

        $this->actingAsClient();

        $response = $this->get($this->getBasePath($contractor));

        $response->assertNotFound();


    }



    /**
     * Retourne les données pour être envoyées pour les horaires
     *
     * @return \Illuminate\Support\Collection
     */
    private function getDataTimes() : \Illuminate\Support\Collection {

        return get_contractor_service_days_array(function($day, $service, $hour) {
            $time = null;
            switch($service) {
                case 'lunch';
                    switch($hour) {
                        case 'opened_at':
                            $time = '12:00';
                            break;
                        case 'closed_at':
                            $time = '14:00';
                            break;
                    }
                    break;
                case 'night';
                    switch($hour) {
                        case 'opened_at':
                            $time = '19:00';
                            break;
                        case 'closed_at':
                            $time = '22:00';
                            break;
                    }
                    break;
            }
            return $time;
        });

    }

    /**
     * Retourne le base path de l'API
     *
     * @param Contractor $contractor
     * @return string
     */
    private function getBasePath(Contractor $contractor) : string {

        return str_replace('{contractor_id}', $contractor->id, self::BASE_PATH);

    } 


}
