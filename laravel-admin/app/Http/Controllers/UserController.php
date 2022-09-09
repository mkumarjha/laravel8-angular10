<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{

    /**
     * @OA\Get(path="/users",
     *      security={{"bearerAuth":{}}},
     *  @OA\Response(response="200",
     *      description="User Collection",
     *   )
     * )
     */
    public function index()
    {
        $this->authorize('view','users');
        return UserResource::collection(User::with('role')->paginate());
    }

    
    public function store(UserCreateRequest $request)
    {
        $this->authorize('edit','users');

        $user = User::create(
            $request->only('first_name','last_name','email','role_id')
            + ['password' => Hash::make(1234)]
        );
        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    
    public function show($id)
    {
        $this->authorize('view','users');
        return new UserResource(User::with('role')->find($id));
    }

    
    public function update(UserUpdateRequest $request, $id)
    {
        $this->authorize('edit','users');

        $user =  User::find($id);
        $user->update($request->only('first_name','last_name','email'));
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        $this->authorize('edit','users');
        User::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}