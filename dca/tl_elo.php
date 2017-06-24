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
 * Table tl_elo
 */
$GLOBALS['TL_DCA']['tl_elo'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'             => 'Table',
		'ptable'					=> 'tl_elo_listen',
		'enableVersioning'          => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' 				=> 'primary',
				'pid'				=> 'index',
				'fideid'			=> 'index',
				'surname'			=> 'index',
				'rating'			=> 'index',
				'games'				=> 'index',
				'rapid_rating'		=> 'index',
				'rapid_games'		=> 'index',
				'blitz_rating'		=> 'index',
				'blitz_games'		=> 'index',
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('surname'),
			'flag'                    => 1,
			'headerFields'            => array('title', 'datum'), 
			'panelLayout'             => 'sort,filter;search,limit',
			'child_record_callback'   => array('tl_elo', 'listPlayers'),
			'child_record_class'      => 'no_padding',
		),
		'label' => array
		(
			'fields'                  => array('surname'),
			'format'                  => '%s'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_elo']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_elo']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_elo']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_elo']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Select
	'select' => array
	(
		'buttons_callback' => array()
	),

	// Edit
	'edit' => array
	(
		'buttons_callback' => array()
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array(''),
		'default'                     => '{title_legend},surname,prename;'
	),

	// Subpalettes
	'subpalettes' => array
	(
		''                            => ''
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'fideid' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['fideid'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>16),
			'sql'                     => "int(16) unsigned NOT NULL default '0'"
		),
		'surname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['surname'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>64),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'prename' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['prename'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>64),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'intent' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['intent'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>16),
			'sql'                     => "varchar(16) NOT NULL default ''"
		),
		'country' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['country'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>3),
			'sql'                     => "varchar(3) NOT NULL default ''"
		),
		'sex' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['sex'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>1),
			'sql'                     => "varchar(1) NOT NULL default ''"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>3),
			'sql'                     => "varchar(3) NOT NULL default ''"
		),
		'w_title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['w_title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>3),
			'sql'                     => "varchar(3) NOT NULL default ''"
		),
		'o_title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['o_title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>3),
			'sql'                     => "varchar(3) NOT NULL default ''"
		),
		'foa_title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['foa_title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>3),
			'sql'                     => "varchar(3) NOT NULL default ''"
		),
		'rating' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['rating'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>4),
			'sql'                     => "int(4) unsigned NOT NULL default '0'"
		),
		'games' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['games'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>4),
			'sql'                     => "int(4) unsigned NOT NULL default '0'"
		),
		'rapid_rating' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['rapid_rating'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>4),
			'sql'                     => "int(4) unsigned NOT NULL default '0'"
		),
		'rapid_games' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['rapid_games'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>4),
			'sql'                     => "int(4) unsigned NOT NULL default '0'"
		),
		'blitz_rating' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['blitz_rating'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>4),
			'sql'                     => "int(4) unsigned NOT NULL default '0'"
		),
		'blitz_games' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['blitz_games'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>4),
			'sql'                     => "int(4) unsigned NOT NULL default '0'"
		),
		'birthday' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['birthday'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>8),
			'sql'                     => "int(8) unsigned NOT NULL default '0'"
		),
		'flag' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['flag'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>8),
			'sql'                     => "varchar(8) NOT NULL default ''"
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elo']['published'],
			'exclude'                 => true,
			'search'                  => false,
			'sorting'                 => false,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50','isBoolean' => true),
			'sql'                     => "char(1) NOT NULL default ''"
		), 
	)
);

/**
 * Provide miscellaneous methods that are used by the data configuration array
 */
class tl_elo extends Backend
{
	 
    /**
     * Generiere eine Zeile als HTML
     * @param array
     * @return string
     */
    public function listPlayers($arrRow)
    {
        $line = '';
        $line .= '<div>';
        $line .= $arrRow['surname'];
        if($arrRow['prename']) $line .= ', '.$arrRow['prename'];
        if($arrRow['intent']) $line .= ', '.$arrRow['intent'];
        $line .= "</div>";
        $line .= "\n";
        return($line);

    }

	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
        $this->import('BackendUser', 'User');
 
        if (strlen($this->Input->get('tid')))
        {
            $this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 0));
            $this->redirect($this->getReferer());
        }
 
        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_linkscollection_links::published', 'alexf'))
        {
            return '';
        }
 
        $href .= '&amp;id='.$this->Input->get('id').'&amp;tid='.$row['id'].'&amp;state='.$row[''];
 
        if (!$row['published'])
        {
            $icon = 'invisible.gif';
        }
 
        return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}

	/**
	 * Disable/enable a user group
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnPublished)
	{
		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_linkscollection_links::published', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide record ID "'.$intId.'"', 'tl_linkscollection_links toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	
		$this->createInitialVersion('tl_linkscollection_links', $intId);
	
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_linkscollection_links']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_linkscollection_links']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnPublished = $this->$callback[0]->$callback[1]($blnPublished, $this);
			}
		}
	
		// Update the database
		$this->Database->prepare("UPDATE tl_linkscollection_links SET tstamp=". time() .", published='" . ($blnPublished ? '' : '1') . "' WHERE id=?")
			->execute($intId);
		$this->createNewVersion('tl_linkscollection_links', $intId);
	}

}
