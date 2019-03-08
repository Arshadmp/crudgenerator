<?php
/**
* @author Arshad Mp
* @author Arshad mp <arshadmp@ndimensionz.com>
*/
namespace [[appns]]Http\Controllers;
use Illuminate\Http\Request;
use [[appns]]Http\Requests;
use [[appns]]Http\Controllers\Controller;
use [[appns]][[model_uc]];
use DB;

class [[controller_name]]Controller extends Controller
{
  //
  public function __construct()
  {
    //$this->middleware('auth');
  }


  public function index(Request $request)
  {
    return view('[[view_folder]].index', []);
  }

  //show view of add page
  public function create(Request $request)
  {
    $[[model_singular]] = new [[model_uc]];
    return view('[[view_folder]].add');
  }
  
   //show view of edit oage
  public function edit(Request $request, $id)
  {
    $[[model_singular]] = [[model_uc]]::findOrFail($id);
    return view('[[view_folder]].add', [
      'model' => $[[model_singular]]
    ]);
  }

  //show view of show page
  public function show(Request $request, $id)
  {
    $[[model_singular]] = [[model_uc]]::findOrFail($id);
    return view('[[view_folder]].show',compact('[[model_singular]]'));
  }

  
  //function to upadate data to database.
  public function update(Request $request) {

  $[[model_singular]] = null;
  if($request->id > 0) { $[[model_singular]] = [[model_uc]]::findOrFail($request->id); }
  else {
    $[[model_singular]] = new [[model_uc]];
  }
  
$request->validate //generating validation rules
([

[[foreach:columns]]
[[if:i.null=='NO']]
'[[i.name]]' => 'required',
[[endif]]
[[endforeach]]

[[foreach:columns]]
[[if:i.type=='text']]
'[[i.name]]' => 'max:255|regex:/^[\pL\s]+$/u',
[[endif]]
[[endforeach]]
[[foreach:columns]]
[[if:i.type=='number']]
'[[i.name]]' => 'regex:/^[0-9]+/|not_in:0',
[[endif]]
[[endforeach]]
[[foreach:columns]]
[[if:i.type=='date']]
'[[i.name]]' => 'required',
[[endif]]
[[endforeach]]
[[foreach:columns]]
[[if:i.display=='Email']]
'[[i.name]]' => 'email',
[[endif]]
[[endforeach]]


[[foreach:columns]]
[[if:i.null=='YES']]
'[[i.name]]' => 'nullable',
[[endif]]
[[endforeach]]

[[foreach:columns]]
[[if:i.name=='email']]
'[[i.name]]' => 'email',
[[endif]]
[[endforeach]]

]);

 
[[foreach:columns]]
[[if:i.name=='id']]
$[[model_singular]]->[[i.name]] = $request->[[i.name]]?:0;
[[endif]]
[[if:i.name!='id']]
$[[model_singular]]->[[i.name]] = $request->[[i.name]];
[[endif]]
[[endforeach]]



$[[model_singular]]->save();

  return redirect('/[[route_path]]');

}

//function to store data to database.
public function store(Request $request)
{
  return $this->update($request);
}

//delete a row in table
public function destroy(Request $request, $id) {

  $[[model_singular]] = [[model_uc]]::findOrFail($id);

  $[[model_singular]]->delete();
  return "OK";

}

//get data from database
public function getData( Request $request )
{    

    $order = $request->order;
    $offset = !empty( $request->offset) ?  $request->offset :0;
    $limit = !empty(  $request->limit ) ?  $request->limit : 10;
    $search = $request->search;
    $sort = !empty(  $request->sort ) ?  $request->sort : "";


  
    DB::statement(DB::raw('set @row='.$offset.''));
    $model= [[model_uc]]::selectRaw('*, @row:=@row+1 as row');



    if ( !empty( $search ) ) {  //setting condition based on search

        [[foreach:newcolumns]]
        $model->orWhere('[[i.name]]','LIKE',"%{$search}%");
        [[endforeach]]
    }
    $count = $model->count();

    $model->limit($limit)->offset($offset); //setting conditions based on limit and offset
    if ( !empty( $sort ) ) {  //setting conditions based on sort
        $model->orderBy($sort,$order);
    }

    $data = $model->get();
  
    $responseData = [
        "total" => $count,
        "rows" => $data
    ];
    return response()->json($responseData);
}

}
