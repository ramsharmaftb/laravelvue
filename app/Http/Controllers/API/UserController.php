<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function profile(){
        return auth('api')->user();
    } 

    public function updateProfile(Request $request){
        
        $user = auth('api')->user();
        $this->validate($request,[
            'name' => 'required|string|max:30',
            'email' => 'required|email|max:191|unique:users,email,'.$user->id,
            'bio' => 'required|string|min:10|max:250',
            'type' => 'required',
            'password' => 'sometimes|required|min:8',
            
        ]);
            $current_photo = $user->photo;
            if($request->photo != $user->photo){
                //for finding image extension with unique name
                $name = time().'.'.explode('/', explode(':',substr($request->photo,0, strpos($request->photo,';')))[1])[1];
                //saving file
                Image::make($request->photo)->save(public_path('/img/profile/').$name);
                $request->merge(['photo' => $name]);
                
                $userPhoto = public_path('img/profile/').$current_photo;
                if(file_exists($userPhoto)){
                    @unlink($userPhoto);
                }
            
            }
           
                if(!empty($request->password)){
                    $request->merge(['password' => Hash::make($request['password'])]);
                }
            $user->update($request->all());

        return ['message' => 'success'];
    } 

    public function search(Request $request){
        if(empty($request->q)){
            return User::latest()->paginate(10);
        }else{
            $users = User::where('name' ,'Like',"%$request->q%")
                         ->orWhere('email' ,'Like',"%$request->q%") 
                         ->orWhere('bio' ,'Like',"%$request->q%") 
                         ->orWhere('type' ,'Like',"%$request->q%")->paginate(10); 
        return $users;
         }
    }

    public function index()
    {
        return User::orderBy('id','DESC')->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:30',
            'email' => 'required|email|max:191|unique:users,email',
            'bio' => 'required|string|min:10|max:250',
            'type' => 'required',
            'password' => 'required|string|max:191|min:8',
            
        ]);

        return User::create([
            'name'=>$request['name'],
            'email'=>$request['email'],
            'type'=>$request['type'],
            'bio'=>$request['bio'],
            'photo'=>$request['photo'],
            'password'=>Hash::make($request['password']),
        ]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:30',
            'email' => 'required|email|max:191|unique:users,email,'.$user->id,
            'bio' => 'required|string|min:10|max:250',
            'type' => 'required',
            'password' => 'sometime|min:8',
            
        ]);

        //update user
        $user->update($request->all());
        return ['message' => 'User Updated'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        //delete user
        $user->delete();
        return ['message' => 'User Deleted'];
    }
}
