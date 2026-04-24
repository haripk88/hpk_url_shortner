<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiCommonController;
use App\Http\Requests\Api\Admin\TeamMembers\TeamMembersIndexRequest;
use App\Http\Requests\Api\Admin\TeamMembers\TeamMembersInviteRequest;
use App\Mail\InviteMailClient;
use App\Mail\InviteMailTeamMember;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class UserController extends ApiCommonController
{
    public function index(TeamMembersIndexRequest $request)
    {
        $user = $request->user();
        $perPage = $request->per_page ?? 10;

        $users = User::select('id', 'name', 'email', 'roles', 'total_urls', 'total_url_hits')
            ->where('users.company_id', $user->company_id)
            ->paginate($perPage);

        return $this->success('Team members fetched successfully', [
            'data'   => $users->items(),
            'meta'      => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total()
            ]
        ]);
    }

    public function invite(TeamMembersInviteRequest $request)
    {
        $name = $request->name;
        $email = $request->email;
        $company = $request->user()->company;
        $companyName = $company->name ?? '';

        $invitation = Invitation::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'email' => $email,
                'company_id'  => $company->id,
                'role'  => 'admin',
            ]
        );
        $xid = Hashids::encode($invitation->id);
        $inviteUrl = route('app', '/invite/' . $xid);

        try {
            Mail::to($email)->send(new InviteMailTeamMember($name, $companyName, $inviteUrl));
        } catch (\Exception) {
            return $this->error('Failed to send invitation email. Please check mail configuration.', 403);
        }

        return $this->success('Invitation send successfully');
    }
}
