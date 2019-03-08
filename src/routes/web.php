<?php
// MyVendor\contactform\src\routes\web.php
Route::group(['namespace' => 'Arshad\CrudGenerator\Http\Controllers', 'middleware' => ['web']], function(){
	
Route::get('/crudgenerator', 'CrudGeneratorController@index')->name('crudgenerator');
Route::get('/crudgenerator/home', 'CrudGeneratorController@view')->name('crudgenerator.home');
Route::get('/crudgenerator/link', 'CrudGeneratorController@viewLinks')->name('crudgenerator.link');



Route::post('/crudgenerator/generate','CrudGeneratorController@generate')->name('crudgenerator.generate');
Route::get('/crudgenerator/navdisplay','CrudGeneratorController@navdisplay')->name('crudgenerator.navdisplay');

});

?>