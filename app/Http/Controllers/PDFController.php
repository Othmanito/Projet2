<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use PDF;
use DB;
use Carbon\Carbon;

class PDFController extends Controller
{

    public function imprimer($param)
    {
      switch($param)
      {
        case 'users':         return $this->printUsers();         break;
        case 'categories':    return $this->printCategories();    break;
        case 'fournisseurs':  return $this->printFournisseurs();  break;
        case 'articles':      return $this->printArticles();      break;
        case 'promotions':    return $this->printPromotions();    break;
        case 'magasins':    return $this->printMagasins();    break;
        default: return 'erreur PDFController-> imprimer';
      }
    }

    private function printUsers()
    {
      $date = Carbon::now()->format('j/m/Y');
      //echo $date;
      //return true;
      $pdf = PDF::loadView('pdf/pdf-users',['data' => DB::table('users')->get() ,'magasins' => DB::table('magasins')->get(), 'roles' =>  DB::table('roles')->get() ] );
      return $pdf->stream("Utilisateurs $date .pdf");
    }

    public function printArticles()
    {
      $pdf = PDF::loadView('pdf/pdf-articles',['data' => DB::table('articles')->get(), 'fournisseurs' => DB::table('fournisseurs')->get(), 'categories' => DB::table('categories')->get() ] );
      return $pdf->stream('Articles '.date('d-M-Y').'.pdf');
    }

    public function printPromotions()
    {
      $pdf = PDF::loadView('pdf/pdf-promotions',['data' => DB::table('promotions')->get(), 'articles' => DB::table('articles')->get(), 'magasins' => DB::table('magasins')->get() ] );
      return $pdf->stream('Promotions '.date('d-M-Y').'.pdf');
    }

    public function printCategories()
    {
      $pdf = PDF::loadView('pdf/pdf-categories',['data' => DB::table('categories')->get() ] );
      return $pdf->stream('Categories.pdf');
    }

    public function printFournisseurs()
    {
      $pdf = PDF::loadView('pdf/pdf-fournisseurs',['data' => DB::table('fournisseurs')->get() ] );
      return $pdf->stream('Fournisseurs '.date('d-M-Y').'.pdf');
    }

    public function printMagasins()
    {
      $pdf = PDF::loadView('pdf/pdf-magasins',['data' => DB::table('magasins')->get() ] );
      return $pdf->stream('Magasins '.date('d-M-Y').'.pdf');
    }
}
