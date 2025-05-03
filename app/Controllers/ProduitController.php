<?php
namespace App\Controllers;
use App\models\Produit;

class ProduitController{
    
    // Recupere tout dans la table produit
    public static function get(){
        $produits = Produit::take(10)->get(); 
        return $produits;
    }
}