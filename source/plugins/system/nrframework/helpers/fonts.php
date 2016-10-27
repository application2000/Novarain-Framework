<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2016 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class NRFonts
{

	/**
	 *  Classic Fonts
	 *
	 *  @var  array
	 */
	private $classic = array(
		"Arial",
		"Arial Black",
		"Georgia",
		"Tahoma",
		"Franklin Gothic Medium",
		"Calibri",
		"Cambria",
		"Century Gothic",
		"Consolas",
		"Corbel",
		"Courier New",
		"Times New Roman",
		"Impact",
		"Lucida Console",
		"Palatino Linotype",
		"Trebuchet MS",
		"Verdana"
	);

	/**
	 *  Google Fonts List
	 *
	 *  @var  array
	 */
	private $google = array(
		"Roboto",
		"Open Sans",
		"Slabo 27px",
		"Lato",
		"Oswald",
		"Roboto Condensed",
		"Source Sans Pro",
		"Montserrat",
		"Raleway",
		"PT Sans",
		"Roboto Slab",
		"Lora",
		"Droid Sans",
		"Merriweather",
		"Ubuntu",
		"Droid Serif",
		"Arimo",
		"Noto Sans",
		"PT Sans Narrow"
	);

	/**
	 *  Returns all font groups alphabetically sorted
	 *
	 *  @return  array
	 */
	public function getFontGroups()
	{
		return array(
			"Google Fonts" => $this->getFontGroup("google"),
			"Classic" => $this->getFontGroup("classic")
		);
	}

	/**
	 *  Returns a font group alphabetically sorted
	 *
	 *  @param   string  $name  The Font Group
	 *
	 *  @return  array         
	 */
	public function getFontGroup($name)
	{
		$fonts = $this->$name;
		sort($fonts);
		return $fonts;
	}

	/**
	 *  Loads Google font to the document
	 *
	 *  @param   mixed  $name  The Google font name
	 *
	 *  @return  void
	 */
	public function loadFont($names)
	{
		if (!$names)
		{
			return;
		}

		if (!is_array($names))
		{
			$names[] = $names;
		}

		foreach ($names as $key => $value)
		{
			// If font is a Google Font then load it into the document
	        if (in_array($value, $this->google))
	        {
	            JFactory::getDocument()->addStylesheet("//fonts.googleapis.com/css?family=".urlencode($value));
	        }
		}
	}
}