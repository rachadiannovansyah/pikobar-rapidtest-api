<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Entities\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        /**
         * @var User $user
         */
        $user        = $request->user();
        $role        = $user->roles()->first();
        $permissions = $user->getAllPermissions();

        $province = $user->province ? $user->province()->select('code_kemendagri as code', 'name')->first() : null;
        $city     = $user->city ? $user->city()->select('code_kemendagri as code', 'name')->first() : null;

        return [
            'id'            => $user->id,
            'name'          => $user->name,
            'email'         => $user->email,
            'province_code' => $user->province_code,
            'province'      => $province,
            'city_code'     => $user->city_code,
            'city'          => $city,
            'role'          => $role ? [$role->name => $role->title] : null,
            'permissions'   => $permissions->pluck('title', 'name'),
        ];
    }

    /**
     * Update the user's profile information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $this->validate($request, [
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        return tap($user)->update($request->only('name', 'email'));
    }
}
