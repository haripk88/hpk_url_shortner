<?php

namespace App\Http\Controllers\Api\Superadmin;

use App\Http\Controllers\ApiCommonController;
use App\Http\Requests\Api\SuperAdmin\Company\CompanyIndexRequest;
use App\Http\Requests\Api\SuperAdmin\Company\CompanyInviteRequest;
use App\Mail\InviteMailCompany;
use App\Models\Company;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class CompanyController extends ApiCommonController
{
    public function index(CompanyIndexRequest $request)
    {
        $perPage = $request->per_page ?? 10;

        $users = Company::select('id', 'name', 'email', 'total_users', 'total_urls', 'total_url_hits', 'address')
            ->where('is_supper', 0)
            ->paginate($perPage);

        return $this->success('Company fetched successfully', [
            'data'   => $users->items(),
            'meta'      => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total()
            ]
        ]);
    }

    public function invite(CompanyInviteRequest $request)
    {
        $name = $request->name;
        $email = $request->email;
        $globalCompany = Company::select('id', 'name')->where('is_supper', 1)->first();
        $globalCompanyName = $globalCompany ? $globalCompany->name : '';

        $invitation = Invitation::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'email' => $email,
                'company_id'  => $globalCompany->id,
                'role'  => 'admin',
            ]
        );
        $xid = Hashids::encode($invitation->id);
        $inviteUrl = route('app', '/invite/' . $xid);

        try {
            Mail::to($email)->send(new InviteMailCompany($name, $globalCompanyName, $inviteUrl));
        } catch (\Exception) {
            return $this->error('Failed to send invitation email. Please check mail configuration.', 403);
        }

        return $this->success('Invitation send successfully');
    }
}
