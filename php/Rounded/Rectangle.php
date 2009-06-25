<?php
/**
 * Rounded PHP, Rounded corners made easy.
 *
 * Rounded_Rectangle class
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
 * @version		1.1
 * @link		http://dev.kingthief.com
 * @link		http://dev.kingthief.com/demos/roundedphp
 * @link		http://www.sourceforge.net/projects/roundedphp
 */


# Require Corner, RGB and Tools classes
require_once 'Rounded/RGB.php';
require_once 'Rounded/Corner.php';
require_once 'Rounded/Tools.php';


/**
 * Class used to create rounded rectangle images with optional borders
 *
 * Use:
 *  $params = array('radius' => 15,
 * 					'width' => 300,
 *					'height' => 500,
 *					'background' => 'FF0000');
 *  $img = Rounded_Rectangle::create($params);
 *  header('Content-Type: image/png');
 *  imagepng($img);
 */
class Rounded_Rectangle
{
	private $image,						# image resource
			$width = 100,				# width of rectangle
			$height = 100,				# height of rectangle
			$radius = 10,				# radius of corner
			$foreground = 'CCC',		# color of corner
			$background = 'FFF',		# color of background
			$borderwidth = 0,			# width of border
			$bordercolor = '000',		# color of border (if border width > 0)
			$bgtransparent = false,		# transparent background flag
			$btransparent = false,		# transparent border flag
			$fgtransparent = false,		# transparent foreground flag
			$antialias = true;			# antialias flag
	
	/**
	 * Rounded_Rectangle
	 *
	 * Constructor for the Rectangle object.
	 *
	 * @access	public
	 * @param	array	$params	Associative array of custom parameters:
	 *								- width			: {2, 3, ... , n}
	 *								- height		: {2, 3, ... , n}
	 *								- radius		: {1, 2, ... , n}
	 *								- foreground	: 6 (or 3) character hex color code
	 *								- background	: 6 (or 3) character hex color code
	 *								- borderwidth	: {0, 1, ... , n}
	 *								- bordercolor	: 6 (or 3) character hex color code
	 *								- bgtransparent	: {true, false}
	 *								- btransparent	: {true, false}
	 *								- fgtransparent	: {true, false}
	 *								- antialias		: {true, false}
	 * @return	void
	 */
	public function Rounded_Rectangle($params)
	{
		if (is_array($params))
			foreach($params as $param => $value)
				$this->{$param} = $value;
		
		$this->width = max($this->width, 2);
		$this->height = max($this->height, 2);
		$this->radius = max(min(floor(min($this->width, $this->height) / 2), $this->radius), 0);
		$this->borderwidth = max(min(ceil(min($this->width, $this->height) / 2), $this->borderwidth), 0);
	}
	
	/**
	 * Image
	 *
	 * Used to build the actual image resource.
	 *
	 * @access	public
	 * @return	image resource for rounded rectangle
	 */
	public function image()
	{
		$this->image = imagecreatetruecolor($this->width, $this->height);
		imagealphablending($this->image, !($this->bgtransparent || $this->btransparent || $this->fgtransparent));
		
		$rgb = new Rounded_RGB($this->bordercolor);
		$color = imagecolorallocatealpha($this->image, $rgb->red, $rgb->green, $rgb->blue, $this->borderwidth == 0 || $this->btransparent ? 127 : 0);
		imagefilledrectangle($this->image, 0, 0, $this->width - 1, $this->height - 1, $color);
		
		if ($this->borderwidth < min($this->width, $this->height) / 2) {
			$rgb = new Rounded_RGB($this->foreground);
			$color = imagecolorallocatealpha($this->image, $rgb->red, $rgb->green, $rgb->blue, $this->fgtransparent ? 127 : 0);
			imagefilledrectangle($this->image, $this->borderwidth, $this->borderwidth, $this->width - $this->borderwidth - 1, $this->height - $this->borderwidth - 1, $color);
		}
		
		$params = array('radius'		=> $this->radius,
						'orientation'	=> 'tl',
						'foreground'	=> $this->foreground,
						'background'	=> $this->background,
						'borderwidth'	=> $this->borderwidth,
						'bordercolor'	=> $this->bordercolor,
						'bgtransparent'	=> $this->bgtransparent,
						'btransparent'	=> $this->btransparent,
						'fgtransparent'	=> $this->fgtransparent,
						'antialias'		=> $this->antialias);
		
		$img = Rounded_Corner::create($params);
		imagecopy($this->image, $img, 0, 0, 0, 0, $this->radius, $this->radius);
		
		$img = Rounded_Tools::imageFlipVertical($img);
		imagecopy($this->image, $img, 0, $this->height - $this->radius, 0, 0, $this->radius, $this->radius);
		
		$img = Rounded_Tools::imageFlipHorizontal($img);
		imagecopy($this->image, $img, $this->width - $this->radius, $this->height - $this->radius, 0, 0, $this->radius, $this->radius);
		
		$img = Rounded_Tools::imageFlipVertical($img);
		imagecopy($this->image, $img, $this->width - $this->radius, 0, 0, 0, $this->radius, $this->radius);
		
		imagedestroy($img);
		
		return $this->image;
	}
	
	/**
	 * Create
	 *
	 * Method used as a factory for rectangle images.
	 * Offers a quick way to send parameters and return
	 * an image resource for output.
	 *
	 * @static
	 * @access	public
	 * @param	array	$params	Associative array of custom parameters:
	 *								- (See constructor docs for accepted values)
	 * @return	image resource of rounded rectangle
	 */
	public static function create($params)
	{
		$r = new Rounded_Rectangle($params);
		return $r->image();
	}
}
?>