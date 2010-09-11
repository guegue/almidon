<?php
/**
 * Rounded PHP, Rounded corners made easy.
 *
 * Rounded_Corner class
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


# Require RGB and Tools classes
require_once 'Rounded/RGB.php';
require_once 'Rounded/Tools.php';


/**
 * Class used to create rounded corner images with optional borders
 *
 * Use:
 *  $params = array('radius' => 15,
 * 					'orientation' => 'bl',
 *					'borderwidth' => 2);
 *  $img = Rounded_Corner::create($params);
 *  header('Content-Type: image/png');
 *  imagepng($img);
 */
class Rounded_Corner
{
	private $image,						# image resource
			$radius = 10,				# radius of corner
			$orientation = 'tl',		# orientation of corner
			$foreground = 'CCCCCC',		# color of corner
			$background = 'FFFFFF',		# color of background
			$borderwidth = 0,			# width of border
			$bordercolor = '000000',	# color of border (if border width > 0)
			$bgtransparent = false,		# transparent background flag
			$btransparent = false,		# transparent border flag
			$fgtransparent = false,		# transparent foreground flag
			$antialias = true;			# antialias flag
	
	/**
	 * Rounded_Corner
	 *
	 * Constructor for the Corner object.
	 *
	 * @access	public
	 * @param	array	$params	Associative array of custom parameters:
	 *								- radius		: {1, 2, ... , n}
	 *								- orientation	: {'tl', 'tr', 'br', 'bl'}
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
	public function Rounded_Corner($params)
	{
		if (is_array($params))
			foreach($params as $param => $value)
				$this->{$param} = $value;
		
		$this->radius = max(intval($this->radius), 1);
		$this->borderwidth = min(max(intval($this->borderwidth), 0), $this->radius);
		$this->orientation = strtolower($this->orientation);
	}
	
	/**
	 * Image
	 *
	 * Used to build the actual image resource.
	 *
	 * @access	public
	 * @return	image resource for final rounded corner
	 */
	public function image()
	{
		$this->image = imagecreatetruecolor($this->radius, $this->radius);
		imagealphablending($this->image, !$this->bgtransparent);
		
		$rgb = new Rounded_RGB($this->bgtransparent ? (!$this->btransparent && $this->borderwidth > 0 ? $this->bordercolor : $this->foreground) : $this->background);
		
		$color = imagecolorallocatealpha($this->image, $rgb->red, $rgb->green, $rgb->blue, $this->bgtransparent ? 127 : 0);
		imagefilledrectangle($this->image, 0, 0, $this->radius - 1, $this->radius - 1, $color);
		
		if ($this->bgtransparent && ($this->btransparent || $this->borderwidth == 0) && ($this->fgtransparent || $this->borderwidth >= $this->radius))
			return $this->image;
		
		if ($this->borderwidth > 0 && !($this->bgtransparent && $this->btransparent)) {
			imagealphablending($this->image, !$this->btransparent);
			
			$rgb = new Rounded_RGB($this->btransparent ? $this->background : $this->bordercolor);
			$this->draw($this->radius, $rgb, $this->antialias, $this->btransparent);
		}
		
		if ($this->borderwidth < $this->radius && !($this->fgtransparent && $this->btransparent && $this->borderwidth > 0)) {
			if ($this->borderwidth > 0 && $this->btransparent) {
				imagealphablending($this->image, false);
				$rgb = new Rounded_RGB('FFF');
				$this->draw($this->radius - $this->borderwidth, $rgb, false);
			}
			
			imagealphablending($this->image, !($this->fgtransparent || ($this->btransparent && $this->borderwidth > 0)));
			
			$rgb = new Rounded_RGB($this->fgtransparent ? (!$this->btransparent && $this->borderwidth > 0 ? $this->bordercolor : $this->background) : $this->foreground);
			$this->draw($this->radius - $this->borderwidth, $rgb, $this->antialias, $this->fgtransparent);
		}
		
		switch ($this->orientation) {
			case 'br' :
			case 'rb' :
				break;
			case 'bl' :
			case 'lb' :
				$this->image = Rounded_Tools::imageFlipHorizontal($this->image);
				break;
			case 'tr' :
			case 'rt' :
				$this->image = Rounded_Tools::imageFlipVertical($this->image);
				break;
			case 'tl' :
			case 'lt' :
			default :
				$this->image = Rounded_Tools::imageFlipBoth($this->image);
				break;
		}
		
		return $this->image;
	}
	
