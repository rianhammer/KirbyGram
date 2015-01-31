<?php

spl_autoload_register(function ($class) {
    include 'lib/' . str_replace('\\', '/', $class) . '.php';
});


$instagram = new Instagram();
$kirby = $this;

$this->options['routes'][] = array(
	'pattern' => 'kirbygram/install',
	'method'  => 'GET',
	'action'  => function($path = null) use($instagram, $kirby) {

		if(!$instagram->is_installed()){
			return f::load(__DIR__.'/templates/install.php', ['instagram' => $instagram]);
		}

		return false;

	}
);

$this->options['routes'][] = array(
	'pattern' => 'kirbygram/done',
	'method'  => 'GET',
	'action'  => function($path = null) use($instagram) {

		if($instagram->is_installed()){
			return f::load(__DIR__.'/templates/complete.php', ['instagram' => $instagram]);
		} else {
			return f::load(__DIR__.'/templates/error.php', ['instagram' => $instagram]);
		}

	}
);

$this->options['routes'][] = array(
	'pattern' => 'kirbygram/complete',
	'method'  => 'POST',
	'action'  => function($path = null) use($instagram) {
		if(!$instagram->is_installed()){

			//write to cache
			$data = $_POST;

			if($data['kgt'] == $instagram->get_config('csrf')){

				if($data['token'] && $data['user']){
					$instagram->set_config([
						'token' => $data['token'],
						'user' => $data['user'],
						'installed' => true
					]);
				}

				$instagram->save_config();

			}

			exit;

		}

		return false;

	}
);



?>