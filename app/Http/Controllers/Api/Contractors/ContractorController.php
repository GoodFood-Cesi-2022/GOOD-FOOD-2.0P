<?php

namespace App\Http\Controllers\Api\Contractors;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contractors\AddContractorTimesRequest;
use App\Http\Requests\Contractors\AddRecipesRequest;
use App\Http\Resources\ContractorResource;
use App\Http\Requests\Contractors\CreateContractorRequest;
use App\Http\Requests\Contractors\UpdateContractorRequest;
use App\Http\Requests\Contractors\UpdateRecipeRequest;
use App\Http\Resources\ContractorCollection;
use App\Http\Resources\ContractorRecipeCollection;
use App\Http\Resources\ContractorTimeResource;
use App\ModelFilters\ContractorFilter;
use App\ModelFilters\RecipeFilter;
use App\Models\Contractor;
use App\Models\Email;
use App\Models\Recipe;

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
        $contractor->ownedBy()->associate($request->owned_by);

        $contractor->save();

        return new ContractorResource($contractor);

    }


    /**
     * Ajouter des recettes au franchisé
     *
     * @param AddRecipesRequest $request
     * @param Contractor $contractor
     * @return \Illuminate\Http\JsonResponse
     */
    public function addRecipes(AddRecipesRequest $request, Contractor $contractor) : \Illuminate\Http\JsonResponse {

        $data = collect([]);

        foreach($request->recipes as $request_recipe) {
            $data->put($request_recipe['recipe_id'], ['price' => $request_recipe['price']]);
        }

        $contractor->recipes()->attach($data);

        return (new ContractorRecipeCollection($contractor->recipes()->whereIn('recipe_id', $data->keys())->get()))->response()->setStatusCode(201);

    }

    /**
     * Récupérer l'ensemble des recettes du franchisé
     *
     * @param Request $request
     * @param Contractor $contractor
     * @return ContractorRecipeCollection
     */
    public function getRecipes(Request $request, Contractor $contractor) : ContractorRecipeCollection {

        $this->authorize('view-recipes', $contractor);

        $contractor_recipes = $contractor->recipes()->filter($request->all(), RecipeFilter::class)->paginate(10);

        return new ContractorRecipeCollection($contractor_recipes);

    }


    /**
     * Retourne tous les franchisés de la plateforme
     *
     * @param Request $request
     * @return ContractorCollection
     */
    public function all(Request $request) : ContractorCollection {

        $this->authorize('all', Contractor::class);

        $contractors = Contractor::filter($request->all(), ContractorFilter::class)->paginate(15);

        return new ContractorCollection($contractors);

    }

    /**
     * Ajoute les horaires d'ouvertures à la franchise
     *
     * @param AddContractorTimesRequest $request
     * @return ContractorTimeResource
     */
    public function addTimes(AddContractorTimesRequest $request, Contractor $contractor) : \Illuminate\Http\JsonResponse {

        $contractor->load('times');

        $times = $this->convertRequestTimesToModelTimes($request->all());
        
        if(!$contractor->times) {
            $contractor->times()->create($times);
        } else {
            abort(400);
        }

        return (new ContractorTimeResource($contractor->times()->first()))->response()->setStatusCode(201);

    }

    /**
     * Met à jour horaires de la franchise
     *
     * @param AddContractorTimesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updateTimes(AddContractorTimesRequest $request, Contractor $contractor) : \Illuminate\Http\Response {

        $contractor->load('times');

        $times = $this->convertRequestTimesToModelTimes($request->all());

        if($contractor->times) {
            $contractor->times()->update($times);
        } else {
            abort(400);
        }

        return response('', 204);

    }


    /**
     * Retourne les horaires d'ouvertures 
     *
     * @param Request $request
     * @return ContractorTimeResource
     */
    public function getTimes(Request $request, Contractor $contractor) : ContractorTimeResource {

        $contractor->load('times');

        $this->authorize('view-times', $contractor);

        if($contractor->times){
            return new ContractorTimeResource($contractor->times);
        }

        return abort(404);

    }

    /**
     * Mise à jour du prix de la recette du franchisé
     *
     * @param UpdateRecipeRequest $request
     * @param Contractor $contractor
     * @param Recipe $recipe
     * @return \Illuminate\Http\Response
     */
    public function updateRecipe(UpdateRecipeRequest $request, Contractor $contractor, Recipe $recipe) : \Illuminate\Http\Response {

        $contractor->recipes()->updateExistingPivot($recipe->id, [
            'price' => $request->price
        ]);

        return response('', 204);

    }

    /**
     * Supprime une recette du catalogue du franchisé
     *
     * @param Request $request
     * @param Contractor $contractor
     * @param Recipe $recipe
     * @return \Illuminate\Http\Response
     */
    public function deleteRecipe(Request $request, Contractor $contractor, Recipe $recipe) : \Illuminate\Http\Response {

        $this->authorize('delete-recipe', [$contractor, $recipe]);

        $contractor->recipes()->detach($recipe->id);

        return response('', 204);

    }

    /**
     * Met à jour les informations de la franchise
     *
     * @param UpdateContractorRequest $request
     * @param Contractor $contractor
     * @return Illuminate\Http\Response
     */
    public function update(UpdateContractorRequest $request, Contractor $contractor) : \Illuminate\Http\Response {

        $email = Email::firstOrCreate([
            'email' => $request->email
        ]);

        $contractor->name = $request->name;
        $contractor->phone = $request->phone;
        $contractor->timezone = 'FR';
        $contractor->max_delivery_radius = $request->max_delivery_radius;

        $contractor->email()->associate($email);
        $contractor->address()->associate($request->address_id);
        $contractor->ownedBy()->associate($request->owned_by);

        $contractor->save();

        return response('', 204);

    }

    /**
     * Supprime une franchise
     *
     * @param Request $request
     * @param Contractor $contractor
     * @return Illuminate\Http\Response
     */
    public function delete(Request $request, Contractor $contractor) : \Illuminate\Http\Response {
        
        $this->authorize('delete', $contractor);

        $contractor->delete();

        return response('', 204);

    }



    /**
     * Convertit les données de la requête en données pour le model
     *
     * @param array $request_times
     * @return array
     */
    private function convertRequestTimesToModelTimes(array $request_times) : array {

        return get_contractor_service_days()->mapWithKeys(function($tojoin) use ($request_times) {
            $tojoin = collect($tojoin);
            return [$tojoin->join('_') => data_get($request_times, $tojoin->join('.'))];
        })->toArray();

    }




}
