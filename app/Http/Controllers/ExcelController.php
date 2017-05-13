<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Magasin;
use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\Article;
use App\Models\Marque;
use App\Models\Stock;
use App\Models\Promotion;
use \Exception;
use \Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;


class ExcelController extends Controller
{

	public function export($p_table)
	{


		switch($p_table)
		{
			case 'users': 				$this->ExportUsers(); 				break;
			case 'articles': 				$this->ExportArticles(); 				break;
			case 'fournisseurs': 	$this->ExportFournisseurs(); 	break;
			case 'categories': 		$this->ExportCategories(); 		break;
			case 'magasins': 		$this->ExportMagasins(); 		break;
			case 'stocks': 		$this->ExportStocks(); 		break;
			case 'promotions': 		$this->ExportPromotions(); 		break;
			default: return redirect()->back()->withInput()->with('alert_warning',' Vous avez pris le mauvais chemin. ==> ExcelController@export');      break;
		}
	}


	//fonction pour exporter la liste des utilisateurs
	public function ExportUsers()
	{
		$carbon = new Carbon();
		$date =  $carbon->format('d/m/Y H:m:s');

		Excel::create('Liste Utilisateurs '.$date, function($excel)
		{
			$excel->getDefaultStyle()
        ->getAlignment()
        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->sheet('Utilisateurs', function($sheet)
			{
				$data = User::all(); $i=2;


					$sheet->cells('A1:I1', function($cells) {
										$cells->setBackground('#E6E8EE');
										$cells->setAlignment('center');
										$cells->setFont(array(
																			    'family'     => 'Calibri',
																			    'size'       => '14',
																			    'bold'       =>  true
																			));
				});

				$sheet->setStyle(array(
				'font' => array(
					'name'      =>  'Calibri',
					'size'      =>  12

				)
				));
				//$sheet->setOrientation('landscape');
				$sheet->with( array('Role','Magasin', 'Nom','Prenom','Ville','Telephone','Description','Email','Date de creation') );



				foreach( $data as $item )
				{

					$sheet->row( $i++ ,
					array(
						getChamp('roles', 'id_role', $item->id_role, 'libelle'),
						getChamp('magasins', 'id_magasin', $item->id_magasin, 'libelle'),
						$item->nom,$item->prenom,
						$item->ville,
						$item->telephone,
						$item->description,
						$item->email,
						getDateHelper($item->created_at).' à '.getTimeHelper($item->created_at)
						)

					);
				}
			});
		})->download('xls');
	}

	//fonction pour exporter la liste des fournisseurs
	public function ExportFournisseurs()
	{
		$carbon = new Carbon();
		$date =  $carbon->format('d/m/Y H:m:s');

		Excel::create('Fournisseurs '.$date, function($excel)
		{
			$excel->getDefaultStyle()
        ->getAlignment()
        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->sheet('Fournisseurs', function($sheet)
			{
				$data = Fournisseur::all(); $i=2;

				$sheet->cells('A1:I1', function($cells) {
									$cells->setBackground('#E6E8EE');
									$cells->setAlignment('center');
									$cells->setFont(array(
																				'family'     => 'Calibri',
																				'size'       => '14',
																				'bold'       =>  true
																		));
			});

			$sheet->setStyle(array(
			'font' => array(
				'name'      =>  'Calibri',
				'size'      =>  12

			)
			));
				//$sheet->setOrientation('landscape');
				$sheet->fromArray( array('Code','Nom','Agent','Email','Telephone','Fax','Description','Date de creation') );
				foreach( $data as $item )
				{
					$sheet->row( $i++ ,
					array(
						$item->code,$item->libelle,
						$item->agent,$item->email,
						$item->telephone,$item->fax,
						$item->description,
						getDateHelper($item->created_at).' à '.getTimeHelper($item->created_at))
					);
				}
			});
		})->download('xls');
	}

	//fonction pour exporter la liste des categories
	public function ExportCategories()
	{
		$carbon = new Carbon();
		$date =  $carbon->format('d/m/Y H:m:s');

		Excel::create('Categories '.$date, function($excel)
		{
			$excel->getDefaultStyle()
        ->getAlignment()
        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->sheet('Fournisseurs', function($sheet)
			{
				$data = Categorie::all(); $i=2;

				$sheet->cells('A1:C1', function($cells) {
									$cells->setBackground('#E6E8EE');
									$cells->setAlignment('center');
									$cells->setFont(array(
																				'family'     => 'Calibri',
																				'size'       => '14',
																				'bold'       =>  true
																		));
			});

			$sheet->setStyle(array(
			'font' => array(
				'name'      =>  'Calibri',
				'size'      =>  12

			)
			));
				//$sheet->setOrientation('landscape');
				$sheet->fromArray( array('Nom Categorie','Description','Date de creation') );
				foreach( $data as $item )
				{
					$sheet->row( $i++ ,
					array(
						$item->libelle,
						$item->description,
						getDateHelper($item->created_at).' à '.getTimeHelper($item->created_at))
					);
				}
			});
		})->download('xls');
	}

