<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct(private GroupService $groupService)
    {
        // Se quiser, pode aplicar middleware de permissão aqui
        // $this->middleware('permission:manage_groups');
    }

    /**
     * GET /api/v1/groups
     * Lista todos os grupos.
     */
    public function index()
    {
        // Carrega grupos e eager load de permissions (se quiser)
        $groups = Group::with('permissions')->get();
        return GroupResource::collection($groups);
    }

    /**
     * POST /api/v1/groups
     * Cria um novo grupo.
     */
    public function store(StoreGroupRequest $request)
    {
        // Monta array de dados
        $data = [
            'name'        => $request->validated('name'),
            'permissions' => $request->validated('permissions') ?? [],
        ];

        // Usa o service
        $group = $this->groupService->createGroup($data);

        // Carrega relacionamento e retorna resource
        $group->load('permissions');

        return new GroupResource($group);
    }

    /**
     * GET /api/v1/groups/{group}
     * Mostra detalhes de um grupo.
     */
    public function show(Group $group)
    {
        $group->load('permissions');
        return new GroupResource($group);
    }

    /**
     * PUT / PATCH /api/v1/groups/{group}
     * Atualiza um grupo existente.
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        $data = [
            'name'        => $request->validated('name'),
            'permissions' => $request->validated('permissions') ?? [],
        ];

        $group = $this->groupService->updateGroup($group, $data);

        $group->load('permissions');
        return new GroupResource($group);
    }

    /**
     * DELETE /api/v1/groups/{group}
     * Remove um grupo.
     */
    public function destroy(Group $group)
    {
        $group->delete();

        return response()->json(['message' => 'Grupo removido com sucesso'], 200);
    }
}
