<?php

declare(strict_types=1);

namespace App\Http\Traits;

use Illuminate\Http\{Request, RedirectResponse, JsonResponse};

trait HelperTrait
{
    public function serverErrorMessage(): string
    {
        return 'An error occured, please try again or contact support if the problem persists.';
    }

    public function validateEmail(string $email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function invalidEmailMessage(): string
    {
        return 'Invalid email.';
    }

    public function getStates(): array
    {
        $states = [
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'DC' => 'District Of Columbia',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming',
        ];

        return $states;
    }

    public function getMimeType(string $file): string
    {
        $mimeType = '';

        if (file_exists($file)) {
            $mimeType = mime_content_type($file);
        }

        return $mimeType;
    }

    public function viewFile(string $file, string $title)
    {
        $contentType = $this->getMimeType($file);
        if ($contentType) {
            $headers = [
                'Content-Type' => $contentType
            ];

            return response()->download($file, $title, $headers, 'inline');
        } else {
            abort(404, 'Invalid content type!');
        }
    }

    public function sanitizeFileName(string $path): string
    {
        if ($path) {
            $fileName = basename($path);
            $fileName = preg_replace('/[^a-zA-Z0-9\-]/', '_', $fileName);
            return $fileName;
        }
    }

    public function sanitizeDir(string $folder): string
    {
        if ($folder) {
            $folder = preg_replace('/[\W]/', '_', $folder);
            return $folder;
        }
    }

    public function escapeSqlLike(?string $value): string
    {
        if ($value) {
            $value = preg_replace('/\%/', '', $value);
            return $value;
        }

        return '';
    }

    public function sanitizeInput(string $value): string
    {
        $value = strip_tags($value);

        return $value;
    }

    public function sanitizeOutput(string $value): string
    {
        $value = htmlentities($value, ENT_NOQUOTES, 'UTF-8', false);

        return $value;
    }

    public function responseJsonError(string $message): JsonResponse
    {
        $response['type'] = 'error';
        $response['message'] = $message;

        return response()->json($response);
    }

    public function responseJsonSuccess(object $data): JsonResponse
    {
        $response['type'] = 'success';
        $response['data'] = $data;

        return response()->json($response);
    }

    public function backWithSuccess(string $message): RedirectResponse
    {
        return back()->with('success', $message);
    }

    public function backWithError(string $message): RedirectResponse
    {
        return back()->with('error', $message);
    }

    public function getMonthCharFromAlphabet(int $month)
    {
        $monthCharMap = [];
        $c = 1;

        foreach (range('A', 'L') as $v) {
            $monthCharMap[$c] = $v;
            ++$c;
        }

        return $monthCharMap[$month];
    }

    public function setDefaultNull($var)
    {
        if (empty($var)) {
            return null;
        }

        if ($var == 0) {
            return null;
        }

        return $var;
    }

    public function th($name, $style = '')
    {
        $style = "width:250px;";
        $style .= "text-align: center;";
        return "<th style='{$style}'><strong>{$name}</strong></th>";
    }
    public function td($name, $style = '')
    {
        $style .= "text-align: center;";
        return "<td style='{$style}'>{$name}</td>";
    }

    public function temp_id()
    {
        return uniqid((string)microtime(true));
    }
}
