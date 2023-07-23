<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class EventEditRequest extends FormRequest
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
            'event_title'       => 'sometimes|required|string|max:200',
            'event_start_date'  => 'sometimes|required|date|before:event_end_date',
            'event_end_date'    => 'sometimes|required|date|after:event_start_date',
            // 'event_duration'    => 'sometimes|required|numeric|max:12',
        ];
    }


    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            // Replace the missing by the current ones
            'event_start_date' =>   $this->input('event_start_date') ?? $this->route('event')->event_start_date,
            'event_end_date' =>    $this->input('event_end_date') ?? $this->route('event')->event_end_date,
        ]);
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
