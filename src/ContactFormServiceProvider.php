<?php
// MyVendor\contactform\src\ContactFormServiceProvider.php
namespace Arshad\CrudGenerator;
use Illuminate\Support\ServiceProvider;
class ContactFormServiceProvider extends ServiceProvider {
	public function boot()
	{
		$this->loadRoutesFrom(__DIR__.'/routes/web.php');
	}
	public function register()
	{
	}
}
?>
