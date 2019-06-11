<?php
/**
*
* @package phpBB Extension - Custom Header Info
* @copyright (c) 2018 orynider - http://mxpcms.sourceforge.net
* @version $Id: thumbnail.php,v 1.5 2008/08/30 22:23:00 orynider Exp
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, Smartor, FlorinCB] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

namespace orynider\customheadernfo\controller;
use orynider\customheadernfo\core\customheadernfo;

/**
 * Enter description here...
 *
 */
class thumbnail extends \orynider\customheadernfo\core\thumbnail
{
	/**
	* Handle image thumbnail
	*
	*/
	function handle_thumbnail()
	{
		// =======================================================
		// Request vars
		// =======================================================
		$info_id = $this->request->variable('info_id', 1);
		
		// get countries if is required and installed languages		
		$this->countries = $this->get_countries();
		
		// get packs installed and init some variables
		$this->packs = $this->load_lang_dirs($this->module_root_path);
		
		// =======================================================
		// =======================================================
		$sql = 'SELECT *
				FROM ' . $this->custom_header_info_table . '
				WHERE header_info_id = ' . $info_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		
		$header_info_name = $row['header_info_name'];
		$header_info_desc = $row['header_info_desc'];
		$header_info_longdesc = $row['header_info_longdesc'];
		$header_info_type = $row['header_info_type'];
		$header_info_dir = $row['header_info_dir']; /* i.e. ext/orynider/customheadernfo/language/movies/ */
		$header_info_font = $row['header_info_font'];
		$thumbnail_width = $thumbnail_height = 0;
		$db_width = $row['header_info_pic_width'];
		$db_height = $row['header_info_pic_height'];
		
		$header_info_title_font_size = $row['header_info_title_pixels'];
		$header_info_desc_font_size = $row['header_info_desc_pixels'];
		
		$header_info_font_size = !empty($row['header_info_desc_pixels']) ? $row['header_info_desc_pixels'] : $row['header_info_title_pixels'];
		
		// populate entries (all lang keys)
		$this->language_from = (isset($this->config['default_lang'])) ? $this->config['default_lang'] : $this->user->lang['USER_LANG'];
		$this->language_into = (isset($this->user->data['user_lang'])) ? $this->user->data['user_lang'] : $this->language_from;
		// Replace language with our request language
		$this->language_into = $this->request->variable('lang', $this->language_into); //$this->language_into = 'sy_lat';
		$this->language_into = is_file($this->module_root_path . customheadernfo::MULTILANG_DIR . $this->language_into . '/' . $header_info_dir . 'common.' . $this->php_ext) ? $this->language_into : $this->language_from;
		$this->language_into = is_file($this->module_root_path . customheadernfo::MULTILANG_DIR . $this->language_into . '/' . $header_info_dir . 'common.' . $this->php_ext) ? $this->language_into : 'en';
		$this->entries = $this->load_lang_file($this->module_root_path . customheadernfo::MULTILANG_DIR . $this->language_into . '/' . $header_info_dir . 'common.' . $this->php_ext);
		
		$header_info_title_colour		= isset($row['header_info_title_colour']) ? $row['header_info_title_colour'] : '';
		$header_info_title_colour_1	= isset($row['header_info_title_colour']) ? $this->get_gradient_colour($row['header_info_title_colour'], 1) : '';
		$header_info_title_colour_2	= isset($row['header_info_title_colour']) ? $this->get_gradient_colour($row['header_info_title_colour'], 2) : '';
		$header_info_desc_colour	= isset($row['header_info_desc_colour']) ? $row['header_info_desc_colour'] : '';
		$header_info_desc_colour_1	= isset($row['header_info_desc_colour']) ? $this->get_gradient_colour($row['header_info_desc_colour'], 1) : '';
		$header_info_desc_colour_2	= isset($row['header_info_desc_colour']) ? $this->get_gradient_colour($row['header_info_desc_colour'], 2) : '';
		
		$i = 0;
		$pic_title = array();
		$pic_desc = array();
		srand ((float) microtime() * 10000000);
		
		if ((count($this->entries) == 0) || ($header_info_type != 'language')) 
		{
			$l_keys[0] = $header_info_name;
			$l_values[0] = $header_info_desc;
			
			$l_keys[1] = $header_info_name;
			$l_values[1] = $header_info_longdesc;
			$j = rand(0, 1);
			$pic_title = $l_keys[$j];
			$pic_desc = $l_values[$j];
		}
		else
		{
			$i = count($this->entries) -1;
			//Overwrite ramdom in ext.php, for ex. ?rand=29 for $j = 29.
			$j = $this->request->variable('rand', rand(0, $i));
			$l_keys = array_keys($this->entries);
			$l_values = array_values($this->entries);
			$pic_title = $l_keys[$j];
			$pic_desc = $l_values[$j];
		}

		$header_info_image	= $row['header_info_image'];

		$this->db->sql_freeresult($result);

		//
		// Output all
		//
		$board_url = generate_board_url();
		$phpbb_url = $board_url . '/';

		// Replace path by your own font path
		$font_name = $this->request->variable('font', $header_info_font);
		$font = $this->module_root_path . "assets/fonts/" . $font_name . '.ttf';
		
		/* fonts overwrite for other languages 
		We only need this if there is no font for our language
		uncomment this if you add incompatible language */
		switch($this->language_into)
		{
			case 'sy_lat':
				$font = $this->module_root_path . "assets/fonts/SyrCOMEdessa.ttf";
			break;
			
			default:
			break;
		}
		/* fonts overwrite for other languages */
		
		if (!is_file($font))
		{
			$font = $this->module_root_path . "assets/fonts/" . $header_info_font; //. '.ttf';
		}
		
		$header_info_image = $header_info_image ? str_replace('_info.', '_bg.', $header_info_image) : $this->module_root_path . "styles/prosilver/theme/images/banners/custom_header_bg.png";
		$header_info_image = str_replace(basename($header_info_image), $this->request->variable('image', basename($header_info_image)), $header_info_image);
		$header_info_image	= ($this->config['board_disable'] || ($row['header_info_id'] == 0)) ? generate_board_url() . '/ext/orynider/customheadernfo/styles/prosilver/theme/images/banners/under_construction.gif' : $header_info_image;
		$header_info_image = str_replace(array('.php', '.pal'), '.png', $header_info_image);
		
		//user logged in ? user has custom style ?
		if (($this->user->data['user_id'] !== 1))
		{
			$this->default_style = (isset($this->config['default_style'])) ? $this->config['default_style'] : $this->user->data['user_style'];
			$this->user_style = (isset($this->user->data['user_style'])) ? $this->user->data['user_style'] : $this->default_style;
			
			if (is_file(str_replace('prosilver', $this->user_style, $header_info_image)))
			{
				$header_info_image = str_replace('prosilver', $this->user_style, $header_info_image);
			}
		}
		/* http://localhost/Rhea/ext/orynider/customheadernfo/styles/prosilver/theme/images/banners/custom_header_bg.png */
		$src_path = str_replace(array('localhost' . $this->config['script_path'], $phpbb_url), $this->root_path, $header_info_image);
		$pic_filename = basename($src_path);
		$pic_filetype = strtolower(substr($pic_filename, strlen($pic_filename) - 4, 4)); 
		$pic_ext = str_replace('jpg', 'jpeg', substr(strrchr($pic_filename, '.'), 1));
		$file_header = 'Content-type: image/' . $pic_ext;
		$pic_title_reg = preg_replace("/[^A-Za-z0-9]/", "_", $pic_title);
		$read_function = 'imagecreatefrom'.$pic_ext;

		/* image id */
		$im = $read_function($src_path); 
		
		/* use php_GD2 GetImageSize() */
		if (function_exists('GetImageSize'))
		{
			$pic_size = GetImageSize($src_path);
			$pic_width = isset($pic_size[0]) ? $pic_size[0] : $db_width;
			$pic_height = isset($pic_size[1]) ? $pic_size[1] : $db_height;
		}
		else
		{
			$pic_width = $db_width;
			$pic_height = $db_height;
		}

		$resize_width = $this->request->variable('resize_width', $pic_width); 
		$resize_height = $this->request->variable('resize_height', $pic_height);

		// Create some colors
		$white = ImageColorAllocate($im, 255, 255, 255); //#ffffff
		$grey = ImageColorAllocate($im, 128, 128, 128); //#808080
		
		// integer representation of the color black (rgb: 0,0,0)
		$black = $background = ImageColorAllocate($im, 0, 0, 0); //#000000
		
		//blue: #0000ff or #0c6a99 or #066c9f
		$blue = ImageColorAllocate($im, 6, 108, 159); //#066c9f
		$blure = ImageColorAllocate($im, 29, 36, 52); //#1d2434
		$rand = ImageColorAllocate($im, rand(180, 255), rand(180, 255), rand(180, 255));

		/* int ImageColorAllocate(resource $image, int $red, int $green, int $blue) */
		$title_colour = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_title_colour, 'r'), $this->get_hexdec_colour($header_info_title_colour, 'g'), $this->get_hexdec_colour($header_info_title_colour, 'b'));
		$title_colour_1 = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_title_colour_1, 'r'), $this->get_hexdec_colour($header_info_title_colour_1, 'g'), $this->get_hexdec_colour($header_info_title_colour_1, 'b'));
		$title_colour_2 = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_title_colour_2, 'r'), $this->get_hexdec_colour($header_info_title_colour_2, 'g'), $this->get_hexdec_colour($header_info_title_colour_2, 'b'));

		$desc_colour = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_desc_colour, 'r'), $this->get_hexdec_colour($header_info_desc_colour, 'g'), $this->get_hexdec_colour($header_info_desc_colour, 'b'));
		$desc_colour_1 = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_desc_colour_1, 'r'), $this->get_hexdec_colour($header_info_desc_colour_1, 'g'), $this->get_hexdec_colour($header_info_desc_colour_1, 'b'));
		$desc_colour_2 = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_desc_colour_2, 'r'), $this->get_hexdec_colour($header_info_desc_colour_2, 'g'), $this->get_hexdec_colour($header_info_desc_colour_2, 'b'));

		$dimension_filesize = @FileSize($src_path);

		$dimension_font = 1;
		$dimension_string = intval($pic_width) . 'x' . intval($pic_height) . '(' . intval($dimension_filesize / 1024) . 'KB)';

		/* removing the black from the placeholder */
		ImageColorTransparent($im, $background);
		
		/* turning on alpha channel information saving 
		to ensure the full range of transparency is preserved */
		ImageSaveAlpha($im, true);

		$dimension_height = ImageFontHeight($dimension_font);
		$dimension_width = ImageFontWidth($dimension_font) * utf8_strlen($pic_desc, 'utf-8');
		$dimension_x = (($thumbnail_width - $dimension_width) / 2) - utf8_strlen($pic_desc, 'utf-8');
		$dimension_y = $thumbnail_height + ((16 - $dimension_height) / 2);

		/* ideea: https://stackoverflow.com/a/8187653/9369810
		credit: https://stackoverflow.com/users/1046402/jeff-wilbert */
		$middle_title = utf8_strrpos(utf8_substr($pic_title, 0, floor(utf8_strlen($pic_title) / 2 )), ' ') + 1;
		$middle_desc = utf8_strrpos(utf8_substr($pic_desc, 0, floor(utf8_strlen($pic_desc) / 2 )), ' ') + 1;

		$pic_title = $this->convert_encoding($pic_title);
		$pic_desc = $this->convert_encoding($pic_desc); 

		$pic_title1 = $this->convert_encoding(utf8_substr($pic_title, 0, $middle_title)); 
		$pic_title2 = $this->convert_encoding(utf8_substr($pic_title, $middle_title));

		$middle_title1 = utf8_strrpos(utf8_substr($pic_title1, 0, floor(utf8_strlen($pic_title1) / 2 )), ' ') + 1;
		$middle_title2 = utf8_strrpos(utf8_substr($pic_title2, 0, floor(utf8_strlen($pic_title2) / 2 )), ' ') + 1;

		//Title Split Level 2
		$pic_title1_1 = $this->convert_encoding(utf8_substr($pic_title1, 0, $middle_title1));
		$pic_title1_2 = $this->convert_encoding(utf8_substr($pic_title1, $middle_title1));
		$pic_title2_1 = $this->convert_encoding(utf8_substr($pic_title2, 0, $middle_title2));
		$pic_title2_2 = $this->convert_encoding(utf8_substr($pic_title2, $middle_title2));

		$pic_desc1 = $this->convert_encoding(utf8_substr($pic_desc, 0, $middle_desc)); 
		$pic_desc2 = $this->convert_encoding(utf8_substr($pic_desc, $middle_desc)); 

		$middle_desc1 = utf8_strrpos(utf8_substr($pic_desc1, 0, floor(utf8_strlen($pic_desc1) / 2 )), ' ') + 1;
		$middle_desc2 = utf8_strrpos(utf8_substr($pic_desc2, 0, floor(utf8_strlen($pic_desc2) / 2 )), ' ') + 1;

		//Description Split Level 2
		$pic_desc1_1 = $this->convert_encoding(utf8_substr($pic_desc1, 0, $middle_desc1));
		$pic_desc1_2 = $this->convert_encoding(utf8_substr($pic_desc1, $middle_desc1));
		$pic_desc2_1 = $this->convert_encoding(utf8_substr($pic_desc2, 0, $middle_desc2));
		$pic_desc2_2 = $this->convert_encoding(utf8_substr($pic_desc2, $middle_desc2));

		$resize_height = (($header_info_font_size * utf8_strlen($pic_desc, 'utf-8')) >= $resize_width) ? $resize_height + (2 * $header_info_font_size) : ((!empty($pic_desc2_2)) ? $resize_height + $header_info_font_size  : $resize_height);

		if (((6 * utf8_strlen($pic_title, 'utf-8')) >= $resize_width) || (utf8_strlen($pic_title2, 'utf-8') >= $resize_width))
		{
			$resize_height = $resize_height + $header_info_font_size;
			$dimension_title_y = 0;
		}
		else
		{
			$dimension_title_y = 8;
		}
		
		if (((5 * utf8_strlen($pic_title, 'utf-8')) >= $resize_width) || (utf8_strlen($pic_title2, 'utf-8') >= $resize_width))
		{
			$resize_height = $resize_height + $header_info_font_size;
			$dimension_title_y = 0;
		}
		else
		{
			$dimension_title_y = 6;
		}

		$pic_offset_desc1 = !empty($pic_desc1_2) ? utf8_strlen($pic_desc1_2, 'utf-8') - $resize_height + $header_info_title_font_size + 14 : 0;
		$pic_offset_desc2 =!empty($pic_desc2_2) ? utf8_strlen($pic_desc2_2, 'utf-8') - $resize_height + $header_info_title_font_size + 9 : 0;
		$dimension_desc_y = 1;
		if ((($pic_offset_desc1 * utf8_strlen($pic_desc1, 'utf-8')) >= $resize_width) && (($pic_offset_desc2 * utf8_strlen($pic_desc2, 'utf-8')) >= $resize_width))
		{
			//Description Split Level 3
			if (($pic_offset_desc1 * utf8_strlen($pic_desc2_1, 'utf-8')) >= $resize_width)
			{
				$resize_height = $resize_height + (2 * $header_info_font_size);
				$dimension_desc_y = 6;
				$dimension_title_y = $dimension_title_y + 2;
			}
			else
			{
				$resize_height = $resize_height + $header_info_font_size;
				$dimension_desc_y = 6;
			}
		}
		
		if ((($resize_width !== 0) && ($resize_width !== $pic_width)) || ($dimension_desc_y == 6) || (!empty($pic_desc2_2) && ($resize_width !== $pic_width)))
		{
			$resize = ($this->gdVersion() == 1) ? ImageCreate($resize_width, $resize_height) : ImageCreateTrueColor($resize_width, $resize_height);
			$resize_function = ($this->gdVersion() == 1) ? 'imagecopyresized' : 'imagecopyresampled';

			$resize_function($resize, $im, 0, 0, 0, 0, $resize_width, $resize_height, $pic_width, $pic_height);
			ImageDestroy($im);
			$pic_width = $resize_width;
			$pic_height = $resize_height;
			
			$im = $resize;
			
			ImageFilledRectAngle($im, 0, 0, $resize_width, $pic_height, $white);
			ImageColorTransparent($im, $white);
			
			/* We keep this code here commented
			Uncomment to use Antialias etc. * /
			if (function_exists('imageantialias'))
			{
				ImageAntialias($im, true);
			}
			ImageAlphaBlending($im, false);
			/* We keep this code here commented */
			
			/* removing the black from the placeholder */
			if (function_exists('imagesavealpha'))
			{
				ImageSaveAlpha($im, true);
			}
		}
		else
		{
			if (function_exists('imageantialias'))
			{
				ImageAntialias($im, true);
			}
			
			ImageAlphaBlending($im, false);
			
			/* removing the black from the placeholder * /
			ImageColorTransparent($im, $white);
			/* removing the black from the placeholder */
			
			if (function_exists('imagesavealpha'))
			{
				ImageSaveAlpha($im, true);
			}
		}

		Header($file_header);

		Header("Expires: Mon, 1, 1999 05:00:00 GMT");
		Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		Header("Cache-Control: no-store, no-cache, must-revalidate");
		Header("Cache-Control: post-check=0, pre-check=0", false);
		Header("Pragma: no-cache");

		/* return with no uppercase if patern not in string */
		if (strpos($pic_title, ',') !== false)
		{
			$header_info_title_font_size = $header_info_title_font_size - 2;
		}

		if (((5 * utf8_strlen($pic_title, 'utf-8')) >= $resize_width) || (utf8_strlen($pic_title2, 'utf-8') >= $resize_width))
		{
			ImageTtfText($im, $header_info_title_font_size, 0, 12, $dimension_y + $dimension_title_y + 30, $title_colour, $font, $pic_title1);
			$dimension_y = $dimension_y + $header_info_font_size;
			ImageTtfText($im, $header_info_title_font_size, 0, 12, $dimension_y + $dimension_title_y + 30, $title_colour, $font, $pic_title2);
		}	
		if (((6 * utf8_strlen($pic_title, 'utf-8')) >= $resize_width) || (utf8_strlen($pic_title2, 'utf-8') >= $resize_width))
		{
			ImageTtfText($im, $header_info_title_font_size, 0, 12, $dimension_y + $dimension_title_y + 30, $title_colour, $font, $pic_title1);
			$dimension_y = $dimension_y + $header_info_font_size;
			ImageTtfText($im, $header_info_title_font_size, 0, 12, $dimension_y + $dimension_title_y + 30, $title_colour, $font, $pic_title2);
		}
		else
		{
			// Add some shadow to the text
			ImageTtfText($im,  $header_info_title_font_size, 0, 11, $dimension_y + 28, $grey, $font, $pic_title);

			// Add the text
			ImageTtfText($im, $header_info_title_font_size, 0, 10, $dimension_y + 29, $title_colour, $font, $pic_title);
		}
		
		//4 x 138 >= 458
		if (((6 * utf8_strlen($pic_desc, 'utf-8')) >= $resize_width) || (utf8_strlen($pic_desc2, 'utf-8') >= $resize_width))
		{
			if ((($pic_offset_desc1 * utf8_strlen($pic_desc1, 'utf-8')) >= $resize_width) && (($pic_offset_desc2 * utf8_strlen($pic_desc2, 'utf-8')) >= $resize_width))
			{
				//Description Split Level 3
				$middle_desc1_1 = utf8_strrpos(utf8_substr($pic_desc1_1, 0, floor(utf8_strlen($pic_desc1_1) / 2 )), ' ') + 1;
				$middle_desc1_2 = utf8_strrpos(utf8_substr($pic_desc1_2, 0, floor(utf8_strlen($pic_desc1_2) / 2 )), ' ') + 1;

				//Description Split Level 3
				$pic_desc1_1_1 = $this->convert_encoding(utf8_substr($pic_desc1_1, 0, $middle_desc1_1));
				$pic_desc1_1_2 = $this->convert_encoding(utf8_substr($pic_desc1_1, $middle_desc1_1));
				$pic_desc1_2_1 = $this->convert_encoding(utf8_substr($pic_desc1_2, 0, $middle_desc1_2));
				$pic_desc1_2_2 = $this->convert_encoding(utf8_substr($pic_desc1_2, $middle_desc1_2));
				
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + $dimension_desc_y, $desc_colour, $font, $pic_desc1_1);
				//ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 8 + $header_info_font_size, $desc_colour, $font, $pic_desc1_1_2);
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + $dimension_desc_y + $header_info_font_size, $desc_colour, $font, $pic_desc1_2);
				//ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 8 + (2 * $header_info_font_size), $desc_colour, $font, $pic_desc1_2_2);
				
				//Description Split Level 3
				$middle_desc2_1 = utf8_strrpos(utf8_substr($pic_desc2_1, 0, floor(utf8_strlen($pic_desc2_1) / 2 )), ' ') + 1;
				$middle_desc2_2 = utf8_strrpos(utf8_substr($pic_desc2_2, 0, floor(utf8_strlen($pic_desc2_2) / 2 )), ' ') + 1;

				//Description Split Level 3
				$pic_desc2_1_1 = $this->convert_encoding(utf8_substr($pic_desc2_1, 0, $middle_desc2_1));
				$pic_desc2_1_2 = $this->convert_encoding(utf8_substr($pic_desc2_1, $middle_desc2_1));
				$pic_desc2_2_1 = $this->convert_encoding(utf8_substr($pic_desc2_2, 0, $middle_desc2_2));
				$pic_desc2_2_2 = $this->convert_encoding(utf8_substr($pic_desc2_2, $middle_desc2_2));
				
				if (($pic_offset_desc1 * utf8_strlen($pic_desc2_1, 'utf-8')) >= $resize_width)
				{
					ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2_1_1);
					$dimension_y = $dimension_y + $header_info_font_size;
					ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2_1_2);
					$dimension_y = $dimension_y + $header_info_font_size;
				}
				else
				{
					ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2_1);
					$dimension_y = $dimension_y + $header_info_font_size;
				}

				if (($pic_offset_desc1 * utf8_strlen($pic_desc2_2, 'utf-8')) >= $resize_width)
				{
					ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2_2_1);
					$dimension_y = $dimension_y + $header_info_font_size;
					ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2_2_2);
					$dimension_y = $dimension_y + $header_info_font_size;
				}
				else
				{
					ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2_2);
				}
			}
			elseif (($pic_offset_desc1 * utf8_strlen($pic_desc1, 'utf-8')) >= $resize_width)
			{
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 8, $desc_colour, $font, $pic_desc1_1);
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 8 + $header_info_font_size, $desc_colour, $font, $pic_desc1_2);
			
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2);
			}
			elseif (($pic_offset_desc2 * utf8_strlen($pic_desc2, 'utf-8')) >= $resize_width)
			{
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 8, $desc_colour, $font, $pic_desc1);
			
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2_1);
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43 + $header_info_font_size, $desc_colour, $font, $pic_desc2_2);
			}
			else
			{
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 8, $desc_colour, $font, $pic_desc1);
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2);
			}
		}
		else
		{
			ImageTtfText($im, $header_info_font_size, 0, 10, $dimension_y + 10, $desc_colour, $font, $pic_desc);
		}

		$wm = !empty($this->language->lang($row['header_info_dir'])) ? $this->language->lang($row['header_info_dir']) : $dimension_filesize;
		
		/* Position watermark and place on image */
		switch($this->config['disp_watermark_at'])
		{
			case 0: // 1 top left
				$dest_x = 0;
				$dest_y = 0;
			break;

			case 1: // 2 top middle
				$dest_x = (($resize_width - utf8_strlen($wm, 'utf-8')) / 2);
				$dest_y = 0;
			break;

			case 2: // 3 top right
				$dest_x = $resize_width - utf8_strlen($wm, 'utf-8');
				$dest_y = 0;
			break;

			case 3: // 4 middle left
				$dest_x = 0;
				$dest_y = ($resize_width / 2) - ($header_info_font_size / 2);
			break;

			case 4: // 5 middle
				$dest_x = ($resize_width / 2 ) - (utf8_strlen($wm, 'utf-8') / 2);
				$dest_y = ($resize_height / 2 ) - $header_info_font_size;
			break;

			case 5: // 6 middle right
				$dest_x = $resize_width - utf8_strlen($wm, 'utf-8') - 100;
				$dest_y = ($resize_height / 2) - ($dimension_y + $header_info_font_size / 2);
			break;

			case 6: // 7 bottom left
				$dest_x = 0;
				$dest_y = $resize_height - $header_info_font_size;
			break;

			case 7: // 8 bottom middle
				$dest_x = (($resize_width - utf8_strlen($wm, 'utf-8')) / 2);
				$dest_y = $resize_height - $header_info_font_size;
			break;

			case 8: // 9 bottom right
				$dest_x = $resize_width - 60 - utf8_strlen($wm, 'utf-8');
				$dest_y =  $resize_height - $header_info_font_size;
			break;

			default:
			break;
		}

		//****************************************************************************
		// How add watermark at position
		//   Usage : WatermarkPos(Filename of the 24-bit PNG watermark file,
		//										position as 1 to 9 matrix,
		//										1		2		3
		//										4		5		6
		//										7		8		9
		//										maxsize as percentage,
		//										transition is the transparency level to be applied on the watermark)
		//   Returns : true on success and false on fail
		//****************************************************************************
		if ($this->config['use_watermark'] == 1)
		{
			ImageTtfText($im, $header_info_font_size, 0,  $dest_x, $dest_y, $desc_colour, $font, $wm);
		}
		ImagePNG($im);
		ImageDestroy($im);
	}
}

