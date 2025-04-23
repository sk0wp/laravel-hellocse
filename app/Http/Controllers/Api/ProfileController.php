<?php

namespace App\Http\Controllers\Api;

use App\Enums\ProfileStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEditProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    public function list(): AnonymousResourceCollection
    {
        return ProfileResource::collection(
            Profile::query()->where('status', ProfileStatusEnum::ACTIVE)->get()
        );
    }

    public function create(CreateEditProfileRequest $request): Response
    {
        try {
            $profile = new Profile();
            $profile->firstname = $request->input('firstname');
            $profile->lastname = $request->input('lastname');
            $profile->status = $request->input('status');
            $profile->administrator_id = $request->input('administrator_id');
            $profile->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile created successfully',
                'data' => $profile
            ], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => sprintf('Create profile failed : %s', $exception->getMessage()),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function edit(CreateEditProfileRequest $request, Profile $profile): Response
    {
        $profile->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $profile
        ], Response::HTTP_OK);
    }

    public function delete(Profile $profile): Response
    {
        $profile->delete();

        return response()->json([
            'success' => true,
            'message' => 'Profile deleted successfully',
            'data' => $profile
        ], Response::HTTP_OK);
    }
}
