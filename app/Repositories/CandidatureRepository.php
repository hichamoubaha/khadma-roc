<?php

namespace App\Repositories;

use App\Models\Candidature;

class CandidatureRepository
{
    public function create(array $data)
    {
        return Candidature::create($data);
    }

    public function getAll()
    {
        return Candidature::all();
    }

    public function getById($id)
    {
        return Candidature::findOrFail($id);
    }

    public function getByAnnonce($annonceId)
    {
        return Candidature::where('annonce_id', $annonceId)->get();
    }

    public function update(Candidature $candidature, array $data)
    {
        $candidature->update($data);
        return $candidature;
    }

    public function delete(Candidature $candidature)
    {
        $candidature->delete();
    }
}
