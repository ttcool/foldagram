<?php

class UsersController extends BaseController{


        public function getAdduser()
        {
                return View::make('admin.add_user')->with('title','Foldagram - Admin')->with("page_title","Add User");
        }

	public function postLogin()
	{
		Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password')));
		return View::make('blogs.addpost');
	}
	
	public function Logout()
	{
		Sentry::logout();
		return Redirect::to('/');
	}

        public function getIndex()
        {
                
                try
                {
                    $user = Sentry::getUser();

                    // 查询组
                    $admin = Sentry::findGroupByName('Admin');

                    // 检查
                    if ($user->inGroup($admin))
                    {
                    // 存在
                    if(!Sentry::check()){
                   return Redirect::to('register');
                    }

                    $users = User::all();

                  return View::make('admin.manage_user')->with('title','Foldagram - Admin')
                  ->with("page_title","Manage User")
                  ->with('users',$users);
                     }
                     else
                     {
                    // 不存在
                    }
               }
             catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
              {
               echo '用户不存在';
              }
               catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
               {
                echo '管理员组不存在';
                }

       
   
 
            
        }

}
