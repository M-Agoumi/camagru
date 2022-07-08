<?php

namespace Controller;


use Exception;
use Middlewares\AuthMiddleware;
use Model\Background;
use Model\Config;
use Model\Cover;
use Model\Post;
use Model\User;
use Simfa\Action\Controller;
use Simfa\Framework\Application;
use Simfa\Framework\Request;
use Simfa\Model\Language;
use Simfa\Model\Preference;

class UserController extends Controller
{

	public function __construct()
	{
		$this->registerMiddleware(New AuthMiddleware(['edit', 'update', 'preferences']));
	}

	public function index(User $user)
	{
		/** get user cover image */
		$bg = new Background();
		$bg->getOneBy('user', $user->getId());
		if (!$bg->getId()) {
			$config = new Config();
			$config->getOneBy('name', 'user/profile/cover');
			$image = $config->getValue();
		} else {
			if (!$bg->getType())
				$image = $bg->getImage();
			else {
				$cover = new Cover();
				$cover->getOneBy($bg->getImage());
				$image = $cover->getImage();
			}
		}

		/** get all posts of the user */
		$posts = NEW Post();
		$posts = $posts->findAllBy(['author' => $user->getId()]);
		if (!Application::isGuest())
			if ($user->username === Application::$APP->user->getUsername())
				return $this->render('pages/profile/myProfile',
					[
						'user' => $user,
						'title' => Application::$APP->user->getName() . " Profile",
						'posts' => $posts,
						'cover' => $image,
						'bg' => $bg,
						'covers' => new Cover()
					]);

		return render('pages/profile/profile', [
			'user' => $user,
			'title' => $user->name . " Profile",
			'posts' => $posts,
			'cover' => $image
		]);
	}

	/**
	 * show profile if user is logged otherwise redirect to log in
	 */

	public function myProfile()
	{
		if (Application::$APP->user)
			Application::$APP->response->redirect('/user/' . Application::$APP->user->username);
		else
			Application::$APP->response->redirect(Application::path('auth.login'));
	}

	public function edit()
	{
		return render('pages/profile/updateProfile', [
			'user' => Application::$APP->user,
			'title' => Application::$APP->user->name . " - Edit Profile"
		]);
	}

	public function update(Request $request)
	{
		$user = Application::$APP->user;

		$user->loadData($request->getBody());

		$user->validate();

		$valid = 1;

		if (sizeof($user->errors) == 1 && !isset($user->errors['password']))
			$valid = 0;
		if ($valid && $user->update()) {
			Application::$APP->session->setFlash('success', 'Your information has been updated');
			return Application::$APP->response->redirect(Application::path('user.profile'));
		}

		return render('pages/profile/updateProfile', [
			'user' => $user,
			'title' => Application::$APP->user->name . " - Edit Profile"
		]);
	}

	public function UpdatePassword(Request $request): ?string
	{
		/** @var User $user */
		$user = Application::$APP->user;

		if ($request->isPost()) {
		    $newUser = clone $user;

		    $newUser->loadData($request->getBody());

		    if (password_verify($newUser->password, $user->password)) {
		    	$password = filter_input(INPUT_POST, "newPassword", FILTER_SANITIZE_SPECIAL_CHARS);
		    	$retypePassword = filter_input(INPUT_POST, "retypePassword", FILTER_SANITIZE_SPECIAL_CHARS);

		    	$user->password = $password;
		    	$user->pass = true;

		    	if ($password === $retypePassword) {
		    		if ($user->validate()) {
					    if ($user->update()) {
					        Application::$APP->session->setFlash('success', 'Password Updated');
					        Application::$APP->response->redirect('/user/' . $user->username);
					    } else
						    echo "something went wrong";
				    } else {
					    unset($user->errors['password']);
					    $user->addError('newPassword', 'Password is not valid');
				    }
			    } else {
				    $user->addError('retypePassword', "new password doesn't match");
			    }
		    } else
		    	$user->addError('password', 'password is wrong');
		}
		$user->setPassword('');

		return render('pages/profile/updatePassword', ['user' => $user, 'title' => 'Update Password']);
	}

	/**
	 * @param Request $request
	 * @return string|null
	 * @throws Exception
	 */
	public function preferences(Request $request): ?string
	{
		$pref = New Preference();
		$user = Application::$APP->user->getId();

		$pref->getOneBy('user', $user);

		if ($request->isPost()) {
			$pref->loadData($request->getBody());

			/** todo add preference for sending an email on command or disabling it */
			if ($pref->entityID) {
				if ($pref->validate() && $pref->update()){
					Application::$APP->session->set('lang_main', (Language::getLang($pref->language))->language);
					Application::$APP->session->set('lang_fb', (Language::getLang($pref->language))->language);
					Application::$APP->session->setFlash('success', 'your preferences has been updated');
					Application::$APP->response->redirect(Application::path('user.preferences'));
				}
			} else {
				$pref->user = $user;

				if ($pref->validate() && $pref->save()) {
					Application::$APP->session->set('lang_main', (Language::getLang($pref->language))->language);
					Application::$APP->session->setFlash('success', 'your preferences has been updated');
					Application::$APP->response->redirect(Application::path('user.preferences'));
				}
			}
		}

		return render('pages/profile/preferences', ['pref' => $pref, 'title' => 'Preferences'] );
	}

