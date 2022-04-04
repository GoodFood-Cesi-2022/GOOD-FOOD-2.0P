<?php
namespace App\Http\Controllers\Api\Users;

use App\Models\User;
use App\Models\Email;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\UserCollection;
use App\Mail\Users\ConfirmAccountMail;
use App\Http\Requests\Users\CreateRequest;
use Exception;

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
     * Retourne la liste des utilisateurs de la plateforme
     *
     * @return \App\Http\Resources\UserCollection
     */
    public function getAllUsers() : UserCollection {

        return new UserCollection(User::paginate(50));

    }


    /**
     * Créer un nouvelle utilisateur dans l'application
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