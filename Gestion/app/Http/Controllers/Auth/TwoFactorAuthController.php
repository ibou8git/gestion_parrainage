<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;

class TwoFactorLoginController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string',
            'recovery_code' => 'nullable|string',
        ]);

        $user = Auth::user();

        if ($request->code) {
            // Vérification avec Fortify
            $verified = app(ConfirmTwoFactorAuthentication::class)($user, $request->code);

            if (!$verified) {
                throw ValidationException::withMessages([
                    'code' => __('The provided authentication code is invalid.'),
                ]);
            }
        } elseif ($request->recovery_code) {
            // Vérifier si le code de récupération est valide
            $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

            if (!in_array($request->recovery_code, $recoveryCodes)) {
                throw ValidationException::withMessages([
                    'recovery_code' => __('The provided recovery code is invalid.'),
                ]);
            }

            // Supprimer le code utilisé
            $user->two_factor_recovery_codes = encrypt(array_values(array_diff($recoveryCodes, [$request->recovery_code])));
            $user->save();
        } else {
            throw ValidationException::withMessages([
                'code' => __('Please provide an authentication or recovery code.'),
            ]);
        }

        return redirect()->intended('/dashboard'); // Redirection après succès
    }
}
