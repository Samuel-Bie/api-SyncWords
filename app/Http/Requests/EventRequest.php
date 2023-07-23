<?php

namespace App\Http\Requests;

use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // this is not necessary since we will collect this information from the token
            // 'organization_id'   => 'required|integer|exists:authorizations,id',

            'event_title'       => 'required|string|max:200',
            'event_start_date'  => 'required|date|before:event_end_date',
            'event_end_date'    => 'required|date|after:event_start_date',
            // 'event_duration'    => 'required|numeric|max:12',
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                try {
                    $date1 = new Carbon($this->input('event_start_date'));
                    $date2 = (new Carbon($this->input('event_end_date')));
                    $duration =  $date1
                        ->diffInRealHours($date2);
                    if ($duration > 12) {
                        $validator->errors()->add(
                            'event_duration',
                            'Event duration must be less than 12 hours.'
                        );
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        ];
    }
}
