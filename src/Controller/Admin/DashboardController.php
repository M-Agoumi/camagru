<?php


namespace Controller\Admin;


use Model\ContactUs;
use Model\Emote;
use Model\Post;
use Model\User;
use Simfa\Framework\Application;
use Simfa\Framework\Exception\ForbiddenException;
use Simfa\Framework\Request;

class DashboardController extends BaseController
{
	public function index(User $user, Post $post):string
	{
		$totalUsers = $user->getCount();
		$newUsers = count($user->findAllBy([
			'created_at' => [' > ' =>  date('Y-m-d H:i:s', strtotime('-2 day', strtotime(date('Y-m-d H:i:s'))))]
		]));

		$totalPosts = $post->getCount();
		$newPosts = count($post->findAllBy([
			'created_at' => [' > ' =>  date('Y-m-d H:i:s', strtotime('-2 day', strtotime(date('Y-m-d H:i:s'))))]
		]));

		return render('admin/dashboard', [
			'totalUsers' => $totalUsers,
			'newUsers' => $newUsers,
			'totalPosts' => $totalPosts,
			'newPosts' => $newPosts
		]);
	}

	public function emotes():string
	{
		return render('admin/emotes/emotes', ['emotes' => (new Emote())->findAll()]);
	}

	public function addEmote(Emote $emote, Request $request)
	{
		$errors= array();
		$success = 0;
		if ($request->isPost())
		{
			if(isset($_FILES['image'])){
				$file_name = $_FILES['image']['name'];
				$file_size = $_FILES['image']['size'];
				$file_tmp = $_FILES['image']['tmp_name'];
				$file_type = $_FILES['image']['type'];
				$tmp = explode('.',$_FILES['image']['name']);
				$file_ext=strtolower(end($tmp));

				$extensions= array("jpeg","jpg","png");

				if(in_array($file_ext,$extensions)=== false){
					$errors[]="extension not allowed, please choose a JPEG or PNG file.";
				}

				if($file_size > 2097152) {
					$errors[]='File size must be excately 2 MB';
				}

				if(empty($errors)==true) {
					move_uploaded_file($file_tmp, Application::$ROOT_DIR . "/public/assets/img/".$file_name);
					$success = 1;
				}else{
					print_r($errors);
				}
			}

			if ($success) {
				$emote = new Emote();

				$emote->setName($request->getBody()['name']);
				$emote->setFile($file_name);

				$emote->save();
			}
		}
		return render('admin/emotes/add', ['errors' => $errors, 'success' => $success]);
	}

	public function deleteEmote(Emote $emote)
	{
		if ($emote->delete(1))
			return render('admin/emotes/deleted');
		return 'Something went wrong';
	}

	public function users(User $user)
	{
		$users = $user->findAll();

		return render('admin/users', ['users' => $users]);
	}

	public function deleteUser(User $user)
	{
		if (!isset($_GET[Application::$APP->session->getToken()]))
			throw new ForbiddenException();
		if ($user->delete(1))
			return render('admin/emotes/deleted');
		return 'Something went wrong';
	}

	public function posts(Post $post)
	{
		$posts = $post->paginate(['articles' => 20, 'order' => 'desc'],['allow_all']);

		return render('admin/posts', ['posts' => $posts, 'pst' => $post]);
	}

	public function deletePost(Post $post)
	{
		if (!isset($_GET[Application::$APP->session->getToken()]))
			throw new ForbiddenException();
		if ($post->delete(1))
			return render('admin/emotes/deleted');
		return 'Something went wrong';
	}

	public function messages(ContactUs $contact)
	{
		$contacts = $contact->paginate(['articles' => 10, 'order' => 'desc'],['allow_all']);

		return render('admin.messages', ['messages' => $contacts, 'msg' => $contact]);
	}

	public function showMessage(ContactUs $contactUs)
	{
		if (!isset($_GET[Application::$APP->session->getToken()]))
			throw new ForbiddenException();

		if (!$contactUs->updated_at)
			$contactUs->update();


		return $this->render('admin.showMessage', ['message' => $contactUs]);
	}
}
