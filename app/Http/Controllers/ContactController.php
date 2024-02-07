<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function create(ContactCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $contact = Contact::create($data);

        return response()->json(new ContactResource($contact), 201);
    }

    public function get(int $id): ContactResource
    {
        $contact = Auth::user()->contacts->where('id', $id)->first();

        if (!$contact) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ], 404));
        }

        return new ContactResource($contact);
    }

    public function update(int $id, ContactUpdateRequest $request): ContactResource
    {
        $contact = Auth::user()->contacts->where('id', $id)->first();

        if (!$contact) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ], 404));
        }

        $data = $request->validated();
        $contact->update($data);

        return new ContactResource($contact);
    }

    public function delete(int $id): JsonResponse
    {
        $contact = Auth::user()->contacts->where('id', $id)->first();

        if (!$contact) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ], 404));
        }

        $contact->delete();

        return response()->json([
            "data" => true
        ]);
    }
}
