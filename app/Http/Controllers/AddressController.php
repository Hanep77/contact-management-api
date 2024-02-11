<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Models\Contact;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    private function getContact(int $contact_id)
    {
        $contact = Auth::user()->contacts->where("id", $contact_id)->first();

        if (!$contact) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ], 404));
        }

        return $contact;
    }

    private function getAddress(Contact $contact, int $address_id)
    {
        $address = $contact->addresses->where("id", $address_id)->first();

        if (!$address) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ], 404));
        }

        return $address;
    }

    public function index(int $contact_id): JsonResponse
    {
        $contact = $this->getContact($contact_id);
        return response()->json(["data" => $contact->addresses]);
    }

    public function create(int $contact_id, AddressRequest $request): JsonResponse
    {
        $contact = $this->getContact($contact_id);

        $data = $request->validated();
        $data["contact_id"] = $contact->id;
        $address = Address::create($data);

        return response()->json(new AddressResource($address), 201);
    }

    public function get(int $contact_id, int $address_id): AddressResource
    {
        $contact = $this->getContact($contact_id);
        $address = $this->getAddress($contact, $address_id);
        return new AddressResource($address);
    }

    public function update(int $contact_id, int $address_id, AddressRequest $request): AddressResource
    {
        $contact = $this->getContact($contact_id);
        $address = $this->getAddress($contact, $address_id);

        $data = $request->validated();
        $address->update($data);

        return new AddressResource($address);
    }

    public function delete(int $contact_id, int $address_id): JsonResponse
    {
        $contact = $this->getContact($contact_id);
        $address = $this->getAddress($contact, $address_id);
        $address->delete();
        return response()->json(["data" => true]);
    }
}
