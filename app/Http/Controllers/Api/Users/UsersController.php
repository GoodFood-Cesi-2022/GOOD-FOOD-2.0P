<?php
namespace App\Http\Controllers\Api\Users;

use Exception;
use App\Enums\Roles;
use App\Models\Role;
use App\Models\User;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\UserCollection;
use App\Mail\Users\ConfirmAccountMail;
use App\Http\Requests\Users\CreateRequest;

class UsersController extends Controller {


    /**
     * Retourne les informations de l'utilisateur courant
     *
     * @return \App\Http\Resources\UserResource
     */
    public function getCurrentUser() : UserResource {

        return new UserResource(auth()->user);

    }

    /**
     * Retourne l'utilisateur demandé
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\UserResource
     */
    public function getUser(Request $request) : UserResource {

        $this->authorize('view', $request->user_id);

        return new UserResource($request->user_id);

    }


    /**
     * Retourne la liste des utilisateurs de la plateforme
     *
     * @return \App\Http\Resources\UserCollection
     */
    public function getAllUsers() : UserCollection {

        $this->authorize('view-any', User::class);

        return new UserCollection(User::paginate(50));

    }


    /**
     * Créer un nouvelle utilisateur dans l'application
     * Création d'un utilisateur interne par défault il a le rôle
     * de contractant (franchisé)
     *
     * @param \App\Http\Requests\Users\CreateRequest $request
     * @return \App\Http\Resources\UserResource
     */
    public function createUser(CreateRequest $request) : UserResource {

        DB::beginTransaction();

        try {

            $email = Email::create($request->safe(['email']));

            $user = new User([
                'firstname' => $request->safe()->firstname,
                'lastname' => $request->safe()->lastname,
                'phone' => $request->phone
            ]);

            $user->emailLogin()->associate($email);
    
            $user->confirmable_token = User::generateConfirmableToken();
    
            $user->save();

            $user->roles()->attach(Role::whereCode(Roles::contractor->value)->first()->id);

            DB::commit();


        }catch(Exception $e) {

            DB::rollBack();
            throw $e;
            
        }

        // Envoi Email pour confirmer email avec lien pour renseigner mdp.
        Mail::to($user)->send(new ConfirmAccountMail($user));

        return new UserResource($user);

    }


}