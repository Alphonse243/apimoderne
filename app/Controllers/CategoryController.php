<?php

namespace App\Controllers;
use App\models\Category; 

class CategoryController
{
   public static function get(){
      return Category::all();
   }
   public static function getAllCategory()
   {
      return Category::with('posts')->orderBy('name','asc')->get();
   }
   public static function getAllCategoryById($categorie_id)
   {
      return Category::where('id',$categorie_id)->get();
   }
   public static function getAllCategoryArea(){

      return Category::inRandomOrder()->take(3)->get();
   }
}   