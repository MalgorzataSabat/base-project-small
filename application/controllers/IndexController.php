<?php

class IndexController extends Base_Controller_Action
{

	public function indexAction()
	{
		$layoutService = new Layout_Service('dashboard');
		$this->view->layoutService = $layoutService;
	}

	public function manifestAction()
	{
		$manifest = array(
			"ios" => true,
			"name" => "Short Project",
			"short_name" => "Short Project",
			"description" => "Short Project - task",
			"default_locale" => "pl",
			"version" => 0.2,
			"author" => "Malgorzata Sabat",
			"icons" => array(),
			"scope" => "/",
			"start_url" => "/",
			"background_color" => "#006D99",
			"theme_color" => "#006D99",
			"display" => "standalone"
		);

		$manifest['start_url'] = "/login?token=".Base_Auth::getUser('token').'&utm_source=homescreen';



		$this->_helper->json($manifest);
	}

}

