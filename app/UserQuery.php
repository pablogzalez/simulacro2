<?php

namespace App;

class UserQuery extends QueryBuilder
{
    public function findByEmail($email)
    {
        return $this->where(compact('email'))->first();
    }

    public function withLastLogin()
    {
        $subselect = Login::select('logins.created_at')
            ->whereColumn('logins.user_id', 'users.id')
            ->latest() // orderByDesc('created_at')
            ->limit(1);

        return $this->addSelect([
            'last_login_at' => $subselect,
        ]);
    }
}
