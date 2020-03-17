<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function login(Request $request)
    {
        //验证
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/',
            'password' => 'required|alpha_num|between:6,16',
            'captcha' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        //校对验证码
        $captcha_key = Cache::get($request->captcha_key);
        if ($captcha_key !== $request->code){
            return '验证码不正确';
        }

        $credentials['name'] = $request->name;
        $credentials['password'] = $request->password;

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        //验证
         $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users,name|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/',
            'password' => 'required|alpha_num|between:6,16|confirmed',
            'password_confirmation' => 'required|alpha_num|between:6,16',
            'captcha' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

         //校对验证码
        $captcha_key = Cache::get($request->captcha_key);
        if ($captcha_key !== $request->code ){
            return '验证码不正确';
        }
        //逻辑
        $user = User::create([
            'name' => $request->name,
            //加密密码
            'password' => Hash::make($request->password),
        ]);

        $token = auth('api')->fromUser($user);

        //渲染
        return response()->json(compact('user','token'))->setStatusCode(201);
    }

    //生成验证码
    public function captcha(CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-'.Str::random(15);
        //生成验证码实例
        $captcha = $captchaBuilder->build();
        //设置过期时间
        $expiredAt = now()->addMinutes(2);
        //获取验证码数字设置key和过期时间放入缓存
        Cache::put($key, ['code' => $captcha->getPhrase()], $expiredAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline(),//验证码图片
            'code' => $captcha->getPhrase()//图片中的数字或者字母
        ];

        //渲染
        return response()->json($result)->setStatusCode(201);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     * 刷新token，如果开启黑名单，以前的token便会失效。
     * 值得注意的是用上面的getToken再获取一次Token并不算做刷新，两次获得的Token是并行的，即两个都可用。
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
