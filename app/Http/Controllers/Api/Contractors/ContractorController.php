<?php

namespace App\Http\Controllers\Api\Contractors;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContractorResource;
use App\Http\Requests\Contractors\CreateContractorRequest;
use App\Models\Contractor;
use App\Models\Email;

class ContractorController extends Controller
{
    
    /**
     * Créer un franchisé
     *
     * @param CreateContractorRequest $request
     * @return ContractorResource
     */
    public function create(CreateContractorRequest $request) : ContractorResource {

        $email = Email::firstOrCreate([
            'email' => $request->email
        ]);

        $contractor = new Contractor([
            'name' => $request->name,
            'phone' => $request->phone,
            'timezone' => 'FR',
            'max_delivery_radius' => $request->max_delivery_radius
        ]);

        $contractor->email()->associate($email);
        $contractor->address()->associate($request->address_id);

        $contractor->save();

        return new ContractorResource($contractor);

    }




}
