<?php

class AdminTexy extends Texy {

    public function __construct() {
	parent::__construct();

	// output
	$this->setOutputMode(self::HTML4_TRANSITIONAL);
	$this->htmlOutputModule->removeOptional = false;
	self::$advertisingNotice = false;

	// headings
	$this->headingModule->balancing = TexyHeadingModule::FIXED;

	// phrases
	$this->allowed['phrase/ins'] = true;   // ++inserted++
	$this->allowed['phrase/del'] = true;   // --deleted--
	$this->allowed['phrase/sup'] = true;   // ^^superscript^^
	$this->allowed['phrase/sub'] = true;   // __subscript__
	$this->allowed['phrase/cite'] = true;   // ~~cite~~
	$this->allowed['deprecated/codeswitch'] = true; // `=code
	// images
	//$this->imageModule->fileRoot = __DIR__ . "/images";
	//$this->imageModule->root = "images/";
	//$this->imageModule->fileRoot = __DIR__ . "/";
	//$this->imageModule->root = "./";
	// přidávání youtube.com, stream.cz videa a flash
	$this->addHandler('image', array(__CLASS__, 'youtubeHandler'));
	$this->addHandler('image', array(__CLASS__, 'streamHandler'));
	$this->addHandler('image', array(__CLASS__, 'flashHandler'));
	// spojování textu v odstavcích po enteru
	$this->mergeLines = false;
	// povolení a nastavení emotikonů
	$this->allowed['emoticon'] = true;
	$this->emoticonModule->root = './texyla/emoticons/texy/';
	$this->emoticonModule->fileRoot = ROOT_WEBU . '/texyla/emoticons/texy/'; //dirname(__FILE__);
	$this->emoticonModule->icons = array(
	':-)' => 'smile.gif',
	':-(' => 'sad.gif',
	';-)' => 'wink.gif',
	':-D' => 'biggrin.gif',
	'8-O' => 'eek.gif',
	'8-)' => 'cool.gif',
	':-?' => 'confused.gif',
	':-x' => 'mad.gif',
	':-P' => 'razz.gif',
	':-|' => 'neutral.gif'
	);
    }

    /**
     * User handler for images
     *
     * @param TexyHandlerInvocation  handler invocation
     * @param TexyImage
     * @param TexyLink
     * @return TexyHtml|string|FALSE
     */
    public static function youtubeHandler($invocation, $image, $link) {
	$parts = explode(':', $image->URL);
	if (count($parts) !== 2)
	    return $invocation->proceed();

	switch ($parts[0]) {
	    case 'youtube':
		$video = htmlSpecialChars($parts[1]);
		$code = '<iframe width="425" height="349" src="http://www.youtube.com/embed/' . $video . '" frameborder="0" allowfullscreen></iframe>';
		$texy = $invocation->getTexy();
		return $texy->protect($code, Texy::CONTENT_BLOCK);
	}

	return $invocation->proceed();
    }

    /**
     * User handler for images
     *
     * @param TexyHandlerInvocation  handler invocation
     * @param TexyImage
     * @param TexyLink
     * @return TexyHtml|string|FALSE
     */
    public static function flashHandler($invocation, $image, $link) {
	$texy = $invocation->getTexy();

	if (substr($image->URL, -4) === '.swf') {  // accepts only *.swf
	    $movie = Texy::prependRoot($image->URL, $texy->imageModule->root);

	    $dimensions =
		    ($image->width ? ' width="' . $image->width . '"' : '')
		    . ($image->height ? ' height="' . $image->height . '"' : '');

	    $movie = htmlSpecialChars($movie);
	    $altContent = $image->modifier->title ? "<p>" . htmlSpecialChars($image->modifier->title) . "</p>" : "";

	    // @see http://phpfashion.com/how-to-correctly-insert-a-flash-into-xhtml
	    $code = '
	<!--[if !IE]> -->
	<object type="application/x-shockwave-flash" data="' . $movie . '"' . $dimensions . '>
	<!-- <![endif]-->

	<!--[if IE]>
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ' . $dimensions . '
	codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0">
	<param name="movie" value="' . $movie . '" />
	<!--><!--dgx-->

	' . $altContent . '
	</object>
	<!-- <![endif]-->
	';
	    return $texy->protect($code, Texy::CONTENT_BLOCK);
	}

	return $invocation->proceed();
    }

