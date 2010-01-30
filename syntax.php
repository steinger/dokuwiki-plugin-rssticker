<?php
/**
 * Plugin: RSS Ticker (Ajax invocation)
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Marcel Steinger <sourcecode@steinger.ch>
 */
 
// must be run within DokuWiki
if(!defined('DOKU_INC')) die();
 
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once DOKU_PLUGIN.'syntax.php';
 
/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_rssticker extends DokuWiki_Syntax_Plugin {
 
    function getType() { return 'substition'; }
    function getSort() { return 314; }
 
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('{{rssticker>.*?}}',$mode,'plugin_rssticker');
    }

    /**
          * Handle the match
          */
    function handle($match, $state, $pos, &$handler) {
            $match = substr($match,12,-2);
            list($title,$url,$cachetime,$css,$delay,$optionalswitch) = explode(',',$match);
            if ( empty($title)) { $title = "none";}
            if ( empty($url)) { $url = "none";}
            if ( empty($cachetime)) {$cachetime  = 600;}
            if ( empty($css)) {$css  = "rss";}
            if ( empty($delay)) {$delay = 3000;}
            if ( empty($optionalswitch)) {$optionalswitch = "date";}
            return array($title,$url,$cachetime,$css,$delay,$optionalswitch);
    }

    /**
          * Create output
          */
    function render($mode, &$renderer, $data) {
        if($mode == 'xhtml'){
            $linkscript = DOKU_URL."lib/plugins/rssticker/rssticker.js";
            $renderer->doc .= "<script src=$linkscript type='text/javascript'></script>";
            $renderer->doc .= "<div>";
            $renderer->doc .= "<script type='text/javascript' charset='UTF-8'>";
            $renderer->doc .= "document.write('<b></i>$data[0]:</i></b>')\n";
            $renderer->doc .= "new rssticker_ajax('$data[1]', $data[2], '$data[3]box', '$data[3]class', $data[4], '$data[5]')";
            $renderer->doc .= "</script></div>";
 
            return true;
        }
        return false;
    }
}
?>
