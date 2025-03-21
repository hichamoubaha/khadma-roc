<?php

namespace App\Repositories;

use App\Models\Annonce;

class AnnonceRepository
{
    public function create(array $data)
    {
        return Annonce::create($data);
    }

    public function getAll()
    {
        return Annonce::all();
    }

    public function getById($id)
    {
        return Annonce::findOrFail($id);
    }

    public function update(Annonce $annonce, array $data)
    {
        $annonce->update($data);
        return $annonce;
    }

    public function delete(Annonce $annonce)
    {
        $annonce->delete();
    }
}
