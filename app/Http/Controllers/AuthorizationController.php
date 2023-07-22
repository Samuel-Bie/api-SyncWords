<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Authorization as Organization;
use Symfony\Component\HttpFoundation\Response as HttpStatusCode;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthorizationController extends Controller
{
    public function token(Request $request)
    {
        $request->validate([
            'name'          => 'required|string',
            'secret'        => 'required|string',
            'application'   => 'nullable|string',
        ]);

        $organization = Organization::whereName($request->input('name'))
        ->first();

        if ($organization && $organization?->secret == $request->input('secret')) {
            return response()->json([
                'token' => $organization->createToken($request->input('application', 'spa'))->plainTextToken
            ], HttpStatusCode::HTTP_OK);
        }
        throw new UnauthorizedHttpException('Invalid login credentials.', "Invalid login credentials.");
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([], HttpStatusCode::HTTP_NO_CONTENT);
    }

    public function whoami(Request $request)
    {
        return response()->json([
            'organization' => $request->user()
        ], HttpStatusCode::HTTP_OK);
    }
}
