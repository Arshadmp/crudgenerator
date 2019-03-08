<?php
namespace Arshad\CrudGenerator\Classes;

use Illuminate\Console\Command;
use Arshad\CrudGenerator\Classes\CrudGeneratorFileCreator;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Redirect;

use DB;
use Artisan;

class CrudGeneratorService 
{

    public $modelName = '';
    public $tableName = '';
    public $prefix = '';
    public $controllerName = '';
    public $viewFolderName = '';
    public $appNamespace = 'App';


    /**
     *
     * generating controler and views 
     *
     * @return   void
     */
    public function generate() 
    {
        $modelname = ucfirst(str_singular($this->modelName));
        $this->viewFolderName = strtolower($this->controllerName);



        $options = [
            'model_uc' => $modelname,
            'model_uc_plural' => str_plural($modelname),
            'model_singular' => strtolower($modelname),
            'model_plural' => strtolower(str_plural($modelname)),
            'tablename' => $this->tableName ?: strtolower(str_plural($this->modelName)),
            'prefix' => $this->prefix,
            'controller_name' => $this->controllerName,
            'custom_master' => 'crudgenerator::layout',
            'view_folder' => $this->viewFolderName,
            'route_path' => $this->viewFolderName,
            'appns' => $this->appNamespace,
        ];


        $columns = $this->createModel($modelname, $this->prefix, $this->tableName);


        $options['columns'] = $columns;
        $options['num_columns'] = count($columns);
        // echo "<pre>";
        // print_r($columns);die();


        if(!is_dir(base_path().'/resources/views/'.$this->viewFolderName)) 
        {
            mkdir( base_path().'/resources/views/'.$this->viewFolderName);
        }
        if(count($columns)>4)
        {
            for ($x = 0; $x <=3; $x++)
            {
                $newcolumns[]= $columns[$x]; 
            }
            $options['newcolumns'] = $newcolumns;
        }
        else
        {
            $options['newcolumns'] = $columns;
        }

        $filegenerator = new CrudGeneratorFileCreator();
        $filegenerator->options = $options;


        $filegenerator->templateName = 'controller';
        $filegenerator->path = app_path().'/Http/Controllers/'.$this->controllerName.'Controller.php';
        $filegenerator->generate();

        $filegenerator->templateName = 'view.add';
        $filegenerator->path = base_path().'/resources/views/'.$this->viewFolderName.'/add.blade.php';
        $filegenerator->generate();

        $filegenerator->templateName = 'view.show';
        $filegenerator->path = base_path().'/resources/views/'.$this->viewFolderName.'/show.blade.php';
        $filegenerator->generate();


        $filegenerator->templateName = 'view.index';
        $filegenerator->path = base_path().'/resources/views/'.$this->viewFolderName.'/index.blade.php';
        $filegenerator->generate();


        $addroute = 'Route::get(\'/'.$this->viewFolderName.'/getData\', \''.$this->controllerName.'Controller@getData\');';
        $this->appendToEndOfFile(base_path().'/routes/web.php', "\n".$addroute, 0, true);



        $addroute = 'Route::resource(\'/'.$this->viewFolderName.'\', \''.$this->controllerName.'Controller\');';
        $this->appendToEndOfFile(base_path().'/routes/web.php', "\n".$addroute, 0, true);



    }
     

     /**
     *
     * get table informations
     *
     * @param    String  $tablename The name of the table
     * @return   array
     */
    protected function getColumns($tablename)
    {

        $cols = DB::select("show columns from " . $tablename);

        $ret = [];
        foreach ($cols as $c)
        {
            $field = isset($c->Field) ? $c->Field : $c->field;
            $type = isset($c->Type) ? $c->Type : $c->type;
            $null = isset($c->Null) ? $c->Null : $c->null;
            $cadd = [];
            $cadd['name'] = $field;
            $cadd['type'] = $field == 'id' ? 'id' : $this->getTypeFromDBType($type);
            $cadd['display'] = ucwords(str_replace('_', ' ', $field));
            $cadd['null'] = $field=='id'||$field=='created_at'||$field=='updated_at'? 'norequird':$null;
            $ret[] = $cadd;
        }
        return $ret;
    }
     
     /**
     *
     * return type of table column
     *
     * @param    String  $dbtype The type of the field
     * @return   String
     */
    protected function getTypeFromDBType($dbtype) {
        if(str_contains($dbtype, 'varchar')) { return 'text'; }
        if(str_contains($dbtype, 'int') || str_contains($dbtype, 'float')) { return 'number'; }
        if(str_contains($dbtype, 'date')) { return 'date'; }
        if(str_contains($dbtype, 'text')) { return 'textarea'; }
        if(str_contains($dbtype, 'timestamp')) { return 'timestamp'; }
        return 'unknown';
    }


    /**
     *
     * Create model
     *
     * @param    String  $modelname The model name for crating the model,String $prefix prefix,String $table_name 
     * The table name
     * @return   array
     */
     protected function createModel($modelname, $prefix, $table_name) {

        Artisan::call('make:model', ['name' => $modelname]);

        $columns = $this->getColumns($prefix.($table_name ?: strtolower(str_plural($modelname))));
         
        $cc = collect($columns);

        if(!$cc->contains('name', 'updated_at') || !$cc->contains('name', 'created_at')) { 
            $this->appendToEndOfFile(app_path().'/'.$modelname.'.php', "    public \$timestamps = false;\n\n}", 2, true);
        }

       
        return $columns;

       
    }


     /**
     *
     * add content to end of the file
     *
     * @param    String  $path The path of file,$text data to indert,Int $remove_last_chars number of last line to remove,Boolean dont_add_if_exist do not add if already added
     * @return   void
     */
    protected function appendToEndOfFile($path, $text, $remove_last_chars = 0, $dont_add_if_exist = false) {
        $content = file_get_contents($path);
        if(!str_contains($content, $text) || !$dont_add_if_exist) {
            $newcontent = substr($content, 0, strlen($content)-$remove_last_chars).$text;
            file_put_contents($path, $newcontent);    
        }
    }
}
