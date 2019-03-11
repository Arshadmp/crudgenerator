<?php
namespace Arshad\CrudGenerator\Http\Controllers;
use App\Http\Controllers\Controller;

use Arshad\CrudGenerator\Classes\CrudGeneratorService;

use Illuminate\Http\Request;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Schema;

use DB;
use Artisan;
use App\Project;

class CrudGeneratorController extends Controller
{
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{  

		if(!file_exists(base_path().'/resources/views/nav.blade.php')) 
		{  
			$navpath = base_path().'/resources/views/nav.blade.php';
			file_put_contents($navpath,'');
		}

		return view('crudgenerator::crudgenerator.index');

	}
   
    
	 /**
	 *
	 * get model name and call CrudgeneratorService
	 *
	 * @param    oojest  Request model name to create
	 * @return   url of created crud
	 */
    public function generate(Request $request)
	{
      
         

		$request->validate
		([
			'model_name' => 'required|alpha|max:20'
		]);




     
 
		$modelname = strtolower($request->get('model_name'));

		$prefix = \Config::get('database.connections.mysql.prefix');
		
 		$tocreate = [];

		$tocreate = [
			'modelname' => $modelname,
			'tablename' => '',
		];

		$tocreate = [$tocreate];   

		


         if (!Schema::hasTable(strtolower(str_plural($modelname))))
        {   
          
            return redirect()->back() ->with('alert', 'Table does not exsist!');

        }
        else
        {
        	foreach ($tocreate as $c) 
        	{
        		$generator = new CrudGeneratorService();
        		
        		$generator->appNamespace = Container::getInstance()->getNamespace();
        		$generator->modelName = ucfirst($c['modelname']);
        		$generator->tableName = $c['tablename'];

        		$generator->prefix = $prefix;

        		$generator->controllerName =str_plural($generator->modelName);
        		$generator->generate();



        		$url = strtolower(str_plural($generator->modelName));
        		$path = base_path().'/resources/views/nav.blade.php';
        		$content = file_get_contents($path);
        		if(str_contains($content,$url)) 
        		{ 
        		return redirect($url);
               	}
               	else
               	{
               	$this->navAdd($url);
        		return redirect($url);
               	}
        		

        	}

        }




	}

	 /**
	 *
	 * Display home page
	 *
	 *
	 * @return    \Illuminate\Http\Response
	 */
    public function view()
	{  
		return view('crudgenerator::crudgenerator.home');

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

	/**
	 *
	 *setting links to added to nav.blade.php
	 *
	 * @param    String  $link The link to add to file
	 * @return   array
	 */
    public function navAdd($link)
	{ 
   
        
    $addli = '<li class="nav-item">';
	$this->appendToEndOfFile(base_path().'/resources/views/nav.blade.php', "\n".$addli,0,false);

	$addnav = ' <a class="nav-link" href="'.url('/').'/'.$link.'">'.ucfirst($link).'</a>';
	$this->appendToEndOfFile(base_path().'/resources/views/nav.blade.php', "\n".$addnav,0, true);

	$addlic = '</li>';
	$this->appendToEndOfFile(base_path().'/resources/views/nav.blade.php', "\n".$addlic,0, false);
	}



}
