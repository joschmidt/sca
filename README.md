# sca

Silex ContextAware - a tiny contexts holder for Silex applications (see http://silex.sensiolabs.org/)

## Example usage

Allow safe control of your application behavior in regular and (unit) test mode.

Set up a context in your unit test (see also http://silex.sensiolabs.org/doc/testing.html ):

		public function createApplication()
		{
			$contextAware = ContextAware::newInstance();
			$contextAware->setContext(array('test' => true));
		    
			require __DIR__ . '/../../web/index.php';
		    
			$app['debug'] = true;
			$app['exception_handler']->disable();
		    
			return $app;
		}
  
Share ContextAware in your application (index.php):

		require_once __DIR__ . '/../vendor/autoload.php';
	
		$contextAware = App\ContextAware::newInstance();
		$contextAware->setContext(array('test' => false, 'foo' => 'bar'));
	
		$app = new Silex\Application();
	
		$app['ca'] = $app->share(function() use ($contextAware) {
	  		return $contextAware;
		});
		
		// get the unmodifiable context and use it to control your app. behavior 
		$app['ca']['test'];

