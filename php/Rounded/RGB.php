<?php
/**
 * Rounded PHP, Rounded corners made easy.
 *
 * Rounded_RGB class
 *
 * PHP version 5, GD version 2
 *
 * Copyright (C) 2008 Tree Fort LLC
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @category	Rounded PHP
 * @package		Rounded
 * @author		Nevada Kent <dev@kingthief.com>
 * @version		1.0
 * @link		http://dev.kingthief.com
 * @link		http://dev.kingthief.com/demos/roundedphp
 * @link		http://www.sourceforge.net/projects/roundedphp
 *
 *
 *
 * Class used to convert hex codes to hexidecimal rgb values
 *
 * Use:
 *  $rgb = new Rounded_RGB('FFAAEE');
 *  $color = imagecolorallocate($img, $rgb->red, $rgb->green, $rgb->blue);
 */
class Rounded_RGB
{
	public $hex,		# original hex code
		   $red = 0,	# red hexidecimal value
		   $green = 0,	# green hexidecimal value
		   $blue = 0;	# blue hexidecimal value
	
	/**
	 * Rounded_RGB
	 *
	 * Constructor for the RGB object.
	 *
	 * @access	public
	 * @param	string	$hex	3 or 6 character hex code
	 * @return	void
	 */
	public function Rounded_RGB ($hex)
	{
		$this->hex = preg_replace('/[^a-fA-F0-9]+/', '', $hex);
		if (preg_match('/^([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])$/', $this->hex, $m)) {
			$this->red = hexdec($m[1] . $m[1]);
			$this->green = hexdec($m[2] . $m[2]);
			$this->blue = hexdec($m[3] . $m[3]);
		} else if (preg_match('/^([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/', $this->hex, $m)) {
			$this->red = hexdec($m[1]);
			$this->green = hexdec($m[2]);
			$this->blue = hexdec($m[3]);
		}
	}
}
?>