	/**
	 * Draw
	 *
	 * Draw an anti-aliased arc on an image.
	 * Always draws quadrant IV of a circle with center
	 * positioned at (0,0).
	 *
	 * @access	private
	 * @param	int		$r		Value for the radius of the arc
	 * @param	RGB		$rgb	RGB object for color information
	 * @param	bool	$aa		Toggle antialiasing
	 * @param	bool	$mask	Toggle pixel addition or subtraction (masking)
	 * @return	void
	 */
	private function draw($r, $rgb, $aa = true, $mask = false)
	{
		for ($x = 0; $x < $r; $x++)
			for ($y = ceil($this->loc($x, $r)) - 1; $y > -1; $y--) {
				$alpha = $this->computeAlpha($x, $y, $r);
				$alpha = $mask ? ($aa ? 127 - $alpha : 127) : ($aa ? $alpha : 0);
				
				$color = imagecolorallocatealpha($this->image, $rgb->red, $rgb->green, $rgb->blue, $alpha);
				
				if ($mask == true) {
					if ($alpha == 127) {
						imageline($this->image, $x, $y, $x, 0, $color);
						break;
					}
				} else {
					if ($alpha == 0) {
						imageline($this->image, $x, $y, $x, 0, $color);
						break;
					}
				}
				
				imagesetpixel($this->image, $x, $y, $color);
			}
	}
	
	/**
	 * ComputeAlpha
	 *
	 * Determines the alpha value to apply to
	 * a specific pixel
	 *
	 * @access	private
	 * @param	int		$x	x-coordinate for the pixel
	 * @param	int		$y	y-coordinate for the pixel
	 * @param	int		$r	radius of the arc
	 * @return	int		value for pixel alpha (0 <= a <= 127)
	 */
	private function computeAlpha($x, $y, $r)
	{
		if ($this->isInside($x + 1, $y + 1, $r))
			return 0;
		
		$x_a = min($x + 1, $this->loc($y, $r));
		$x_b = max($x, $this->loc($y + 1, $r));
		return round(127 * (1 - $this->area($x_a, $r) + $this->area($x_b, $r) - $x_b + $x + $y * ($x_a - $x_b)));
	}
	
	/**
	 * Area
	 *
	 * Given a value for x = n, computes the area under a circular arc
	 * from x = 0 -> n, with the cirle centerd at the orgin
	 *
	 * @access	private
	 * @param	int		$x	x-coordinate for the pixel
	 * @param	int		$r	radius of the arc
	 * @return	float	area under the arc
	 */
	private function area($x, $r)
	{
		return ($x * $this->loc($x, $r) + $r * $r * asin($x / $r)) / 2;
	}
	
	/**
	 * IsInside
	 *
	 * Helper method to determine if a coordinate lies inside
	 * of the arc.
	 *
	 * @access	private
	 * @param	int		$x	x-coordinate
	 * @param	int		$y	y-coordinate
	 * @param	int		$r	radius of the arc
	 * @return	bool	true if coordinate lies inside bounds of arc
	 */
	private function isInside($x, $y, $r)
	{
		return $x * $x + $y * $y <= $r * $r;
	}
	
	/**
	 * LawOfCosines (loc)
	 *
	 * Used to calculate length of opposite side
	 * of a right triangle, given the length of the
	 * hypotenuse and one side.
	 *
	 * @access	private
	 * @param	int		$r		Value for the radius of the arc
	 * @param	int		$xy		Value for either side of the right triangle
	 * @return	int		Length of the unknown side
	 */
	private function loc($xy, $h)
	{
		return sqrt($h * $h - $xy * $xy);
	}
	
	/**
	 * Create
	 *
	 * Method used as a factory for corner images.
	 * Offers a quick way to send parameters and return
	 * an image resource for output.
	 *
	 * @static
	 * @access	public
	 * @param	array	$params	Associative array of custom parameters:
	 *								- (See constructor docs for accepted values)
	 * @return	image resource for generated rounded corner
	 */
	public static function create($params)
	{
		$c = new Rounded_Corner($params);
		return $c->image();
	}
}
?>