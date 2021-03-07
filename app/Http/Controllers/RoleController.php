<?php

namespace App\Http\Controllers;

use App\Traits\APIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use APIResponse;

    public function __construct()
    {
        $this->middleware('role:super-admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();

        return $this->response("Success get roles.", $roles, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:4|max:50',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->response(null, $validator->errors(), 422);
        }

        try {
            $role = Role::create([
                'name' => $request->name,
            ]);

            $role->syncPermissions($request->permission);

            return $this->response("Successfully create role.", $request->all(), 201);
        } catch (\Exception $e) {
            return $this->response("Failed to create role.", $e, 409);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::with('permissions', 'users')->find($id);

        return $this->response('Success get role', $role, 200);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:4|max:50',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->response(null, $validator->errors(), 422);
        }

        try {
            $role = Role::findOrFail($id);

            $role->update([
                'name' => $request->name,
            ]);

            $role->syncPermissions($request->permission);

            return $this->response("Successfully update role.", $request->all(), 201);
        } catch (\Exception $e) {
            return $this->response("Failed to update role.", $e, 409);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);

            $role->delete();

            return $this->response("Successfully delete role.", null, 201);
        } catch (\Exception $e) {
            return $this->response("Failed to delete role.", $e, 409);
        }
    }
}
