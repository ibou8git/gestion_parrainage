<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TempElecteur;
use App\Models\ElecteursErreur;
use App\Models\HistoriqueUpload;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DGEController extends Controller
{
    /**
     * Afficher le formulaire d'importation.
     */
    public function importForm()
{
    if (!auth()->check()) {
        return redirect()->route('auth.login')->with('error', 'Vous devez être connecté.');
    }
    return view('dge.import');
}
    
    /**
     * Contrôle et stockage temporaire des électeurs.
     */
    public function ControlerFichierElecteurs(Request $request)
    {
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('auth.login')->with('error', 'Vous devez être connecté.');
        }
    
        // Vérifier si un autre upload est en cours
        $uploadEnCours = DB::table('historique_uploads')
            ->where('reussi', 0)  // 0 signifie que l'upload est en cours ou a échoué
            ->exists();
    
        if ($uploadEnCours) {
            return back()->withErrors(['upload_en_cours' => 'Un upload est déjà en cours.']);
        }
    
        // Valider les entrées
        $request->validate([
            'fichier' => 'required|mimes:csv,txt|max:2048',
            'checksum' => 'required|string|size:64'
        ]);
    
        // Lire le fichier et calculer son empreinte SHA256
        $path = $request->file('fichier')->store('imports');
        $fileContent = Storage::get($path);
        $computedChecksum = hash('sha256', $fileContent);
    
        // Vérifier si l'empreinte correspond
        if ($computedChecksum !== $request->checksum) {
            // Enregistrer l'échec de l'upload dans l'historique
            $uploadId = HistoriqueUpload::create([
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'cle_utilisee' => $request->checksum,
                'reussi' => false,
            ])->id;
    
            return back()->withErrors(['checksum' => 'L\'empreinte SHA256 ne correspond pas au fichier.']);
        }
    
        // Enregistrer le succès de l'upload dans l'historique
        $uploadId = HistoriqueUpload::create([
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'cle_utilisee' => $request->checksum,
            'reussi' => true,
        ])->id;
    
        // Stocker l'ID de l'upload dans la session
        session(['upload_id' => $uploadId]);
    
        // Passer l'ID de l'upload à la méthode ControlerElecteurs
        return $this->ControlerElecteurs($path, $uploadId);
    }
    /**
     * Contrôle des électeurs et stockage temporaire.
     */
    public function ControlerElecteurs($path, $uploadId)
{
    // Lire le fichier CSV
    $fileContent = Storage::get($path);
    $lines = explode("\n", $fileContent);

    // Supprimer la première ligne (en-tête) si nécessaire
    array_shift($lines);

    // Convertir chaque ligne en un tableau associatif
    $electeurs = [];
    foreach ($lines as $line) {
        $data = str_getcsv($line);
        if (count($data) > 0) {
            $electeurs[] = [
                'nom' => $data[0] ?? null,
                'prenom' => $data[1] ?? null,
                'carte_identite' => $data[2] ?? null,
                'num_electeur' => $data[3] ?? null,
                'email' => $data[4] ?? null,
                'telephone' => $data[5] ?? null,
                'bureau_vote' => $data[6] ?? null,
            ];
        }
    }

    // Vérifier si on a bien un tableau non vide
    if (empty($electeurs)) {
        return back()->withErrors(['error' => 'Aucun électeur trouvé dans le fichier CSV']);
    }

    // Valider chaque électeur
    foreach ($electeurs as $electeur) {
        // Vérifier si le numéro d'électeur existe déjà dans la table temporaire
        $exists = DB::table('temp_electeurs')
            ->where('num_electeur', $electeur['num_electeur'])
            ->exists();

        if ($exists) {
            // Enregistrer l'erreur dans la table des erreurs
            DB::table('electeurs_erreurs')->insert([
                'upload_id' => $uploadId,
                'carte_identite' => $electeur['carte_identite'] ?? 'Inconnu',
                'num_electeur' => $electeur['num_electeur'] ?? 'Inconnu',
                'erreur' => 'Numéro d\'électeur en double',
            ]);
            continue; // Passer à la ligne suivante
        }

        // Valider les autres champs
        $validator = Validator::make($electeur, [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'carte_identite' => 'required|string|size:12|unique:electeurs,carte_identite',
            'num_electeur' => 'required|string|size:10|unique:electeurs,num_electeur',
            'email' => 'required|email|unique:electeurs,email',
            'telephone' => 'required|string|unique:electeurs,telephone',
            'bureau_vote' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            DB::table('electeurs_erreurs')->insert([
                'upload_id' => $uploadId,
                'carte_identite' => $electeur['carte_identite'] ?? 'Inconnu',
                'num_electeur' => $electeur['num_electeur'] ?? 'Inconnu',
                'erreur' => json_encode($validator->errors()),
            ]);
            continue;
        }

        // Insérer dans la table temporaire
        DB::table('temp_electeurs')->insert($electeur);
    }

    // Retourner une réponse de succès
    return back()->with('success', 'Importation des électeurs terminée. Veuillez valider les données.');
}

public function validerImportation(Request $request)
{
    // Démarrer une transaction
    DB::beginTransaction();

    try {
        $uploadId = session('upload_id') ?? $request->upload_id;
        if (!$uploadId) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack(); // Annuler la transaction si l'upload_id est manquant
            }
            return back()->withErrors(['error' => 'Aucun upload_id trouvé.']);
        }

        // Vérification des électeurs dans la table temporaire
        $electeursDansTemp = DB::table('temp_electeurs')->count();
        if ($electeursDansTemp == 0) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack(); // Annuler la transaction si la table temporaire est vide
            }
            return back()->withErrors(['error' => 'La table temporaire est vide !']);
        }

        // Récupérer les électeurs valides
        $electeursValidés = DB::table('temp_electeurs')
            ->whereNotIn('num_electeur', function ($query) {
                $query->select('num_electeur')->from('electeurs_erreurs');
            })
            ->get();

        if ($electeursValidés->isEmpty()) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack(); // Annuler la transaction si aucun électeur valide n'est trouvé
            }
            return back()->withErrors(['error' => 'Aucun électeur valide trouvé !']);
        }

        // Transférer les électeurs validés vers la table `electeurs`
        foreach ($electeursValidés as $electeur) {
            try {
                // Vérifier si l'électeur existe déjà dans `electeurs`
                $exists = DB::table('electeurs')
                    ->where('num_electeur', $electeur->num_electeur)
                    ->orWhere('carte_identite', $electeur->carte_identite)
                    ->exists();

                if ($exists) {
                    // Enregistrer l'erreur dans la table des erreurs
                    DB::table('electeurs_erreurs')->insert([
                        'upload_id' => $uploadId,
                        'carte_identite' => $electeur->carte_identite,
                        'num_electeur' => $electeur->num_electeur,
                        'erreur' => 'Doublon détecté lors de l\'importation',
                    ]);
                    continue; // Passer à la ligne suivante
                }

                // Insérer l'électeur dans la table `electeurs`
                DB::table('electeurs')->insert([
                    'nom' => $electeur->nom,
                    'prenom' => $electeur->prenom,
                    'carte_identite' => $electeur->carte_identite,
                    'num_electeur' => $electeur->num_electeur,
                    'email' => $electeur->email,
                    'telephone' => $electeur->telephone,
                    'bureau_vote' => $electeur->bureau_vote,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Enregistrer les erreurs de doublon
                DB::table('electeurs_erreurs')->insert([
                    'upload_id' => $uploadId,
                    'carte_identite' => $electeur->carte_identite,
                    'num_electeur' => $electeur->num_electeur,
                    'erreur' => 'Doublon détecté lors de l\'importation',
                ]);
                throw $e; // Annuler la transaction en cas de doublon
            }
        }

        // Vider la table temporaire
        DB::table('temp_electeurs')->delete();

        // Mettre à jour l'état de l'upload dans `historique_uploads`
        DB::table('historique_uploads')
            ->where('id', $uploadId)
            ->update(['etat' => 'validé']);

        // Valider la transaction
        DB::commit();

        // Désactiver l'upload pour les futures tentatives
        DB::table('historique_uploads')
            ->where('etat', 'en_cours')
            ->update(['etat' => 'désactivé']);

        return back()->with('success', count($electeursValidés) . ' électeurs validés ont été transférés.');

    } catch (\Exception $e) {
        // Annuler la transaction en cas d'erreur
        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        return back()->withErrors(['error' => 'Erreur critique : ' . $e->getMessage()]);
    }
}
}