<?php
require_once "PEAR.php";
require_once("HTML/Common2.php");
//generic html
/**
 * generic html: helps keep coding simple with a 
 * abstraction class like viewstate_html
 * 
 * @category   HTML
 * @package    generic html
 * @author Collin Visser
 */
class genhtml extends HTML_Common2{
	private $m_strTagName;
	private $m_strInnerHTML;
	
	function clean(){
		$this->_comment="";
		$this->m_strInnerHTML="";
		$this->m_strTagName="a";
		$this->_attributes = array();
	}
	function getInnerHTML(){
		return $this->m_strInnerHTML;
	}
	
	function setInnerHTML($NewInnerHTML){
		$this->m_strInnerHTML= $NewInnerHTML; 
	}
	
	function getTageName(){
		return $this->m_strTagName;
	}
	function setTagname($NewTageName){
		$this->m_strTagName = $NewTageName;
	}
	
	function toHtml(){		
		$strHtml ='';
		$tabs = $this->getIndent();
        $tab = $this->getOption('indent');
        $lnEnd = $this->getOption('linebreak');
		
		if($this->__comment){
			$strHtml .= $tabs . "<!-- ".$this->_comment." -->" . $lnEnd;
		}
		echo $this->getAttributesString($this->_attributes);
		$strHtml.= $tabs .'<'.$this->m_strTagName.' '.$this->getAttributes(true).' >'.$this->m_strInnerHTML.'</'.$this->m_strTagName.'>';
		return $strHtml;
	}
	public function __toString() {
       return $this->toHtml();
   }

	function __construct($TagName = "a",$attributes = null,$tabOffset = 0){
		$this->m_strTagName = $TagName;
		$this->m_strInnerHTML ="";	    
	    parent::__construct($attributes);	    
		$this->setIndentLevel($tabOffset);
	}
	
	public function __get($member) {
		return $this->getAttribute($member);
    }

    public function __set($member, $value) {
         $this->setAttribute($member,$value);
    }

}



?>