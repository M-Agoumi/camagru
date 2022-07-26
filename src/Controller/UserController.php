<?php

namespace Controller;


use Exception;
use Model\Post;
use Model\User;
use Model\Cover;
use Model\Email;
use Model\Config;
use Model\Background;
use Simfa\Model\Language;
use Simfa\Model\Preference;
use Simfa\Framework\Request;
use Simfa\Action\Controller;
use Middlewares\AuthMiddleware;
use Simfa\Framework\Application;

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
			Application::$APP->response->redirect('/user/' . Application::$APP->user->getUsername());
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

	/**
	 * add verification for the new email
	 * @param Request $request
	 * @return string|void|null
	 * @throws Exception
	 */
	public function update(Request $request)
	{
		/** @var User $user */
		$user = clone Application::$APP->user;
		$user->loadData($request->getBody());
		$newEmail = $request->getBody()['email'] ?? '';

		$user->validate();

		$valid = 1;
		/** check email */
		$this->updateEmail($user, $newEmail);

		if (sizeof($user->errors) > 1 || (sizeof($user->errors) == 1 && !isset($user->errors['password'])))
			$valid = 0;
		if ($valid && $user->update()) {
			Application::$APP->session->setFlash('success', 'Your information has been updated');
			return Application::$APP->response->redirect('/user/' . $user->getUsername());
		}

		return render('pages/profile/updateProfile', [
			'user' => $user,
			'title' => $user->getName() . " - Edit Profile"
		]);
	}

	private function updateEmail($user, $newEmail)
	{
		$email = new Email();
		$mainEmail = $email->queryBuilder()->select('email')->where('user', '=', $user->getId())->and()
			->where('prime', '=', '1')->get();

		if (count($mainEmail) && ($mainEmail[0]['email'] != $newEmail)) {
			if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL))
				$user->addError('email', 'Failed to change email, entered email isn\'t valid');
			else {
				/** check if email already exists */
				$email->getOneBy('email', $newEmail);
				if (!$email->getId()) {
					$email->setEmail($newEmail);
					$email->setUser($user);
					$email->setToken($this->generateToken());
					$email->setPrime(0);

					$data = ['confirmEmail', [
						'port'  => $_SERVER['SERVER_PORT'],
						'token' => $email->getToken(),
						'title' =>'Confirm Your Email']
					];

					if ($email->save()){
						if ($this->mail($email->getEmail(), 'Confirm Your Email', $data)){
//							Application::$APP->session->setFlash('success', 'Please confirm you email, we sent you a link');
							Application::$APP->session->setFlash('success', 'email has been updated, please follow the link sent to your email to complete the update');
							return redirect('/user/' . $user->getUsername());
						} else
							return render('messages/register_email_failed');
					}
				} else {
					$this->updateExistingEmail($email, $user);
				}
			}
		}
	}

	private function setEmailAsPrimary(User $user, Email $email)
	{
		$oldEmail = new Email();
		$oldEmail->getOneBy('email', $user->getEmail());
		$oldEmail->setPrime(0);
		$oldEmail->update();

		$email->setPrime(1);
		$email->setConfirmed(1);
		$email->setUser($user);
		$email->setActive(1);
		$email->update();

	}

	private function updateExistingEmail(Email $email, User $user): void
	{
		if ($email->getConfirmed() && $email->getUser()->getId() == $user->getId()){
			$this->setEmailAsPrimary($user, $email);
			return;
		}

		if ($email->getUser()->getId() != Application::$APP->user->getId())
			return;
		$email->setToken($this->generateToken());
		$data = ['confirmEmail', [
			'port'  => $_SERVER['SERVER_PORT'],
			'token' => $email->getToken(),
			'title' =>'Confirm Your Email']
		];

		if ($email->update()) {
			if ($this->mail($email->getEmail(), 'Confirm Your Email', $data)){
//				Application::$APP->session->setFlash('success', 'Please confirm you email, we sent you a link');
				Application::$APP->session->setFlash('success', 'email has been updated, please follow the link sent to your email to complete the update');
				redirect('/user/' . $user->getUsername());
				return;
			}
		}

		render('messages/register_email_failed');
	}


	/**
	 * @return string
	 */
	private function generateToken(): string
	{
		/** Generate a random string. */
		$token = openssl_random_pseudo_bytes(16);
		/** Convert the binary data into hexadecimal representation. */

		return bin2hex($token);
	}

	public function confirmEmail(Email $email): string
	{
		if ($email->getUser()->getId() == Application::$APP->user->getId()) {
			$oldEmail = new Email();
			$oldEmail->getOneBy('email', $email->getUser()->getEmail());
			$oldEmail->setPrime(0);
			$oldEmail->update();

			$email->setPrime(1);
			$email->setConfirmed(1);
			$email->setActive(1);
			$email->update();
			echo '<pre>';
			print_r($oldEmail);
		}

		Application::$APP->session->setFlash('error', 'something went wrong please try again later');

		return redirect('/');
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
					redirect(Application::path('user.preferences'));
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
			return Application::$APP->user->getName();

		return 'guest';
	}
}
