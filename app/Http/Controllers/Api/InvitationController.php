<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiCommonController;
use App\Http\Requests\RegisterRequest;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\Facades\Hashids;

class InvitationController extends ApiCommonController
{
    public function invitation(Request $request, $xid)
    {
        $convertedId = Hashids::decode($xid);
        $id = $convertedId[0];
        $invitation = Invitation::select('name', 'email', 'company_id')
            ->with(['company:id,name,is_supper'])
            ->where('id', $id)
            ->first();

        if (!$invitation) {
            return $this->error('Invitation code not exists');
        }

        $data = [
            'data' => $invitation,
        ];

        return $this->success('Information fetched successfully', $data);
    }

    public function register(RegisterRequest $request, $xid)
    {
        $convertedId = Hashids::decode($xid);
        $id = $convertedId[0];

        $invitation = Invitation::select('name', 'email', 'company_id', 'role')
            ->with(['company:id,name,is_supper'])
            ->where('id', $id)
            ->first();

        if ($invitation) {
            $isGlobalCompany = $invitation->company->is_supper;
            $companyId = $invitation->company->id;

            if ($isGlobalCompany) {
                $company = new Company();
                $company->name = $request->company_name;
                $company->email = $request->email;
                $company->save();

                $companyId = $company->id;
            }

            $user = new User();
            $user->company_id = $companyId;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->status = 'enabled';
            $user->roles = $invitation->role;
            $user->save();


            $totalUsers = User::where('company_id', $companyId)->count();
            Company::where('id', $companyId)->update(['total_users' => $totalUsers]);

            return $this->success('Registration completed successfully. Now you can login.');
        } else {
            return $this->error('Invitation not exists');
        }
    }
}
