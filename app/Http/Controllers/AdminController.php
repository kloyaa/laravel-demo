<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Model Imports
use App\Models\Tenant;
use App\Models\Client;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Get All tenants
    public function getAllTenants() {
        $params = request()->all();
        if(auth()->user()->role !== "ADMIN") {
            return response()->json([
                'status' => 'error',
                'message' => 'Role not authorized',
            ], 401);
        }
        if(!$params) {
            $tenants = Tenant::orderBy('id', 'desc')->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Tenants retrieved successfully',
                'data' => $tenants,
            ], 200);
        }
        $tenants = Tenant::where('verified', $params['verified'])
            ->orderBy('id', 'desc')
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Tenants retrieved successfully',
            'data' => $tenants,
        ], 200);
    }

    // Get All users
    public function getAllClients() {
        if(auth()->user()->role !== "ADMIN") {
            return response()->json([
                'status' => 'error',
                'message' => 'Role not authorized',
            ], 401);
        }

        $clients = Client::orderBy('id', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Clients retrieved successfully',
            'data' => $clients,
        ], 200);
    }

    // Verify tenant
    public function verifyTenant($id) {
        if(auth()->user()->role !== "ADMIN") {
            return response()->json([
                'status' => 'error',
                'message' => 'Role not authorized',
            ], 401);
        }

        $tenant = Tenant::find($id);
        if(!$tenant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tenant not found',
            ], 404);
        }

        $tenant->verified = true;
        $tenant->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant verified successfully',
            'data' => $tenant,
        ], 200);
    }
}
