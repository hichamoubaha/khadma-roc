<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnnonceService;
use App\Models\Annonce;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class AnnonceController extends Controller
{
    protected $annonceService;

    public function __construct(AnnonceService $annonceService)
    {
        $this->annonceService = $annonceService;
    }

    public function store(Request $request)
    {

        if (!Gate::allows('create', Annonce::class)) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'entreprise' => 'required|string|max:255',
            'lieu' => 'required|string|max:255',
            'type_contrat' => 'required|in:CDI,CDD,Stage,Freelance',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        $annonce = $this->annonceService->creerAnnonce($data);

        return response()->json($annonce, 201);
    }

    public function index()
    {
        return response()->json($this->annonceService->obtenirAnnonces());
    }

    public function show($id)
    {
        return response()->json($this->annonceService->obtenirAnnonce($id));
    }

    public function update(Request $request, Annonce $annonce)
    {

        if (!Gate::allows('update', $annonce)) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        if ($annonce->user_id !== Auth::id()) {
            return response()->json(['message' => 'Accès interdit'], 403);
        }

        $data = $request->all();
        $annonce = $this->annonceService->mettreAJourAnnonce($annonce, $data);

        return response()->json($annonce);
    }

    public function destroy(Annonce $annonce)
    {

        if (!Gate::allows('delete', $annonce)) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        if ($annonce->user_id !== Auth::id()) {
            return response()->json(['message' => 'Accès interdit'], 403);
        }

        $this->annonceService->supprimerAnnonce($annonce);

        return response()->json(['message' => 'Annonce supprimée']);
    }
}


