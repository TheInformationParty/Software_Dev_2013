<?php

class MarkDown {

	/**
	 * Returns the links in the input text. They should 
	 * @param  string $input the user's text to parse
	 * @return array        a collection of Links. Empty if no urls are found
	 */
	public static function parse_for_links($input) {
		//TODO: accept urls without www as well.
		$urlRegex = "\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))";
		$urlMarkdownRegex = "\[.+\]\<".$urlRegex."\>";

		$urlMarkdownMatches = array();
		preg_match_all("#".$urlMarkdownRegex."#",
			$input, $urlMarkdownMatches, PREG_PATTERN_ORDER);

		//make sure there are the same number of urls as matches
		$urls = array();
		preg_match_all("#".$urlRegex."#",
			$input, $urls, PREG_PATTERN_ORDER);		

		if(count($urlMarkdownMatches[0]) != count($urls[0])){
			throw new Exception("I found ".(count($urls[0]) - count($urlMarkdownMatches[0]))." URLs with improper syntax.<br/>To include a link, use &#91;name&#93;&#60;url&#62;<br/>Example: &#91;search&#93;&#60;www.google.com&#62;");
		}

		//try to create the links
		$links = array();
		foreach($urlMarkdownMatches[0] as $urlMarkdownMatch){
			//make some links (about damn time...)
			//find the positions of the ]< character combo. ([ is at beginning, > is at end)
			$divider = strpos($urlMarkdownMatch, "]<");
			$link = new Link();
			$link->name = substr($urlMarkdownMatch, 1, $divider - 1);
			$url = substr($urlMarkdownMatch, $divider + 2, strlen($urlMarkdownMatch) - $divider - 3);
			$urlPrefix = substr($url,0,7);
			if(strcmp($urlPrefix,"http://")){
				if(strcmp($urlPrefix,"https://")){
					$url = "http://".$url;
				}
			}
			$link->url = $url;
			
			$validation = $link->validate();
            if ($validation->passes()){
                $link->save();
				array_push($links, $link);
            } else {
                //send them the validation errors
                throw new Exception("Invalid link");
            }
		}

		return $links;
	}

	public static function replace_links($text){
		//find all the markdown links, then replace those with the html label.
		//TODO: accept urls without www as well.
		$urlRegex = "\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))";
		$urlMarkdownRegex = "\[.+\]\<".$urlRegex."\>";

		$urlMarkdownMatches = array();
		preg_match_all("#".$urlMarkdownRegex."#",
			$text, $urlMarkdownMatches, PREG_PATTERN_ORDER);

		// //make sure there are the same number of urls as matches
		// $urls = array();
		// preg_match_all("#".$urlRegex."#",
		// 	$input, $urls, PREG_PATTERN_ORDER);		

		// if(count($urlMarkdownMatches[0]) != count($urls[0])){
		// 	throw new Exception("I found ".(count($urls[0]) - count($urlMarkdownMatches[0]))." URLs with improper syntax.<br/>To include a link, use &#91;name&#93;&#60;url&#62;<br/>Example: &#91;search&#93;&#60;www.google.com&#62;");
		// }

		foreach($urlMarkdownMatches[0] as $urlMarkdownMatch){
			$divider = strpos($urlMarkdownMatch, "]<");
			//get the name
			$name = substr($urlMarkdownMatch, 1, $divider - 1);
			//get the url
			$url = substr($urlMarkdownMatch, $divider + 2, strlen($urlMarkdownMatch) - $divider - 3);
			$urlPrefix = substr($url,0,7);
			if(strcmp($urlPrefix,"http://")){
				if(strcmp($urlPrefix,"https://")){
					$url = "http://".$url;
				}
			}

			//find where $urlMarkdownMatch starts
			$text = str_replace($urlMarkdownMatch,'<a href="'.$url.'">'.$name.'</a>', $text);
		}

		return $text;
	}

}