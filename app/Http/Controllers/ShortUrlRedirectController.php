<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\UrlShortner;
use App\Models\User;
use Vinkla\Hashids\Facades\Hashids;

class ShortUrlRedirectController extends Controller
{
    public function redirect($xid)
    {
        $convertedId = Hashids::decode($xid);
        $id = $convertedId[0];

        $shortUrl = UrlShortner::where('id', $id)->first();

        if (!$shortUrl) {
            abort(404, 'Short URL not found');
        }

        // Increment hits for short_url
        $shortUrl->increment('hits');

        // Increment total_url_hits for company
        Company::where('id', $shortUrl->company_id)->increment('total_url_hits');

        // Increment total_url_hits for user
        User::where('id', $shortUrl->created_by)->increment('total_url_hits');

        // Redirect to original URL
        return redirect($shortUrl->url);
    }
}
