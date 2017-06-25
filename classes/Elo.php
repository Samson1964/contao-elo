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
		$cache = new \Samson\Helper\Cache('Elo');
		$cache->eraseExpired(); // Cache aufräumen, abgelaufene Schlüssel löschen

		$this->Template->headline = 'FIDE-Ratingliste Deutschland';
		$this->Template->hl = 'h2';
		$this->Template->datum = date('d.m.Y', $objActiv->datum).' ('.$objActiv->title.')';

		/*******************************************************
		 *
		 * Elo-Liste klassisch laden
		 *
		 ******************************************************/
		$cachekey = 'EloN_'.$this->elo_topcount.'_'.$objActiv->id;
		if($cache->isCached($cachekey))
		{
			// Daten aus dem Cache laden
			$eloN = $cache->retrieve($cachekey);
		}
		else
		{
			// Daten aus der Datenbank laden
			$objEloN = $this->Database->prepare('SELECT * FROM tl_elo WHERE pid=? AND published=? AND flag NOT LIKE ? ORDER BY rating DESC')
			                           ->limit($this->elo_topcount)
			                           ->execute($objActiv->id, 1, '%i%');

			// Elo klassisch zuweisen
			if($objEloN->numRows > 1)
			{
				$eloN = array();
				// Datensätze anzeigen
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
				// Daten im Cache speichern
				$cache->store($cachekey, $eloN, $cachetime);
			}
		}


		/*******************************************************
		 *
		 * Elo-Liste Blitzschach laden
		 *
		 ******************************************************/
		$cachekey = 'EloB_'.$this->elo_topcount.'_'.$objActiv->id;
		if($cache->isCached($cachekey))
		{
			// Daten aus dem Cache laden
			$eloB = $cache->retrieve($cachekey);
		}
		else
		{
			// Daten aus der Datenbank laden
			$objEloB = $this->Database->prepare('SELECT * FROM tl_elo WHERE pid=? AND published=? AND flag NOT LIKE ? ORDER BY blitz_rating DESC')
			                          ->limit($this->elo_topcount)
			                          ->execute($objActiv->id, 1, '%i%');
			// Elo Blitzschach zuweisen
			if($objEloB->numRows > 1)
			{
				$eloB = array();
				// Datensätze anzeigen
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
				// Daten im Cache speichern
				$cache->store($cachekey, $eloB, $cachetime);
			}
		}


		/*******************************************************
		 *
		 * Elo-Liste Schnellschach laden
		 *
		 ******************************************************/
		$cachekey = 'EloR_'.$this->elo_topcount.'_'.$objActiv->id;
		if($cache->isCached($cachekey))
		{
			// Daten aus dem Cache laden
			$eloR = $cache->retrieve($cachekey);
		}
		else
		{
			// Daten aus der Datenbank laden
			$objEloR = $this->Database->prepare('SELECT * FROM tl_elo WHERE pid=? AND published=? AND flag NOT LIKE ? ORDER BY rapid_rating DESC')
			                          ->limit($this->elo_topcount)
			                          ->execute($objActiv->id, 1, '%i%');
			// Elo Schnellschach zuweisen
			if($objEloR->numRows > 1)
			{
				$eloR = array();
				// Datensätze anzeigen
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
				// Daten im Cache speichern
				$cache->store($cachekey, $eloR, $cachetime);
			}
		}


		/*******************************************************
		 *
		 * Elo-Liste klassisch Frauen laden
		 *
		 ******************************************************/
		$cachekey = 'EloNw_'.$this->elo_topcount.'_'.$objActiv->id;
		if($cache->isCached($cachekey))
		{
			// Daten aus dem Cache laden
			$eloNw = $cache->retrieve($cachekey);
		}
		else
		{
			// Daten aus der Datenbank laden
			$objEloNw = $this->Database->prepare('SELECT * FROM tl_elo WHERE pid=? AND published=? AND flag NOT LIKE ? AND sex=? ORDER BY rating DESC')
			                           ->limit($this->elo_topcount)
			                           ->execute($objActiv->id, 1, '%i%', 'F');
			// Elo klassisch zuweisen
			if($objEloNw->numRows > 1)
			{
				$eloNw = array();
				// Datensätze anzeigen
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
				// Daten im Cache speichern
				$cache->store($cachekey, $eloNw, $cachetime);
			}
		}


		/*******************************************************
		 *
		 * Elo-Liste Blitzschach Frauen laden
		 *
		 ******************************************************/
		$cachekey = 'EloBw_'.$this->elo_topcount.'_'.$objActiv->id;
		if($cache->isCached($cachekey))
		{
			// Daten aus dem Cache laden
			$eloBw = $cache->retrieve($cachekey);
		}
		else
		{
			// Daten aus der Datenbank laden
			$objEloBw = $this->Database->prepare('SELECT * FROM tl_elo WHERE pid=? AND published=? AND flag NOT LIKE ? AND sex=? ORDER BY blitz_rating DESC')
			                           ->limit($this->elo_topcount)
			                           ->execute($objActiv->id, 1, '%i%', 'F');
			// Elo Blitzschach zuweisen
			if($objEloBw->numRows > 1)
			{
				$eloBw = array();
				// Datensätze anzeigen
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
				// Daten im Cache speichern
				$cache->store($cachekey, $eloBw, $cachetime);
			}
		}


		/*******************************************************
		 *
		 * Elo-Liste Schnellschach Frauen laden
		 *
		 ******************************************************/
		$cachekey = 'EloRw_'.$this->elo_topcount.'_'.$objActiv->id;
		if($cache->isCached($cachekey))
		{
			// Daten aus dem Cache laden
			$eloBw = $cache->retrieve($cachekey);
		}
		else
		{
			// Daten aus der Datenbank laden
			$objEloRw = $this->Database->prepare('SELECT * FROM tl_elo WHERE pid=? AND published=? AND flag NOT LIKE ? AND sex=? ORDER BY rapid_rating DESC')
			                           ->limit($this->elo_topcount)
			                           ->execute($objActiv->id, 1, '%i%', 'F');
			// Elo Schnellschach zuweisen
			if($objEloRw->numRows > 1)
			{
				$eloRw = array();
				// Datensätze anzeigen
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
				// Daten im Cache speichern
				$cache->store($cachekey, $eloRw, $cachetime);
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
