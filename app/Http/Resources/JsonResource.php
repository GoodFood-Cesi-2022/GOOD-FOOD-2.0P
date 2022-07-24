<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

abstract class JsonResource extends BaseJsonResource
{

    /**
     * Retourne les permissions demandées dans la req
     *
     * @param Request $request
     * @return array
     */
    protected function appendAbilities(Request $request) : mixed {

        if($request->has('abilities')) {

            $permissions = $this->getAbilities($request->query('abilities', []));

            $request->offsetUnset('abilities');

            return $this->when(count($permissions) > 0, $permissions);

        }

        return $this->when(false, []);

    }

    /**
     * Calcul les permissions pour l'utilisateur courant 
     *
     * @param array $abilities
     * @return array
     */
    private function getAbilities(array $abilities = []) : array {

        return collect($abilities)->mapWithKeys(function($ability) {

            $permision = explode('.', $ability);
            $model = (count($permision) > 1) ? "App\\Models\\" . Str::studly($permision[0]) : $this->resource;
            $model_name = (count($permision) > 1) ? Str::studly($permision[0]) : (new \ReflectionClass($this->resource))->getShortName();
            $resource = last($permision);
            $policy = "App\\Policies\\" . $model_name . "Policy";

            $can = (new $policy)->$resource(auth()->user(), $model);

            return [ $ability =>  $can ];

        })->toArray();

    }

}