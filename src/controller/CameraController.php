<?php


namespace controller;


class CameraController extends Controller
{
	public function index()
	{
		return $this->render('pages/camera', [], ['title' => 'Camera']);
	}
}