    /**
     * User handler for images
     *
     * @param TexyHandlerInvocation  handler invocation
     * @param TexyImage
     * @param TexyLink
     * @return TexyHtml|string|FALSE
     */
    public static function streamHandler($invocation, $image, $link) {
	$parts = explode(':', $image->URL, 2);

	if (count($parts) != 2 || $parts[0] != "stream") {
	    return $invocation->proceed();
	}
	$parts[1] = htmlSpecialChars($parts[1]);
	$parts[1] = trim($parts[1]);
	$url = 'http://www.stream.cz/object/' . $parts[1];
	$width = $image->width ? $image->width : 450;
	$height = $image->height ? $image->height : 354;
	$dimensions = 'width="' . $width . '" height="' . $height . '"';

	$code = '`<object ' . $dimensions . '>' .
		'<param name="movie" value="' . $url . '">' .
		'<param name="allowfullscreen" value="true">' .
		'<param name="allowscriptaccess" value="always">' .
		'<param name="wmode" value="transparent">' .
		'<embed src="' . $url . '" type="application/x-shockwave-flash"' .
		'wmode="transparent" allowfullscreen="true" ' .
		'allowscriptaccess="always" ' . $dimensions . '></object>`';

	$texy = $invocation->getTexy();
	return $texy->protect($code, Texy::CONTENT_BLOCK);
    }

}

/**
 * Texyla nakonfigurovaná pro forum
 */
class ForumTexy extends Texy {

    public function __construct() {
	parent::__construct();

	// output
	$this->setOutputMode(self::HTML4_TRANSITIONAL);
	$this->htmlOutputModule->removeOptional = false;
	self::$advertisingNotice = false;

	// safe mode
	TexyConfigurator::safeMode($this);

	$this->allowed['heading/surrounded'] = false;
	$this->allowed['heading/underlined'] = false;
	$this->allowed['link/definition'] = false;
	$this->allowed['image/definition'] = false;

	// spojování textu v odstavcích po enteru
	$this->mergeLines = false;

	// povolení a nastavení emotikonů
	$this->allowed['emoticon'] = true;
	$this->emoticonModule->root = './texyla/emoticons/texy/';
	$this->emoticonModule->fileRoot = dirname(__FILE__) . '/../texyla/emoticons/texy/'; //dirname(__FILE__);
	$this->emoticonModule->icons = array(
	':-)' => 'smile.gif',
	':-(' => 'sad.gif',
	';-)' => 'wink.gif',
	':-D' => 'biggrin.gif',
	'8-O' => 'eek.gif',
	'8-)' => 'cool.gif',
	':-?' => 'confused.gif',
	':-x' => 'mad.gif',
	':-P' => 'razz.gif',
	':-|' => 'neutral.gif'
	);
	// přidání target="_blank" k odkazům
	// $this->addHandler('phrase', array(__CLASS__, 'addTargetHandler'));
    }

    /**
     * @param TexyHandlerInvocation  handler invocation
     * @param string
     * @param string
     * @param TexyModifier
     * @param TexyLink
     * @return TexyHtml|string|FALSE
     */
    public static function addTargetHandler($invocation, $phrase, $content, $modifier, $link) {
	// vychozí zpracování Texy
	$el = $invocation->proceed();

	// ověř, že $el je objekt TexyHtml a že jde o element 'a'
	if ($el instanceof TexyHtml && $el->getName() === 'a') {
	    // uprav jej
	    $el->attrs['target'] = '_blank';
	}

	return $el;
    }

}