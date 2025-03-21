<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StatistiquesController extends Controller
{
    // Statistiques pour le recruteur 
    public function statistiquesRecruteur()
    {
        try {
            
            if (Auth::user()->role !== 'recruteur') {
                return response()->json(['message' => 'Accès non autorisé'], 403);
            }

            
            $annonces = Annonce::where('user_id', Auth::id())->get();
            if ($annonces->isEmpty()) {
                return response()->json(['message' => 'Aucune annonce trouvée pour ce recruteur.'], 404);
            }

            // Calculer les statistiques des candidatures
            $totalAnnonces = $annonces->count();
            $totalCandidatures = Candidature::whereIn('annonce_id', $annonces->pluck('id'))->count();
            $candidaturesParAnnonce = [];

            foreach ($annonces as $annonce) {
                $candidaturesParAnnonce[$annonce->id] = $annonce->candidatures->count();
            }

            return response()->json([
                'total_annonces' => $totalAnnonces,
                'total_candidatures' => $totalCandidatures,
                'candidatures_par_annonce' => $candidaturesParAnnonce
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur lors du calcul des statistiques : " . $e->getMessage());
            return response()->json(['message' => 'Une erreur est survenue.'], 500);
        }
    }

    // Statistiques globales pour l'administrateur
    public function statistiquesAdmin()
    {
        // Vérifier que l'utilisateur est un administrateur
        if (Auth::user()->role !== 'administrateur') {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        // Récupérer les statistiques globales
        $totalUtilisateurs = \App\Models\User::count();
        $totalRecruteurs = \App\Models\User::where('role', 'recruteur')->count();
        $totalCandidats = \App\Models\User::where('role', 'candidat')->count();
        $totalAnnonces = Annonce::count();
        $totalCandidatures = Candidature::count();

        return response()->json([
            'total_utilisateurs' => $totalUtilisateurs,
            'total_recruteurs' => $totalRecruteurs,
            'total_candidats' => $totalCandidats,
            'total_annonces' => $totalAnnonces,
            'total_candidatures' => $totalCandidatures
        ]);
    }
}
