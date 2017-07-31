<?php

namespace App\Http\Controllers;
use App\Meun;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("welcome",['posturl' => 'login']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $userName       = $request->input("UserName");
        $passWord       = $request->input("PassWord");
        $User           = new User();
        $loginResult    = $User->where('username', $userName)->first();
        if(null !== $loginResult)
        {
            $loginData = $loginResult->toArray();
            if($loginData['password'] === md5($passWord))
            {
                //登陆成功
                if (!$request->session()->has('users')) {
                    $request->session()->put('users',$loginData);
                }
                $Meun   = new Meun();
                $Result = $Meun->SelectList()->toArray();
                $M_Meun = array();
                foreach ($Result as $key=>$value)
                {
                    if ($value['pid'] === 0)
                    {
                        $value['children']      = array();
                        $M_Meun[$value['id']]   = $value;
                    }
                }
                foreach ($Result as $key=>$value)
                {
                    if ($value['pid'] != 0)
                    {
                        $M_Meun[$value['pid']]['children'][] = $value;
                    }
                }
                return view("index",['meun' => $M_Meun,'userInfo'=>$loginData]);
            }
            else
            {
                echo json_encode(array('status'=>false,'info'=>'密码输入错误'));
            }
        }
        else
        {
            echo json_encode(array('status'=>false,'info'=>'帐号不存在'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function signout(Request $request)
    {
        $request->session()->forget('users');
        $request->session()->flush();
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
