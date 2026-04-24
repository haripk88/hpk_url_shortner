<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exports\AdminUrlShortnerExport;
use App\Http\Controllers\ApiCommonController;
use App\Http\Requests\Api\Admin\UrlShortner\UrlShortnerDownloadRequest;
use App\Http\Requests\Api\Admin\UrlShortner\UrlShortnerGenerateRequest;
use App\Http\Requests\Api\Admin\UrlShortner\UrlShortnerIndexRequest;
use App\Models\Company;
use App\Models\UrlShortner;
use App\Models\User;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class UrlShortnerController extends ApiCommonController
{
    public function index(UrlShortnerIndexRequest $request)
    {
        $user = $request->user();
        $perPage = $request->per_page ?? 10;
        $startDate = $request->start_date ?? null;
        $endDate = $request->end_date ?? null;

        $urls = UrlShortner::select('url_shortners.id', 'url_shortners.url', 'url_shortners.hits', 'users.name as user', 'companies.id as client_id', 'url_shortners.created_at')
            ->join('users', 'users.id', '=', 'url_shortners.created_by')
            ->join('companies', 'companies.id', '=', 'users.company_id')
            ->where('url_shortners.company_id', $user->company_id);

        if ($user->roles === 'member') {
            $urls->where('url_shortners.created_by', $user->id);
        }

        if ($startDate && $endDate) {
            $urls->whereBetween('url_shortners.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $urls = $urls->orderBy('created_at', 'desc')->paginate($perPage);

        return $this->success('Short Urls fetched successfully', [
            'data'   => $urls->items(),
            'meta'      => [
                'current_page' => $urls->currentPage(),
                'last_page' => $urls->lastPage(),
                'per_page' => $urls->perPage(),
                'total' => $urls->total()
            ]
        ]);
    }

    public function download(UrlShortnerDownloadRequest $request)
    {
        $user = $request->user();
        $startDate = $request->start_date ?? null;
        $endDate = $request->end_date ?? null;

        $urls = UrlShortner::select('url_shortners.id', 'url_shortners.url', 'url_shortners.hits', 'users.name as user', 'url_shortners.created_at')
            ->join('users', 'users.id', '=', 'url_shortners.created_by')
            ->join('companies', 'companies.id', '=', 'users.company_id')
            ->where('url_shortners.company_id', $user->company_id);

        if ($user->roles === 'member') {
            $urls->where('url_shortners.created_by', $user->id);
        }

        if ($startDate && $endDate) {
            $urls->whereBetween('url_shortners.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $urls = $urls->orderBy('created_at', 'desc')->get();

        return Excel::download(new AdminUrlShortnerExport($urls), 'urls.csv');
    }

    public function generate(UrlShortnerGenerateRequest $request)
    {
        $user = $request->user();

        $UrlShortner = new UrlShortner();
        $UrlShortner->company_id = $user->company_id;
        $UrlShortner->url = $request->url;
        $UrlShortner->created_by = $user->id;
        $UrlShortner->save();

        $userTotalUrls = UrlShortner::where('created_by', $user->id)->count();
        $companyTotalUrls = UrlShortner::where('company_id', $user->company_id)->count();

        Company::where('id', $user->company_id)->update(['total_urls' => $companyTotalUrls]);
        User::where('id', $user->id)->update(['total_urls' => $userTotalUrls]);

        return $this->success('Short URL generated successfully');
    }
}
