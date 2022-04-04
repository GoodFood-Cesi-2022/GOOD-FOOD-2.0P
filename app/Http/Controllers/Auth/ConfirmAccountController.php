<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ConfirmAccountRequest;
use Hash;
use Illuminate\Http\RedirectResponse;

class ConfirmAccountController extends Controller {

    /**
     * Affiche la vue pour confirmer le compte
     *
     * @param string $token
     * @return View
     */
    public function view(string $token) : View {

        $user = User::whereConfirmableToken($token)->firstOrFail();

        return view('auth.confirm-account', compact('user', 'token'));

    }


    /**
     * Enregistre les paramètres du compte de l'utilisateur
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(ConfirmAccountRequest $request) : RedirectResponse {

        if(is_null($request->safe()->token)) {
            return abort(404);
        };

        $user = User::whereConfirmableToken($request->token)->firstOrFail();

        $user->password = Hash::make($request->safe()->password);

        $user->makeConfirmableTokenUsed()->markEmailAsVerified();

        // Redirection vers l'application GoodFood
        return redirect('/');

    }


}