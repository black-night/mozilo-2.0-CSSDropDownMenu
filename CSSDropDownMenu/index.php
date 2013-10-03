<?php if(!defined('IS_CMS')) die();

/***************************************************************
*
* Plugin fuer moziloCMS, welches das Standard Menu durch ein DropDown Menu ersetzt 
* by blacknight - Daniel Neef
* 
***************************************************************/

class CSSDropDownMenu extends Plugin {

    /***************************************************************
    * 
    * Gibt den HTML-Code zurueck, mit dem die Plugin-Variable ersetzt 
    * wird.
    * 
    ***************************************************************/


    function getContent($value) {
        global $CMS_CONF;
        global $specialchars;
        global $syntax;    
        global $CatPage;    
        $dir = PLUGIN_DIR_REL."CSSDropDownMenu/";
        
       
        $syntax->insert_in_head($this->getHead());

        $result = "<div class=\"cssmenu\">";
		$result .= "<ul>";
		
		// Kategorienverzeichnis einlesen
		$CategoriesArray = $CatPage->get_CatArray();
		$CountCategoriesIndex = count($CategoriesArray)-1;			
		for ($i = 0; $i <= $CountCategoriesIndex; $i++) {	
			// Seitenverzeichnis einlesen	
			$PageArray = $CatPage->get_PageArray($CategoriesArray[$i]);
			$CountPageIndex = count($PageArray)-1;
			$result .= "<li".$this->getLiClass(($i == $CountCategoriesIndex),($CountPageIndex >= 0),$CatPage->is_Activ($CategoriesArray[$i],false)).">";
			if ($CatPage->get_Type($CategoriesArray[$i],false) == 'cat') {
				$result .= "<a href=\"#\">".$CatPage->get_HrefText($CategoriesArray[$i],false)."</a>";
			}else{
				$result .= "<a href=\"".$CatPage->get_Href($CategoriesArray[$i],false)."\" target=\"".$CatPage->get_HrefTarget($CategoriesArray[$i],false)."\">".$CatPage->get_HrefText($CategoriesArray[$i],false)."</a>";
			}		
			if ($CountPageIndex >= 0) $result .= "<ul>";		
			for ($j = 0; $j <= $CountPageIndex; $j++) {
				$pageType = $CatPage->get_Type($CategoriesArray[$i],$PageArray[$j]);				
				if (($pageType == '.txt.php') or ($pageType == '.lnk.php')) {					
					$result .= "<li".$this->getLiClass(($j == $CountPageIndex),false,$CatPage->is_Activ($CategoriesArray[$i],$PageArray[$j])).">";
					if ($pageType == '.txt.php') {
						$result .= "<a href=\"".$CatPage->get_Href($CategoriesArray[$i],$PageArray[$j])."\">".$CatPage->get_HrefText($CategoriesArray[$i],$PageArray[$j])."</a>";
					}elseif ($pageType == '.lnk.php') {
						$result .= "<a href=\"".$CatPage->get_Href($CategoriesArray[$i],$PageArray[$j])."\" target=\"".$CatPage->get_HrefTarget($CategoriesArray[$i],$PageArray[$j])."\">".$CatPage->get_HrefText($CategoriesArray[$i],$PageArray[$j])."</a>";					
					}
					$result .= "</li>";				
				}
			}
			if ($CountPageIndex >= 0) $result .= "</ul>";			
			$result .= "</li>";
		}		
		$result .= "</ul>";
        $result .= "</div>";
        return $result;
    } // function getContent
    
    
    
    /***************************************************************
    * 
    * Gibt die Konfigurationsoptionen als Array zurueck.
    * 
    ***************************************************************/
    function getConfig() {
        global $ADMIN_CONF;        
        $dir = PLUGIN_DIR_REL."CSSDropDownMenu/";
        $language = $ADMIN_CONF->get("language");
        $lang_admin = new Properties($dir."sprachen/admin_language_".$language.".txt",false);

        $config = array();
        $config['cssdir'] = array(
        		"type" => "select",
        		"description" => $lang_admin->get("config_CSSDropDownMenu_css"),
        		"descriptions" => $this->getCSSLayouts()
        );          
        return $config;            
    } // function getConfig
    
    
    
    /***************************************************************
    * 
    * Gibt die Plugin-Infos als Array zurueck. 
    * 
    ***************************************************************/
    function getInfo() {
        global $ADMIN_CONF;        
        $dir = PLUGIN_DIR_REL."CSSDropDownMenu/";
        $language = $ADMIN_CONF->get("language");
        $lang_admin = new Properties($dir."sprachen/admin_language_".$language.".txt",false);        
        $info = array(
            // Plugin-Name
            "<b>".$lang_admin->get("config_CSSDropDownMenu_plugin_name")."</b> \$Revision: 2 $",
            // CMS-Version
            "2.0",
            // Kurzbeschreibung
            $lang_admin->get("config_CSSDropDownMenu_plugin_desc"),
            // Name des Autors
           "black-night",
            // Download-URL
            array("http://software.black-night.org","Software by black-night"),
            # Platzhalter => Kurzbeschreibung
            array('{CSSDropDownMenu}' => $lang_admin->get("config_CSSDropDownMenu_plugin_name"))
            );
            return $info;        
    } // function getInfo
    
    /***************************************************************
    *
    * Interne Funktionen
    *
    ***************************************************************/
    function getHead() {   
    	$head = '<style type="text/css"> @import "'.URL_BASE.PLUGIN_DIR_NAME.'/CSSDropDownMenu/css/'.$this->settings->get("cssdir").'/styles.css"; </style>'
    			;
    	return $head;
    } //function getHead
    
    function getLiClass($IsLast,$HasSub,$IsActiv) {
    	$result = "";
    	if ($IsActiv == true) {
    		$result .= " active";
    	}
    	if ($HasSub == true) {
    		$result .= " has-sub";
    	}
    	if ($IsLast == true) {
    		$result .= " last";
    	}
    	$result = trim($result);
    	if (strlen($result) > 0) {
    		return " class=\"".$result."\"";
    	}else{
    		return "";
    	}
    }
    
    function getCSSLayouts() {
    	$dir = PLUGIN_DIR_REL."CSSDropDownMenu/css/";
    	$result = array();
		$od = opendir($dir);
		
    	while($rd=readdir($od)) {
        	if($rd!="." && $rd!=".." && is_dir($dir.$rd)) {        		
            	$result[$rd] = $rd;            	
        	}
        }
        return $result;
    }
        
} // class FotoGalerie

?>