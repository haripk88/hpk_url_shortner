<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Exports\UrlShortnerExport;
use App\Http\Controllers\ApiCommonController;
use App\Http\Requests\Api\SuperAdmin\UrlShortners\UrlShortnersDownloadRequest;
use App\Http\Requests\Api\SuperAdmin\UrlShortners\UrlShortnerIndexRequest;
use App\Models\UrlShortner;
use Maatwebsite\Excel\Facades\Excel;

class UrlShortnerController extends ApiCommonController
{
    public function index(UrlShortnerIndexRequest $request)
    {
        $perPage = $request->per_page ?? 10;
        $companyId = $request->company_id ?? null;
        $startDate = $request->start_date ?? null;
        $endDate = $request->end_date ?? null;

        $urls = UrlShortner::select('url_shortners.id', 'url_shortners.url', 'url_shortners.hits', 'companies.name as client', 'companies.id as client_id', 'url_shortners.created_at')
            ->join('users', 'users.id', '=', 'url_shortners.created_by')
            ->join('companies', 'companies.id', '=', 'users.company_id');

        if ($companyId) {
            $urls->where('url_shortners.company_id', $companyId);
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

    public function download(UrlShortnersDownloadRequest $request)
    {
        $companyId = $request->company_id ?? null;
        $startDate = $request->start_date ?? null;
        $endDate = $request->end_date ?? null;

        $urls = UrlShortner::select('url_shortners.id', 'url_shortners.url', 'url_shortners.hits', 'companies.name as client', 'url_shortners.created_at')
            ->join('users', 'users.id', '=', 'url_shortners.created_by')
            ->join('companies', 'companies.id', '=', 'users.company_id');

        if ($companyId) {
            $urls->where('url_shortners.company_id', $companyId);
        }

        if ($startDate && $endDate) {
            $urls->whereBetween('url_shortners.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $urls = $urls->orderBy('created_at', 'desc')->get();

        return Excel::download(new UrlShortnerExport($urls), 'urls.csv');
    }
}
