<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//不需要登陆保护的接口
//登陆
Route::post('login.do', 'UsersController@login');
//注册
Route::post('register.do','UsersController@register');
//生成谷歌验证码
Route::get('captcha.do','UsersController@captcha');
// 刷新token
Route::post('refreshToken', 'UsersController@refresh');
// 删除token
Route::post('deleteToken', 'UsersController@destroy');

//需要登陆的保护接口
//登出
Route::post('logout.do','UsersController@logout')->name('users/logout');
//我的消息
Route::post('myMessage.do','UsersController@index');
//校验银行卡密码
Route::post('myBankPassword.do','UsersController@password');
//修改昵称
Route::post('updateUsername','UsersController@updateUsername');
//修改银行账户姓名
Route::post('updateBankAccount','UsersController@updateBankAccount');
//修改登陆密码
Route::post('updatePassword','UsersController@updatePassword');
//修改资金密码
Route::post('updateBankPassWord','UsersController@updateBankWord');
//奖金详情
Route::post('prizeList.do','UsersController@prizeList');

//财务中心
//充值方式列表
Route::post('rechargeList.do','RechargesController@rechargeList');
//充值行为
Route::post('recharge.do','RechargesController@recharge');
//转账行为
Route::post('transfer.do','TransfersController@transfer');
//提现行为
Route::post('withdraw.do','WithdrawsController@withdraw');
//充值记录
Route::post('rechargeRecord.do','RechargesController@rechargeRecord');
//转账记录
Route::post('transferRecord.do','TransfersController@transferRecord');
//提现记录
Route::post('withdrawRecord.do','WithdrawsController@withdrawRecord');

//游戏记录管理
//追号记录
//Route::post('followRecord.do','FollowsController@follow');
//投注记录
//Route::post('betRecord.do','BetsController@bet');

//用户管理
Route::post('userManagement.do','UsersController@index')->name('user/index');
//用户查询
Route::post('userManagementFilter.do','UsersController@filter');
//邀请码
Route::post('inviteCode.do','UsersController@inviteCode')->name('user/inviteCode');

//报表管理
//团队余额
//Route::post('teamBalance.do','ReportsController@index')->name('teamBalance');
////盈亏报表
//Route::post('profitReport.do','ReportsController@index')->name('profitReport');
////盈亏报表查询
//Route::post('profitReportFilter.do','ReportsController@filter')->name('profitReportFilter');
////账变列表
//Route::post('accountChangeReport.do','ReportsController@index')->name('accountChangeReport');
////账变查询
//Route::post('accountChangeReportFilter.do','ReportsController@filer')->name('accountChangeReportFilter');
////统计报表
//Route::post( 'staReport.do','ReportsController@index')->name('staReport');
////统计报表查询
//Route::post('staReportFilter.do','ReportsController@index')->name('staReportFilter');


