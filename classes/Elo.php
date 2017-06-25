<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   Elo
 * @author    Frank Hoppe
 * @license   GNU/LPGL
 * @copyright Frank Hoppe 2016
 */


/**
 * Namespace
 */
namespace Samson\Elo;

/**
 * Class Elo
 *
 * @copyright  Frank Hoppe 2016
 * @author     Frank Hoppe
 * @package    Devtools
 */
class Elo extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_elo';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ELO-LISTE ###';
			$objTemplate->title = $this->name;
			$objTemplate->id = $this->id;

			return $objTemplate->parse();
		}
		
		return parent::generate(); // Weitermachen mit dem Modul
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->import('Database');

		$cachetime = 3600 * 24 * 40; // 40 Tage

		// Aktuelle Liste ermitteln
		$objActiv = $this->Database->prepare('SELECT * FROM tl_elo_listen WHERE published=? ORDER BY datum DESC')
		                           ->limit(1)
		                           ->execute(1);

		// Cache initialisieren
		//$cache = new \Samson\Cache();
		//$cache->eraseExpired(); // Cache aufr�umen, abgelaufene Schl�ssel l�schen

		$this->Template->headline = 'FIDE-Ratingliste Deutschland';
		$this->Template->hl = 'h2';
		$this->Template->datum = date('d.m.Y', $objActiv->datum).' ('.$objActiv->title.')';

		// Toplisten laden
		// Cache laden
		//if($cache->isCached('EloN_'.$objActiv->id))
		//{
		//	//$objEloN = $cache->retrieve('EloN_'.$objActiv->id);
		//}
		//else
		//{
			$objEloN = $this->Database->prepare('SELECT * FROM tl_elo WHERE pid=? AND published=? AND flag NOT LIKE ? ORDER BY rating DESC')
			                           ->limit($this->elo_topcount)
			                           ->execute($objActiv->id, 1, '%i%');
			//$cache->store('EloN_'.$objActiv->id, $objEloN, $cachetime);
		//}

		$objEloB = $this->Database->prepare('SELECT * FROM tl_elo WHERE pid=? AND published=? AND flag NOT LIKE ? ORDER BY blitz_rating DESC')
								   ->limit($this->elo_topcount)
		                           ->execute($objActiv->id, 1, '%i%');
		$objEloR = $this->Database->prepare('SELECT * FROM tl_elo WHERE pid=? AND published=? AND flag NOT LIKE ? ORDER BY rapid_rating DESC')
								   ->limit($this->elo_topcount)
		                           ->execute($objActiv->id, 1, '%i%');
		$objEloNw = $this->Database->prepare('SELECT * FROM tl_elo WHERE pid=? AND published=? AND flag NOT LIKE ? AND sex=? ORDER BY rating DESC')
								   ->limit($this->elo_topcount)
		                           ->execute($objActiv->id, 1, '%i%', 'F');
		$objEloBw = $this->Database->prepare('SELECT * FROM tl_elo WHERE pid=? AND published=? AND flag NOT LIKE ? AND sex=? ORDER BY blitz_rating DESC')
								   ->limit($this->elo_topcount)
		                           ->execute($objActiv->id, 1, '%i%', 'F');
		$objEloRw = $this->Database->prepare('SELECT * FROM tl_elo WHERE pid=? AND published=? AND flag NOT LIKE ? AND sex=? ORDER BY rapid_rating DESC')
								   ->limit($this->elo_topcount)
		                           ->execute($objActiv->id, 1, '%i%', 'F');

		// Elo klassisch zuweisen
		if($objEloN->numRows > 1)
		{
			$eloN = array();
			// Datens�tze anzeigen
			while($objEloN->next()) 
			{
				$line = $objEloN->intent;
				$line .= ($line) ? ' '.$objEloN->prename : $objEloN->prename; 
				$line .= ($line) ? ' '.$objEloN->surname : $objEloN->surname; 
				$eloN[] = array
				(
					'name' 	=> $line,
					'elo'  	=> $objEloN->rating,
					'fid' 	=> $objEloN->fideid,
					'title'	=> ($objEloN->title) ? $objEloN->title . ' ' : (($objEloN->w_title) ? $objEloN->w_title . ' ': ''),
				);
			}
		}

		// Elo Blitzschach zuweisen
		if($objEloB->numRows > 1)
		{
			$eloB = array();
			// Datens�tze anzeigen
			while($objEloB->next()) 
			{
				$line = $objEloB->intent;
				$line .= ($line) ? ' '.$objEloB->prename : $objEloB->prename; 
				$line .= ($line) ? ' '.$objEloB->surname : $objEloB->surname; 
				$eloB[] = array
				(
					'name' 	=> $line,
					'elo'  	=> $objEloB->blitz_rating,
					'fid' 	=> $objEloB->fideid,
					'title'	=> ($objEloB->title) ? $objEloB->title . ' ' : (($objEloB->w_title) ? $objEloB->w_title . ' ': ''),
				);
			}
		}

		// Elo Schnellschach zuweisen
		if($objEloR->numRows > 1)
		{
			$eloR = array();
			// Datens�tze anzeigen
			while($objEloR->next()) 
			{
				$line = $objEloR->intent;
				$line .= ($line) ? ' '.$objEloR->prename : $objEloR->prename; 
				$line .= ($line) ? ' '.$objEloR->surname : $objEloR->surname; 
				$eloR[] = array
				(
					'name' 	=> $line,
					'elo'  	=> $objEloR->rapid_rating,
					'fid' 	=> $objEloR->fideid,
					'title'	=> ($objEloR->title) ? $objEloR->title . ' ' : (($objEloR->w_title) ? $objEloR->w_title . ' ': ''),
				);
			}
		}
		
		// Elo klassisch zuweisen
		if($objEloNw->numRows > 1)
		{
			$eloNw = array();
			// Datens�tze anzeigen
			while($objEloNw->next()) 
			{
				$line = $objEloNw->intent;
				$line .= ($line) ? ' '.$objEloNw->prename : $objEloNw->prename; 
				$line .= ($line) ? ' '.$objEloNw->surname : $objEloNw->surname; 
				$eloNw[] = array
				(
					'name' 	=> $line,
					'elo'  	=> $objEloNw->rating,
					'fid' 	=> $objEloNw->fideid,
					'title'	=> ($objEloNw->title) ? $objEloNw->title . ' ' : (($objEloNw->w_title) ? $objEloNw->w_title . ' ': ''),
				);
			}
		}

		// Elo Blitzschach zuweisen
		if($objEloBw->numRows > 1)
		{
			$eloBw = array();
			// Datens�tze anzeigen
			while($objEloBw->next()) 
			{
				$line = $objEloBw->intent;
				$line .= ($line) ? ' '.$objEloBw->prename : $objEloBw->prename; 
				$line .= ($line) ? ' '.$objEloBw->surname : $objEloBw->surname; 
				$eloBw[] = array
				(
					'name' 	=> $line,
					'elo'  	=> $objEloBw->blitz_rating,
					'fid' 	=> $objEloBw->fideid,
					'title'	=> ($objEloBw->title) ? $objEloBw->title . ' ' : (($objEloBw->w_title) ? $objEloBw->w_title . ' ': ''),
				);
			}
		}

		// Elo Schnellschach zuweisen
		if($objEloRw->numRows > 1)
		{
			$eloRw = array();
			// Datens�tze anzeigen
			while($objEloRw->next()) 
			{
				$line = $objEloRw->intent;
				$line .= ($line) ? ' '.$objEloRw->prename : $objEloRw->prename; 
				$line .= ($line) ? ' '.$objEloRw->surname : $objEloRw->surname; 
				$eloRw[] = array
				(
					'name' 	=> $line,
					'elo'  	=> $objEloRw->rapid_rating,
					'fid' 	=> $objEloRw->fideid,
					'title'	=> ($objEloRw->title) ? $objEloRw->title . ' ' : (($objEloRw->w_title) ? $objEloRw->w_title . ' ': ''),
				);
			}
		}
		
		$this->Template->count = $this->elo_topcount;
		$this->Template->eloN = $eloN;
		$this->Template->eloB = $eloB;
		$this->Template->eloR = $eloR;
		$this->Template->eloNw = $eloNw;
		$this->Template->eloBw = $eloBw;
		$this->Template->eloRw = $eloRw;

	}
}
