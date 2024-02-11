<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Builder;
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

    // public function search(Request $request)
    // {
    //     $user = Auth::user();
    //     $page = $request->input('page', 1);
    //     $size = $request->input('size', 10);

    //     $contacts = $user->contacts;

    //     $contacts->where(function (Builder $builder) use ($request) {
    //         $name = $request->input('name');
    //         if ($name) {
    //             $builder->where(function (Builder $builder) use ($name) {
    //                 $builder->orWhere('first_name', "LIKE", "%$name%")
    //                     ->orWhere('last_name', "LIKE", "%$name%");
    //             });
    //         }

    //         $phone = $request->input('phone');
    //         if ($phone) {
    //             $builder->orWhere('phone', "LIKE", "%$phone%");
    //         }

    //         $email = $request->input('email');
    //         if ($email) {
    //             $builder->orWhere('email', "LIKE", "%$email%");
    //         }
    //     });

    //     $contacts->paginate(perPage: $size, page: $page);

    //     return response()->json([
    //         'test' => 'test'
    //     ]);
    // }
}
