<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct(private PermissionService $permissionService)
    {
        // Se quiser, aplique middleware de permissão aqui:
        // $this->middleware('permission:manage_permissions');
    }

    /**
     * GET /api/v1/permissions
     * Lista todas as permissões.
     */
    public function index()
    {
        $permissions = Permission::all();
        return PermissionResource::collection($permissions);
    }

    /**
     * POST /api/v1/permissions
     * Cria uma nova permissão.
     */
    public function store(StorePermissionRequest $request)
    {
        $data = $request->validated();
        $permission = $this->permissionService->createPermission($data);
        return new PermissionResource($permission);
    }

    /**
     * GET /api/v1/permissions/{permission}
     * Mostra detalhes de uma permissão.
     */
    public function show(Permission $permission)
    {
        return new PermissionResource($permission);
    }

    /**
     * PUT / PATCH /api/v1/permissions/{permission}
     * Atualiza uma permissão existente.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $data = $request->validated();
        $permission = $this->permissionService->updatePermission($permission, $data);
        return new PermissionResource($permission);
    }

    /**
     * DELETE /api/v1/permissions/{permission}
     * Remove uma permissão.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json(['message' => 'Permissão removida com sucesso']);
    }
}
