<?php

namespace App\Http\Controllers\VisualGroup;
use App\Http\Controllers\Controller;
use Less_Parser;
use Less_Cache;


class LessController extends Controller
{
	
	public function minify_css($input) {
	    if(trim($input) === "") return $input;
	    return preg_replace(
	        array(
	            // Remove comment(s)
	            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
	            // Remove unused white-space(s)
	            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
	            // Replace 0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%) with 0
	            '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
	            // Replace :0 0 0 0 with :0
	            '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
	            // Replace background-position:0 with background-position:0 0
	            '#(background-position):0(?=[;\}])#si',
	            // Replace 0.6 with .6, but only when preceded by :, ,, - or a white-space
	            '#(?<=[\s:,\-])0+\.(\d+)#s',
	            // Minify string value
	            '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
	            '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
	            // Minify HEX color code
	            '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
	            // Replace (border|outline):none with (border|outline):0
	            '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
	            // Remove empty selector(s)
	            '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
	        ),
	        array(
	            '$1',
	            '$1$2$3$4$5$6$7',
	            '$1',
	            ':0',
	            '$1:0 0',
	            '.$1',
	            '$1$3',
	            '$1$2$4$5',
	            '$1$2$3',
	            '$1:0',
	            '$1$2'
	        ),
	    $input);
	}
  public function Less(){

       	$parser = new Less_Parser();
        $parser->parseFile('LESS/Build.less');
        $css = app('App\Http\Controllers\VisualGroup\LessController')->minify_css($parser->getCss());

		$less_files = array( base_path().'/public/LESS/Build.less' =>  base_path().'/public/LESS');

		$options = ['cache_dir' => storage_path() . '/app'];
		$css_file_name = Less_Cache::Get( $less_files, $options );
        $compiled = file_get_contents( storage_path() . '/app/' . $css_file_name );
        $compiled = app('App\Http\Controllers\VisualGroup\LessController')->minify_css($compiled);
        return response($compiled, 200)->header('Content-Type', 'text/css');

    }
}