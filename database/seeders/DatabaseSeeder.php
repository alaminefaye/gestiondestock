<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\MouvementStock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer des catégories
        $categories = [
            ['nom' => 'Électronique', 'description' => 'Produits électroniques et accessoires', 'actif' => true],
            ['nom' => 'Bureautique', 'description' => 'Fournitures de bureau', 'actif' => true],
            ['nom' => 'Mobilier', 'description' => 'Meubles et équipements', 'actif' => true],
            ['nom' => 'Informatique', 'description' => 'Matériel informatique', 'actif' => true],
            ['nom' => 'Consommables', 'description' => 'Produits consommables', 'actif' => true],
        ];

        foreach ($categories as $cat) {
            Categorie::create($cat);
        }

        // Créer des fournisseurs
        $fournisseurs = [
            [
                'nom' => 'Tech Supplies Sénégal',
                'email' => 'contact@techsupplies.sn',
                'telephone' => '+221 33 123 45 67',
                'adresse' => 'Rue 10, Zone Industrielle',
                'ville' => 'Dakar',
                'pays' => 'Sénégal',
                'actif' => true
            ],
            [
                'nom' => 'Bureau Plus',
                'email' => 'info@bureauplus.com',
                'telephone' => '+221 33 987 65 43',
                'adresse' => 'Avenue Pompidou',
                'ville' => 'Dakar',
                'pays' => 'Sénégal',
                'actif' => true
            ],
            [
                'nom' => 'IT Solutions',
                'email' => 'contact@itsolutions.sn',
                'telephone' => '+221 77 555 44 33',
                'adresse' => 'Almadies, Route de Ngor',
                'ville' => 'Dakar',
                'pays' => 'Sénégal',
                'actif' => true
            ],
        ];

        foreach ($fournisseurs as $four) {
            Fournisseur::create($four);
        }

        // Créer des produits
        $produits = [
            [
                'nom' => 'Ordinateur Portable Dell',
                'reference' => 'DELL-LAP-001',
                'description' => 'Ordinateur portable Dell Latitude 15 pouces',
                'categorie_id' => 4,
                'fournisseur_id' => 1,
                'prix_achat' => 450000,
                'prix_vente' => 550000,
                'quantite_stock' => 15,
                'seuil_alerte' => 5,
                'unite' => 'unité',
                'actif' => true
            ],
            [
                'nom' => 'Souris Sans Fil',
                'reference' => 'MOUSE-WL-002',
                'description' => 'Souris sans fil optique',
                'categorie_id' => 1,
                'fournisseur_id' => 1,
                'prix_achat' => 5000,
                'prix_vente' => 8000,
                'quantite_stock' => 50,
                'seuil_alerte' => 15,
                'unite' => 'unité',
                'actif' => true
            ],
            [
                'nom' => 'Ramette Papier A4',
                'reference' => 'PAP-A4-500',
                'description' => 'Ramette de 500 feuilles A4 80g',
                'categorie_id' => 2,
                'fournisseur_id' => 2,
                'prix_achat' => 2500,
                'prix_vente' => 3500,
                'quantite_stock' => 3,
                'seuil_alerte' => 10,
                'unite' => 'ramette',
                'actif' => true
            ],
            [
                'nom' => 'Chaise de Bureau',
                'reference' => 'CHAIR-OFF-003',
                'description' => 'Chaise de bureau ergonomique',
                'categorie_id' => 3,
                'fournisseur_id' => 2,
                'prix_achat' => 35000,
                'prix_vente' => 50000,
                'quantite_stock' => 8,
                'seuil_alerte' => 3,
                'unite' => 'unité',
                'actif' => true
            ],
            [
                'nom' => 'Clavier USB',
                'reference' => 'KEY-USB-004',
                'description' => 'Clavier USB AZERTY',
                'categorie_id' => 1,
                'fournisseur_id' => 3,
                'prix_achat' => 4000,
                'prix_vente' => 7000,
                'quantite_stock' => 25,
                'seuil_alerte' => 10,
                'unite' => 'unité',
                'actif' => true
            ],
        ];

        foreach ($produits as $prod) {
            Produit::create($prod);
        }

        // Créer quelques mouvements
        $mouvements = [
            [
                'produit_id' => 1,
                'type' => 'entree',
                'quantite' => 10,
                'prix_unitaire' => 450000,
                'motif' => 'Achat initial',
                'date_mouvement' => now()->subDays(5),
            ],
            [
                'produit_id' => 2,
                'type' => 'entree',
                'quantite' => 50,
                'prix_unitaire' => 5000,
                'motif' => 'Réapprovisionnement',
                'date_mouvement' => now()->subDays(3),
            ],
            [
                'produit_id' => 1,
                'type' => 'sortie',
                'quantite' => 2,
                'prix_unitaire' => 550000,
                'motif' => 'Vente client',
                'date_mouvement' => now()->subDays(1),
            ],
        ];

        foreach ($mouvements as $mvt) {
            MouvementStock::create($mvt);
        }
    }
}