	//fonction pour exporter la liste des magasins
	public function ExportMagasins()
	{
		$carbon = new Carbon();
		$date =  $carbon->format('d/m/Y H:m:s');

		Excel::create('Magasins '.$date, function($excel)
		{
			$excel->getDefaultStyle()
        ->getAlignment()
        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->sheet('Liste des magasins', function($sheet)
			{
				$data = Magasin::all(); $i=2;

				$sheet->cells('A1:H1', function($cells) {
									$cells->setBackground('#E6E8EE');
									$cells->setAlignment('center');
									$cells->setFont(array(
																				'family'     => 'Calibri',
																				'size'       => '14',
																				'bold'       =>  true
																		));
			});

			$sheet->setStyle(array(
			'font' => array(
				'name'      =>  'Calibri',
				'size'      =>  12

			)
			));

				$sheet->fromArray( array('Nom Magasin','Ville','Agent','Telephone','Email','Adresse','Description','Date de creation') );
				foreach( $data as $item )
				{
					$sheet->row( $i++ ,
					array(
						$item->libelle,
						$item->ville,
						$item->agent,
						$item->telephone,
						$item->email,
						$item->adresse,
						$item->description,
						getDateHelper($item->created_at).' à '.getTimeHelper($item->created_at))
					);
				}
			});
		})->download('xls');
	}


	public function ExportStocks()
	{
		$carbon = new Carbon();
		$date =  $carbon->format('d/m/Y H:m:s');

		Excel::create('Stock du magasin  '.$date, function($excel)
		{
			$excel->getDefaultStyle()
        ->getAlignment()
        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->sheet('Stock du magasin', function($sheet)
			{
				$data = Stock::where('id_magasin', 1); $i=2;

				$sheet->cells('A1:E1', function($cells) {
									$cells->setBackground('#E6E8EE');
									$cells->setAlignment('center');
									$cells->setFont(array(
																				'family'     => 'Calibri',
																				'size'       => '14',
																				'bold'       =>  true
																		));
			});

			$sheet->setStyle(array(
			'font' => array(
				'name'      =>  'Calibri',
				'size'      =>  12

			)
			));

				$sheet->fromArray( array('Article ','Quantité en Stock','Quantite Minimale','Quantite Maximale','Date de creation') );
				foreach( $data as $item )
				{
					$sheet->row( $i++ ,
					array(
						getChamp('articles', 'id_article', $item->id_article, 'designation_c'),
						$item->quantite,
						$item->quantite_min,
						$item->quantite_max,
						getDateHelper($item->created_at).' à '.getTimeHelper($item->created_at))
					);
				}
			});
		})->download('xls');
	}


	public function ExportPromotions()
	{
		$carbon = new Carbon();
		$date =  $carbon->format('d/m/Y H:m:s');

		Excel::create('Liste des promotions  '.$date, function($excel)
		{
			$excel->getDefaultStyle()
        ->getAlignment()
        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->sheet('Liste des Promotions', function($sheet)
			{
				$data = Promotion::all(); $i=2;


				$sheet->setAllBorders('solid');
				$sheet->cells('A1:E1', function($cells) {
									$cells->setBackground('#E6E8EE');
									$cells->setAlignment('center');
									$cells->setFont(array(
																				'family'     => 'Calibri',
																				'size'       => '14',
																				'bold'       =>  true
																		));
			});

			$sheet->setStyle(array(
			'font' => array(
				'name'      =>  'Calibri',
				'size'      =>  12

			)
			));

				$sheet->fromArray( array('Article ','Magasin','Taux de Promotion','Date de debut','Date de fin') );
				foreach( $data as $item )
				{
					$sheet->row( $i++ ,
					array(
						getChamp('articles', 'id_article', $item->id_article, 'designation_c'),
						getChamp('magasins', 'id_magasin', $item->id_magasin, 'libelle'),
						$item->taux,
						getDateHelper($item->date_debut),
						getDateHelper($item->date_fin))
					);
				}
			});
		})->download('xls');
	}


		//fonction pour exporter la liste des Articles
	public function ExportArticles()
	{
		$carbon = new Carbon();
		$date =  $carbon->format('d/m/Y H:m:s');

		Excel::create('Liste Articles '.$date, function($excel)
		{
			$excel->getDefaultStyle()
        ->getAlignment()
        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->sheet('Liste Articles', function($sheet)
			{
				$data = Article::all(); $i=2;


					$sheet->cells('A1:J1', function($cells) {
										$cells->setBackground('#E6E8EE');
										$cells->setAlignment('center');
										$cells->setFont(array(
																			    'family'     => 'Calibri',
																			    'size'       => '14',
																			    'bold'       =>  true
																			));
				});

				$sheet->setStyle(array(
				'font' => array(
					'name'      =>  'Calibri',
					'size'      =>  12

				)
				));
				//$sheet->setOrientation('landscape');
				$sheet->with( array('Code Article','Designation Article','Categorie Article', 'Fournisseur Article','Taille','Couleur','Sexe','Prix Achat','Prix Vente','Date de creation') );



				foreach( $data as $item )
				{

					$sheet->row( $i++ ,
					array(
						$item->num_article,
						$item->designation_c,
						getChamp('categories', 'id_categorie', $item->id_categorie, 'libelle'),
						getChamp('fournisseurs', 'id_fournisseur', $item->id_fournisseur, 'libelle'),
						$item->taille,
						$item->couleur,
						$item->sexe,
						$item->prix_achat,
						$item->prix_vente,
						getDateHelper($item->created_at).' à '.getTimeHelper($item->created_at)
						)

					);
				}
			});
		})->download('xls');
	}

}
