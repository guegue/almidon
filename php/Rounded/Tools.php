<?php
/**
 * Rounded PHP, Rounded corners made easy.
 *
 * Rounded_Tools class
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
 */


# Require RGB class for color components
require_once 'Rounded/RGB.php';


/**
 * Class containing util functions used throughout Rounded PHP
 */
class Rounded_Tools
{
	/**
	 * ImageFlipHorizontal
	 *
	 * Flip an image horizontally
	 *
	 * @access	public static
	 * @param	image	$old	image resource for original image
	 * @return	image			image resource for altered image
	 */
	public static function imageFlipHorizontal($old)
	{
		$w = imagesx($old);
		$h = imagesy($old);
		$new = imagecreatetruecolor($w, $h);
		imagealphablending($new, false);
		for ($x = 0; $x < $w; $x++)
			imagecopy($new, $old, $x, 0, $w - $x - 1, 0, 1, $h);
		return $new;
	}
	
	/**
	 * ImageFlipVertical
	 *
	 * Flip an image vertically
	 *
	 * @access	public static
	 * @param	image	$old	image resource for original image
	 * @return	image			image resource for altered image
	 */
	public static function imageFlipVertical($old)
	{
		$w = imagesx($old);
		$h = imagesy($old);
		$new = imagecreatetruecolor($w, $h);
		imagealphablending($new, false);
		for ($y = 0; $y < $h; $y++)
			imagecopy($new, $old, 0, $y, 0, $h - $y - 1, $w, 1);
		return $new;
	}
	
	/**
	 * ImageFlipBoth
	 *
	 * Flip an image both horizontally and vertically
	 *
	 * @access	public static
	 * @param	image	$old	image resource for original image
	 * @return	image			image resource for altered image
	 */
	public static function imageFlipBoth($old)
	{
		return Rounded_Tools::imageFlipHorizontal(Rounded_Tools::imageFlipVertical($old));
	}
}
?>