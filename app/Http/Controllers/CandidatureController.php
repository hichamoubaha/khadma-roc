<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CandidatureService;
use App\Models\Candidature;
use Illuminate\Support\Facades\Auth;

class CandidatureController extends Controller
{
    protected $candidatureService;

    public function __construct(CandidatureService $candidatureService)
    {
        $this->candidatureService = $candidatureService;
    }

    public function store(Request $request)
{
    $request->validate([
        'annonce_id' => 'required|exists:annonces,id',
        'cv' => 'required|file|mimes:pdf|max:2048',
        'lettre_motivation' => 'required|file|mimes:pdf|max:2048'
    ]);

    // Sauvegarde des fichiers
    $cvPath = $request->file('cv')->store('cvs', 'public');
    $lettrePath = $request->file('lettre_motivation')->store('lettres', 'public');

    // Création de la candidature
    $candidature = Candidature::create([
        'user_id' => auth()->id(),
        'annonce_id' => $request->annonce_id,
        'cv' => $cvPath,
        'lettre_motivation' => $lettrePath,
        'statut' => 'En attente'
    ]);

    return response()->json($candidature, 201);
}


    public function index()
    {
        return response()->json($this->candidatureService->obtenirCandidatures());
    }

    public function show($id)
    {
        return response()->json($this->candidatureService->obtenirCandidature($id));
    }

    public function update(Request $request, Candidature $candidature)
    {
        if ($candidature->user_id !== Auth::id()) {
            return response()->json(['message' => 'Accès interdit'], 403);
        }

        $data = $request->all();
        $candidature = $this->candidatureService->mettreAJourCandidature($candidature, $data);

        return response()->json($candidature);
    }

    public function destroy(Candidature $candidature)
    {
        if ($candidature->user_id !== Auth::id()) {
            return response()->json(['message' => 'Accès interdit'], 403);
        }

        $this->candidatureService->supprimerCandidature($candidature);

        return response()->json(['message' => 'Candidature supprimée']);
    }
    public function updateStatut(Request $request, $id)
{
    // Valider le statuts
    $request->validate([
        'statut' => 'required|in:En attente,Acceptée,Refusée'
    ]);

    // Trouver la candidatur
    $candidature = Candidature::find($id);

    if (!$candidature) {
        return response()->json(['message' => 'Candidature non trouvée'], 404);
    }

    // Vérifier l'autorisation de mise à jour (par exemple, si c'est le recruteur de l'annonce)
    if (auth()->user()->role !== 'recruteur' || auth()->id() !== $candidature->annonce->user_id) {
        return response()->json(['message' => 'Accès non autorisé'], 403);
    }

    // Mettre à jour le statut
    $candidature->statut = $request->statut;
    $candidature->save();

    // Retourner une réponse de succès
    return response()->json(['message' => 'Statut mis à jour avec succès'], 200);
}


}
