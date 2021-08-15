<?php

namespace App\Models\Facebook;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Propaganistas\LaravelPhone\PhoneNumber;

class File extends Model
{

    use HasFactory;

    protected $table = 'facebook_files';

    protected $guarded = [];
    const NEW_STATUS = 0;
    const IN_PROGRESS_STATUS = 1;
    const IDLE_STATUS = 2;
    const COMPLETE_STATUS = 3;

    function scopeCanStartImport($q)
    {
        $q->where('status', self::IDLE_STATUS)->orWhere('status', self::NEW_STATUS)->orWhere(function ($q) {
            $q->where('updated_at', '<', Carbon::now()->subMinutes(3))->where('status', '!=', self::COMPLETE_STATUS);
        });
    }

    function mapUser($raw)
    {
        if ($this->file_name == 'Palestine.txt') {
            if (@$raw[3]) {
                try {
                    $mobile = PhoneNumber::make($raw[3], 'PS');

                    return [
                        'mobile_local' => clean_mobile($mobile->formatE164()),
                        'country_code' => $mobile->getCountry(),
                        'mobile' => clean_mobile($mobile->formatInternational()),
                        'facebook_id' => $raw[0] ?: null,
                        'facebook_custom_id' => $raw[11] ?: null,
                        'facebook_url' => $raw[9] ?: null,
                        'full_name' => $raw[12] ?: null,
                        'first_name' => $raw[6] ?: null,
                        'last_name' => $raw[7] ?: null,
                        'gender' => $raw[8] ?: null,
                        'city' => $raw[15] ?: null,
                        'location' => $raw[16] ?: null,
                        'social_status' => $raw[25] ?: null,
                        'job' => $raw[13] ?: null,
                        'company' => $raw[14] ?: null,
                        'career' => $raw[15] ?: null,
                        'collage_name' => $raw[18] ?: null,
                        'email' => $raw[19] ?: null,
                        'graduate_year' => null,
                        'birth_day' => null,
                        'facebook_file_id' => $this->id,
                    ];
                } catch (\Exception $e) {
                    Log::alert($e->getMessage());
                }
            }
        } else if ($this->file_name == 'israel.txt') {
            if (@$raw[0] || @$raw[10]) {
                $mobileCount = 0;
                try {
                    $mobile = PhoneNumber::make("+" . $raw[0], 'IS');
                    $mobileCount = 1;
                } catch (\Exception $e) {
                    Log::alert($e->getMessage());
                    try {
                        $mobile = PhoneNumber::make("+" . $raw[10], 'IS');
                        $mobileCount = 2;
                    } catch (\Exception $e) {
                        $mobileError = false;
                        Log::alert($e->getMessage());
                    }
                }

                try {
                    if ($mobileCount)
                        return [
                            'mobile_local' => clean_mobile($mobile->formatE164()),
                            'country_code' => $mobile->getCountry(),
                            'mobile' => clean_mobile($mobile->formatInternational()),
                            'facebook_id' => $mobileCount == 1 ? $raw[1] : $raw[11],
                            'first_name' => $mobileCount == 1 ? $raw[2] : $raw[9],
                            'last_name' => $mobileCount == 1 ? $raw[3] : $raw[8],
                            'gender' => $mobileCount == 1 ? $raw[4] : $raw[7],
                            'birthday' => $raw[7] ?: null,
                            'location' => $raw[5] ?: null,
                            'city' => $raw[6] ?: null,
                            'social_status' => $raw[7] ?: null,
                            'job' => $raw[8] ?: null,
                            'email' => $raw[9] ?: null,
                            'facebook_file_id' => $this->id,
                        ];
                } catch (\Exception $e) {
                    Log::alert($e->getMessage().":".$e->getFile().":".$e->getLine(),is_array($raw)?$raw:[$raw]);
                }
            }
        }
        return [];

    }

    function lineToArray($line)
    {
        if (in_array($this->file_name, ['Palestine.txt'])) {
            return explode(',', $line);

        } else if (in_array($this->file_name, ['israel.txt'])) {
            return explode(':', $line);
        }
        return [];
    }

    function getIdData($raw)
    {
        if ($this->file_name == 'Palestine.txt') {
            return $raw[0]??'';
        } elseif ($this->file_name == 'israel.txt') {
            return $raw[1]??'';
        }
    }
}