	/**
	 * @param Request $request
	 * @return string
	 */
	public function updateBackground(Request $request): string
	{
		$status = false;
		try {
			$data = $request->getBody();
		} catch (Exception $e) {
			return $this->json(['status' => false, 'message' => 'form token is invalid']);
		}

		$message = 'your preferences has been updated successfully';
		$type = $data['type'] ?? 0;

		if ($type) {
			$cover = new Cover();
			$cover->getOneBy('image', $data['image']);
			if ($cover->getId()) {
				$bg = new Background();
				$bg->setUser(Application::$APP->user);
				$bg->setType(1);
				$bg->setImage($cover->getId());
				if ($bg->save())
					$status = true;
			} else {
				$message = 'something went wrong, please try again, if the problem persist try again later';
			}
		} else {
			$bg = new Background();

			$bg->setUser(Application::$APP->user);
			if(isset($_FILES['file']['name'])){
				// file name
				$filename = $_FILES['file']['name'];
				$file_size = $_FILES['file']['size'];

				$bg->setType(0);
				$bg->setImage($filename);
				// Location
				$location = 'uploads/cover/'.$filename;

				// file extension
				$file_extension = pathinfo($location, PATHINFO_EXTENSION);
				$file_extension = strtolower($file_extension);

				// Valid extensions
				$valid_ext = array("png","jpg","jpeg","svc", 'webp');

				if(in_array($file_extension,$valid_ext)){
					if ($file_size > Application::getAppConfig('post', 'max_file_size')) {
						// Upload file
						if (@move_uploaded_file($_FILES['file']['tmp_name'],$location)) {
							$bg->save();
							return $this->json(['status' => true, 'message' => 'Your cover image has been updated!']);
						}
					}
					return $this->json(['status' => false, 'message' => 'Something went wrong in our side']);
				}
				$message = 'file type is not supported';
			} else
				$message = 'file can\'t be empty';
		}

		return $this->json(['status' => $status, 'message' => $message]);
	}

	/**
	 * @param Request $request
	 * @return bool|string
	 */
	public function updateProfilePicture(Request $request): bool|string
	{
		$user = Application::$APP->user;
		try {
			$request->getBody();
		} catch (Exception $e) {
			return json_encode(['status' => false, 'message' => 'csrf token is not valid']);
		}
		$status = false;

		if(isset($_FILES['file']['name'])){
			// file name
			$filename = $_FILES['file']['name'];

			// Location
			$location = 'uploads/dps/'.$filename;

			// file extension
			$file_extension = pathinfo($location, PATHINFO_EXTENSION);
			$file_extension = strtolower($file_extension);

			// Valid extensions
			$valid_ext = array('png', 'jpg', 'jpeg', 'svc', 'webp');

			if(in_array($file_extension,$valid_ext)){
				// Upload file
				if (move_uploaded_file($_FILES['file']['tmp_name'],$location)) {
					if ($user->getPicture() && file_exists(Application::$ROOT_DIR . '/public/upload/dps/' . $user->getPicture()))
						unlink(Application::$ROOT_DIR . '/public/upload/dps/' . $user->getPicture());
					$user->setPicture($filename);
					$user->update();
					$status = true;
					$message = 'your profile picture has been updated';
				} else
					$message = 'something went wrong';
			} else
				$message = 'file type is not supported';
		} else
			$message = 'file can\'t be empty';

		return $this->json(['status' => $status, 'message' => $message, 'token' => Application::$APP->session->getCsrf()]);
	}

	/**
	 * @return false|string
	 */
	public function DeleteCover(): bool|string
	{
		$bg = new Background();

		$bg->getOneBy('user', Application::$APP->user->getId());

		if ($bg->getId()) {
			if (!$bg->getType() && file_exists(Application::$ROOT_DIR . '/uploads/cover/' . $bg->getImage()))
				unlink(Application::$ROOT_DIR . '/uploads/cover/' . $bg->getImage());
			$bg->delete(1);
		}

		return $this->json(['status' => true, 'message' => 'cover image has been deleted']);
	}

	public function deleteDp()
	{
		$user = Application::$APP->user;

		if ($user->getPicture()) {
			if (file_exists(Application::$ROOT_DIR . '/uploads/dps/' . $user->getPicture()))
				unlink(Application::$ROOT_DIR . '/uploads/dps/' . $user->getPicture());
			$user->setPicture(null);

			$user->update();
		}

		return $this->json(['status' => true, 'message' => 'profile picture has been deleted']);
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		if (Application::$APP->user)
			return Application::$APP->user->name;

		return 'guest';
	}
}
