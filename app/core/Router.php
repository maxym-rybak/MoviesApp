<?php

class Router
{
	static function start()
	{
		// контроллер и действие по умолчанию
		$controller_name = 'Movies';
		$action_name = 'main';
		
		$routes = explode('/', $_SERVER['REQUEST_URI']);

		// получаем имя контроллера
		if ( !empty($routes[1]) )
		{	
			$controller_name = $routes[1];
		}
		
		// получаем имя экшена
		if ( !empty($routes[2]) )
		{
			$action_name = $routes[2];
		}

		// добавляем префиксы
		$model_name = $controller_name.'Model';
		$controller_name = $controller_name.'Controller';
		$action_name = 'action_'.$action_name;

		// подцепляем файл с классом модели (файла модели может и не быть)

		$model_file = strtolower($model_name).'.php';
		$model_path = "app/models/".$model_file;
		if(file_exists($model_path))
		{
			include "app/models/".$model_file;
		}

		// подцепляем файл с классом контроллера
		$controller_file = strtolower($controller_name).'.php';
		$controller_path = "app/controllers/".$controller_file;
		if(file_exists($controller_path))
		{
			include "app/controllers/".$controller_file;
		}
		else
		{
			/*
			правильно было бы кинуть здесь исключение,
			но для упрощения сразу сделаем редирект на страницу 404
			*/
			Router::ErrorPage404();
			// echo "file no esist!";
		}
		
		if(class_exists($controller_name) && method_exists($controller_name, $action_name))
		{
			// вызываем действие контроллера
			$controller = new $controller_name;
			$controller->$action_name();
		}
		else
		{
			// echo "Controller or action does not exists";
			// здесь также разумнее было бы кинуть исключение
			Router::ErrorPage404();
		}
	
	}
	
	static function ErrorPage404()
	{
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
		header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:'.$host.'app/views/errors/'.'404.php');
    }
}