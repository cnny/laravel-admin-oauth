<?php

namespace Cann\Admin\OAuth\Controllers;

use Redirect;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Controllers\AuthController as BaseAuthController;
use Cann\Admin\OAuth\ThirdAccount\ThirdAccount;

class AuthController extends BaseAuthController
{
    public function getLogin()
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectPath());
        }

        $sources = \Arr::only(ThirdAccount::sources(), config('admin-oauth.enabled_thirds'));

        $sources = \Arr::pluck($sources, 'sourceName', 'source');

        return view('oauth::login', compact('sources'));
    }

    public function toAuthorize(Request $request)
    {
        $request->validate([
            'source' => 'required|string|in:' . implode(',', config('admin-oauth.enabled_thirds') ?: []),
        ]);

        $thirdService = ThirdAccount::factory($request->source);

        $authorizeUrl = $thirdService->getAuthorizeUrl($request->all());

        return redirect($authorizeUrl);
    }

    // 第三方账号授权着陆
    public function oauthCallback(Request $request)
    {
        $posts = $request->validate([
            'source' => 'required|string|in:' . implode(',', config('admin-oauth.enabled_thirds') ?: []),
        ]);

        // 获取第三方工厂实例
        $thirdService = ThirdAccount::factory($posts['source']);

        // 获取第三方用户信息
        $thirdUser = $thirdService->getThirdUser($request->all());

        // 根据第三方用户信息获取我方用户信息
        $user = $thirdService->getUserByThird($thirdUser);

        // 如果社交账号未绑定我方账号，则前端引导前往绑定
        if (! $user) {

            // 临时存储第三方用户信息
            $request->session()->put('Admin-OAuth-ThirdUser', [
                'source'    => $request->source,
                'user_info' => $thirdUser,
            ]);

            return redirect()->guest(admin_url('oauth/bind-account'));
        }

        Admin::guard()->login($user);

        admin_toastr(trans('admin.login_successful'));

        return redirect(admin_url('/'));
    }

    public function bindAccount(Request $request)
    {
        if (! $thirdUser = $request->session()->get('Admin-OAuth-ThirdUser')) {
            throw new \Exception('Not Found Third User Info');
        }

        if ($request->isMethod('get')) {
            return view('oauth::bind-account', [
                'sourceName' => ThirdAccount::sources()[$thirdUser['source']]['sourceName'],
            ]);
        }

        else {

            $credentials = $request->only(['username', 'password']);

            $validator = \Validator::make($credentials, [
                'username' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }

            if (! Admin::guard()->validate($credentials)) {
                return Redirect::back()->withInput()->withErrors(['username' => '绑定失败，请检查账号或密码']);
            }

            $userModel = config('admin.database.users_model');

            $user = $userModel::where('username', $credentials['username'])->first();

            // 获取第三方工厂实例
            $thirdService = ThirdAccount::factory($thirdUser['source']);

            // 创建绑定关系
            $thirdService->bindUserByThird($user, $thirdUser['user_info']);

            Admin::guard()->login($user);

            admin_toastr('绑定成功');

            return redirect(admin_url('/'));
        }
    }
}
