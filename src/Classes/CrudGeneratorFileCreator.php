<?php

namespace Arshad\CrudGenerator\Classes;

use Illuminate\Console\Command;
use DB;
use Artisan;

class CrudGeneratorFileCreator 
{
    public $templateName = '';
    public $path = '';
    public $options = [];
   
    public function __construct()
    {

    }
   
     /**
     *
     * Get template and generate file
     *
     * 
     * @return   void
     */ 
    public function generate() {
        $c = $this->renderWithData($this->customTemplate($this->templateName), $this->options);
        file_put_contents($this->path, $c);
        
    }

     /**
     *
     * read file content and replace foreach anf if
     *
     * @param    String  $template_path The path of file,Array $data  The array with details
     * @return   void
     */
    protected function renderWithData($template_path, $data) {
        $template = file_get_contents($template_path);
        $template = $this->renderForeachs($template, $data);
        $template = $this->renderIFs($template, $data);
        $template = $this->renderVariables($template, $data);
        return $template;
    }

    /**
     *
     *render varibles in template
     *
     * @param    String  $template The template content,array $data details needed
     * @return   String
     */
    protected function renderVariables($template, $data) {
        $callback = function ($matches) use($data) {
            if(array_key_exists($matches[1], $data)) {
                return $data[$matches[1]];
            }
            return $matches[0];
        };
        $template = preg_replace_callback('/\[\[\s*(.+?)\s*\]\](\r?\n)?/s', $callback, $template);
        return $template;
    }


     /**
     *
     *remove Foreach from template
     *
     * @param    String  $template The template content,array $data details needed
     * @return   String
     */
    protected function renderForeachs($template, $data) {
        $callback = function ($matches) use($data) {
            $rep = $matches[0];
            $rep = preg_replace('/\[\[\s*foreach:\s*(.+?)\s*\]\](\r?\n)?/s', '', $rep);
            $rep = preg_replace('/\[\[\s*endforeach\s*\]\](\r?\n)?/s', '', $rep);
            $ret = '';
            if(array_key_exists($matches[1], $data) && is_array($data[$matches[1]])) {
               
                $parent = $data[$matches[1]];

                foreach ($parent as $i) {
                    $d = [];
                    if(is_array($i)) {
                        foreach ($i as $key => $value) {
                            $d['i.'.$key] = $value;
                        }
                    }
                    else {
                        $d['i'] = $i;
                    }
                    $rep2 = $this->renderIFs($rep, array_merge($d, $data));
                    $rep2 = $this->renderVariables($rep2, array_merge($d, $data));
                    $ret .= $rep2;
                }
                return $ret;
            }
           
        };
        $template = preg_replace_callback('/\[\[\s*foreach:\s*(.+?)\s*\]\](\r?\n)?((?!endforeach).)*\[\[\s*endforeach\s*\]\](\r?\n)?/s', $callback, $template);
        return $template;
    }

    /**
     *
     *remove expression comma
     *
     * @param    String  $exp The template content,array $data details needed
     * @return   String
     */
    protected function getValFromExpression($exp, $data) {
        if(str_contains($exp, "'")) {
            return trim($exp,"'");    
        }
        else {
            if(array_key_exists($exp, $data)) {
                return $data[$exp];
            }
            else return null;
        }
    }
    /**
     *
     *replace if and endif
     *
     * @param    String  $template The template content,array $data details needed
     * @return   String
     */
    protected function renderIFs($template, $data) {
        $callback = function ($matches) use($data) {
            $rep = $matches[0];
            
            $rep = preg_replace('/\[\[\s*if:\s*(.+?)\s*([!=]=)\s*(.+?)\s*\]\](\r?\n)?/s', '', $rep);
            $rep = preg_replace('/\[\[\s*endif\s*\]\](\r?\n)?/s', '', $rep);
            $ret = '';
            $val1 = $this->getValFromExpression($matches[1], $data);
            $val2 = $this->getValFromExpression($matches[3], $data);
            if($matches[2] == '==' && $val1 == $val2) { $ret .= $rep; }
            if($matches[2] == '!=' && $val1 != $val2) { $ret .= $rep; }
            
            return $ret;
        };
        $template = preg_replace_callback('/\[\[\s*if:\s*(.+?)\s*([!=]=)\s*(.+?)\s*\]\](\r?\n)?((?!endif).)*\[\[\s*endif\s*\]\](\r?\n)?/s', $callback, $template);
        return $template;
    }

   
    /**
     *
     * get path of template file
     *
     * @param    String  $template_name The name of template
     * @return   String
     */
    protected function customTemplate($template_name) 
    {
        $trypath = base_path().'/resources/templates/'.$template_name.'.tpl.php';
        if(file_exists($trypath)) return $trypath;
        return __DIR__.'/Templates/'.$template_name.'.tpl.php';
    }

}