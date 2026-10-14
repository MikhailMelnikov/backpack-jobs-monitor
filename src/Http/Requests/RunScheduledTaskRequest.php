<?php

declare(strict_types=1);

namespace MikhailMelnikov\BackpackJobsMonitor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MikhailMelnikov\JobsMonitor\Enum\RunMode;

/**
 * Request to run a scheduler task manually.
 */
class RunScheduledTaskRequest extends FormRequest
{
    /**
     * Only an authenticated Backpack user may run tasks.
     */
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    /**
     * Validation rules.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'mode' => ['required', Rule::enum(RunMode::class)],
        ];
    }

    /**
     * Chosen run mode.
     */
    public function mode(): RunMode
    {
        return RunMode::from((string) $this->validated('mode'));
    }
}
