<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Clients API",
 *     version="1.0",
 *     description="Documentacion de la Api de Clientes con Swagger"
 * )
 */

class ClientController extends Controller
{
    /**
      * @OA\Get(
     * path="/api/clients",
     * summary="Get all clients",
     * tags={"Clients"},
     * @OA\Response(
     * response=200,
     * description="List of clients"))
     */
    public function index()
    {
        return response()->json(Client::all(), 200);
    }

    /**
     * @OA\Post(
     * path="/api/clients",
     * summary="Create a client",
     * tags={"Clients"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"first_name", "last_name", "phone_number", "email", "address"},
     * @OA\Property(property="first_name", type="string"),
     * @OA\Property(property="last_name", type="string"),
     * @OA\Property(property="phone_number", type="string"),
     * @OA\Property(property="email", type="string", format="email"),
     * @OA\Property(property="address", type="string"),
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error")
     * )
     * 
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:clients,email', 
            'address' => 'required|string|max:255',
        ]);
        
        $client = Client::create($request->all());
        return response()->json($client, 201);
    }

    /**
     * @OA\Get(
     * path="/api/clients/{id}",
     * summary="Get client by id",
     * tags={"Clients"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID of client",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Client data"),
     * @OA\Response(
     * response=404,
     * description="Client not found")
     * )
     */
    public function show(string $id)
    {
        $client = Client::find($id);
        if (!$client){
            return response()->json(['message' => 'Client not found'], 404);
        }
        return response()->json($client, 200);
    }

    /**
     * @OA\Put(
     * path="/api/clients/{id}",
     * summary="Update a client",
     * tags={"Clients"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID of client",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="first_name", type="string"),
     * @OA\Property(property="last_name", type="string"),
     * @OA\Property(property="phone_number", type="string"),
     * @OA\Property(property="email", type="string", format="email"),
     * @OA\Property(property="address", type="string"),
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Client updated"),
     * @OA\Response(
     * response=404,
     * description="Client not found"),
     * )
     */
    public function update(Request $request, $id)
    {
        $client = Client::find($id);
        if (!$client){
            return response()->json(['message' => 'Client not found'], 404);
        }
        $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'phone_number' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:clients, email,'. $id,
            'address' => 'sometimes|string|max:255',
        ]);
        $client->update($request->all());
        return response()->json($client, 200);
    }

    /**
      * @OA\Delete(
 * path="/api/clients/{id}",
 * summary="Delete a client",
 * tags={"Clients"},
 * @OA\Parameter(
 * name="id",
 * in="path",
 * description="ID of client",
 * required=true,
 * @OA\Schema(type="integer")
 * ),
 * @OA\Response(
 * response=200,
 * description="Client deleted"
 * ),
 * @OA\Response(
 * response=404,
 * description="Client not found"
 * )
 * )
     */
    public function destroy(string $id)
    {
        $client = Client::find($id);
        if (!$client){
            return response()->json(['message' => 'Client not found'], 404);
        }
        $client->delete();
        return response()->json(['message' => 'Client deleted'], 200);
    }
}
