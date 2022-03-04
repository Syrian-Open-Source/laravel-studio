<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    private array $ignoredInterfacesFiles = ['..', '.', 'IBaseRepository.php'];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->getRepositoriesClasses() as $instance) {
            $this->app->bind($instance['interface'], $instance['class']);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * yield every interface of the repository with the class to bind
     *
     * @return \Generator
     * @author karam mustafa
     */
    private function getRepositoriesClasses()
    {
        foreach (scandir(app_path("Http/Repositories/Interfaces")) as $file) {

            if (!in_array($file, $this->ignoredInterfacesFiles)) {
                $file = pathinfo($file, PATHINFO_FILENAME);
                yield [
                    'interface' => "App\Http\Repositories\Interfaces\\".$file,
                    'class' => "App\Http\Repositories\Eloquent\\".substr($file, 1, strlen($file)),
                ];
            }
        }
    }
}
