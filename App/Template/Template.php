<?php
//Defining the namespace
namespace App\Template;

/**
 * Template Management for PHP5
 *
 * The Template engine allows to keep the HTML code in some external files
 * which are completely free of PHP code. This way, it's possible keep logical
 * programmin (PHP code) away from visual structure (HTML or XML, CSS, etc).
 *
 * If you are familiar with PHP template concept, this class includes these
 * features: object support, auto-detect blocks, auto-clean children blocks,
 * warning when user call for a non-existent block, warning when a mal-formed
 * block is detected, warning when user sets a non existant variable, and other
 * minor features.
 *
 * @author Rael G.C. (rael.gc@gmail.com)
 * @version 2.2.1
 */
class Template extends \raelgc\view\Template{

	/**
	 * A hash of existent object properties variables in the document.
	 * @var	array
	 */
	protected $properties = array();

	/**
	 * Regular expression to find var and block names.
	 * Only alfa-numeric chars and the underscore char are allowed.
	 *
	 * @var		string
	 */
	protected static $REG_NAME = "([[:alnum:]]|_)+";

	/**
	 * Creates a new template, using $filename as main file.
	 *
	 * When the parameter $accurate is true, blocks will be replaced perfectly
	 * (in the parse time), e.g., removing all \t (tab) characters, making the
	 * final document an accurate version. This will impact (a lot) the
	 * performance. Usefull for files using the &lt;pre&gt; or &lt;code&gt; tags.
	 *
	 * @param     string $filename		file path of the file to be loaded
	 * @param     booelan $accurate		true for accurate block parsing
	 */
	public function __construct(mixed $pagina, protected $accurate = false, $default = true){
		$this->accurate = $accurate;
		if( $default )
			$this->loadfile(".", DIR_HTML . '/' . $pagina->diretorio . '/' . ( !is_null( $pagina->acao ) ? $pagina->acao . '.html' : 'index.html' ) );
		else
			$this->loadfile(".", $pagina);
	}
	/**
	 * Identifies all variables defined in the document.
	 *
	 * @param     string $content	file content
	 */
	protected function identifyVars(&$content){
		$r = preg_match_all("/{(".self::$REG_NAME.")((\-\>(".self::$REG_NAME."))*)?((\|.*?)*)?}/", $content, $m);
		if ($r){
			for($i=0; $i<$r; $i++){
				// Object var detected
				if($m[3][$i] && (!isset($this->properties[$m[1][$i]]) || !in_array($m[3][$i], $this->properties[$m[1][$i]]))){
					$this->properties[$m[1][$i]][] = $m[3][$i];
				}
				// Modifiers detected
				if($m[7][$i] && (!isset($this->modifiers[$m[1][$i]]) || !in_array($m[7][$i], $this->modifiers[$m[1][$i].$m[3][$i]]))){
					$this->modifiers[$m[1][$i].$m[3][$i]][] = $m[1][$i].$m[3][$i].$m[7][$i];
				}
				// Common variables
				if(!in_array($m[1][$i], $this->vars)){
					$this->vars[] = $m[1][$i];
				}
			}
		}
	}
	/**
	 * Fill in all the variables contained in variable named $value.
	 * $value. The resulting string is not "cleaned" yet.
	 *
	 * @param     string 	$value		var value
	 * @return    string	content with all variables substituted.
	 */
	protected function subst($value) {
		// Common variables replacement
		$s = str_replace(array_keys($this->values), $this->values, $value);
		// Common variables with modifiers
		foreach($this->modifiers as $var => $expressions){
			if(false!==strpos($s, "{".$var."|")) foreach($expressions as $exp){
				if(false===strpos($var, "->") && isset($this->values['{'.$var.'}'])){
					$s = str_replace('{'.$exp.'}', $this->substModifiers($this->values['{'.$var.'}'], $exp), $s);
				}
			}
		}
		// Object variables replacement
		foreach($this->instances as $var => $instance){
			foreach($this->properties[$var] as $properties){
				if(false!==strpos($s, "{".$var.$properties."}") || false!==strpos($s, "{".$var.$properties."|")){
					$pointer = $instance;
					$property = explode("->", $properties);
					for($i = 1; $i < sizeof($property); $i++){
						if(!is_null($pointer)){
							$obj = strtolower($property[$i]);
							// Get accessor
							if(method_exists($pointer, "get$obj")) $pointer = $pointer->{"get$obj"}();
							// Magic __get accessor
							elseif(method_exists($pointer, "__get")) $pointer = $pointer->__get($property[$i]);
							// Property acessor
							elseif(property_exists($pointer, $obj)) $pointer = $pointer->$obj;
							else {
								$className = $property[$i-1] ? $property[$i-1] : get_class($instance);
								$class = is_null($pointer) ? "NULL" : get_class($pointer);
								throw new \BadMethodCallException("no accessor method in class ".$class." for ".$className."->".$property[$i]);
							}
						} else {
							$pointer = $instance->get($obj);
						}
					}
					// Checking if final value is an object...
					if(is_object($pointer)){
						$pointer = method_exists($pointer, "__toString") ? $pointer->__toString() : "Object";
					// ... or an array
					} elseif(is_array($pointer)){
						$value = "";
						for($i=0; list($key, $val) = each($pointer); $i++){
							$value.= "$key => $val";
							if($i<sizeof($pointer)-1) $value.= ",";
						}
						$pointer = $value;
					}
					// Replacing value
					$s = str_replace("{".$var.$properties."}", $pointer ?? '', $s);
					// Object with modifiers
					if(isset($this->modifiers[$var.$properties])){
						foreach($this->modifiers[$var.$properties] as $exp){
							$s = str_replace('{'.$exp.'}', $this->substModifiers($pointer, $exp), $s);
						}
					}
				}
			}
		}
		return $s;
	}
}