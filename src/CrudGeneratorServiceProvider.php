<?php
// MyVendor\contactform\src\ContactFormServiceProvider.php
namespace Arshad\CrudGenerator;
use Illuminate\Support\ServiceProvider;
class CrudGeneratorServiceProvider extends ServiceProvider {
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views/', 'crudgenerator');
    }
    public function register()
    {
    }
}
?>
