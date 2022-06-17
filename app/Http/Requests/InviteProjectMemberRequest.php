<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class InviteProjectMemberRequest extends FormRequest
{
    protected $errorBag = 'invite';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('owner-only', $this->project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $invited = array_merge($this->project->members->pluck('email')->toArray(), [$this->project->owner->email]);
        return [
            'email' => [
                'required',
                'exists:users,email',
                Rule::notIn($invited),
            ]
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => 'Email :input hasn\'t been registered in the system',
            'email.not_in' => 'The user with email :input is already a member/owner'
        ];
    }
}
