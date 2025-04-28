<?php

namespace App\Http\Controllers\Api;

use App\Enums\ProfileStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProfileRequest;
use App\Http\Requests\DeleteOrEditProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    public const STORAGE_PATH = "profile";
    public const STORAGE_OPTION = "public";

    public function list(): AnonymousResourceCollection
    {
        return ProfileResource::collection(
            Profile::query()->where('status', ProfileStatusEnum::ACTIVE)->get()
        );
    }

    public function create(CreateProfileRequest $request): Response
    {
        try {
            $profile = new Profile();
            $profile->firstname = $request->input('firstname');
            $profile->lastname = $request->input('lastname');
            $profile->status = $request->input('status');
            $profile->administrator_id = $request->input('administrator_id');

            /** @var UploadedFile $image **/
            $image = $request->validated('image');
            if (!$image->getError()) {
                $profile->image = $image->store(self::STORAGE_PATH, self::STORAGE_OPTION);
            }
            $profile->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile created successfully',
            ], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => sprintf('Create profile failed : %s', $exception->getMessage()),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function edit(DeleteOrEditProfileRequest $request): Response
    {
        $profile = Profile::query()->findOrFail($request->input('id'));
        $data = $request->validated();
        unset($data['id']);

        /** @var UploadedFile $image **/
        $image = $request->validated('image');
        if ($image !== null && !$image->getError()) {
            if ($profile->image) {
                Storage::disk(self::STORAGE_OPTION)->delete($profile->image);
            }
            $data['image'] = $image->store(self::STORAGE_PATH, self::STORAGE_OPTION);;
        }

        $profile->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
        ], Response::HTTP_OK);
    }

    public function delete(DeleteOrEditProfileRequest $request): Response
    {
        try {
            $profile = Profile::query()->findOrFail($request->input('id'));
            $profile->delete();

            return response()->json([
                'success' => true,
                'message' => 'Profile deleted successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => sprintf('Delete profile failed : %s', $exception->getMessage()),
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
