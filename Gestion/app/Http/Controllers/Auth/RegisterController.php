<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Electeur; // Correction ici
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * Affiche le formulaire d'inscription.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Gère l'inscription de l'utilisateur.
     */
    public function inscription(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Vérifier si l'utilisateur est un électeur enregistré
        $electeur = Electeur::where('email', $request->email)
                            ->orWhere('phone', $request->phone)
                            ->first();

        if (!$electeur) {
            return redirect()->back()->withErrors([
                'email' => 'Votre email ou numéro de téléphone n’est pas enregistré comme électeur.'
            ])->withInput();
        }

        // Générer un code d'authentification à 6 chiffres
        $verificationCode = rand(100000, 999999);

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'verification_code' => $verificationCode,
        ]);

        // Envoi du code de vérification par email avec une vue Blade
        Mail::send('emails.verification', ['code' => $verificationCode], function ($message) use ($user) {
            $message->to($user->email)->subject('Vérification de votre compte');
        });

        // Envoi du code par SMS
        $smsSent = $this->sendSms($user->phone, "Votre code de vérification est : $verificationCode");

        // Message utilisateur en fonction de l'envoi
        if ($smsSent) {
            return redirect()->route('verification.form')->with('success', 'Un code de vérification vous a été envoyé par email et SMS.');
        } else {
            return redirect()->route('verification.form')->with('warning', 'Le code a été envoyé par email, mais pas par SMS.');
        }
    }

    /**
     * Envoi d'un SMS via Twilio.
     */
    private function sendSms($phone, $message)
    {
        try {
            $sid    = env("TWILIO_SID");
            $token  = env("TWILIO_TOKEN");
            $twilio = new Client($sid, $token);

            $twilio->messages->create($phone, [
                "from" => env("TWILIO_PHONE"),
                "body" => $message
            ]);

            return true; // Succès
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi du SMS : " . $e->getMessage());
            return false; // Échec
        }
    }
}
