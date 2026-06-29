<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactServices
{
    public const STATUS_ACTIVE = 1;

    /**
     * @var Contact
     */
    private Contact $contact;

    /**
     * ContactServices constructor
     * @param Contact $contact
     */
    public function __construct(
        Contact $contact
    ) 
    {
        $this->contact = $contact;
    }
    
    /**
     * Contact Submit.
     * 
     * @param array $request.
     * @return bool
     */
    public function submit($request): bool
    {
        $this->contact->create([
            'name' => $request['name'] ?? '',
            'email' => $request['email'] ?? '',
            'message' => $request['message'] ?? ''
        ]);

        return true;
    }
}