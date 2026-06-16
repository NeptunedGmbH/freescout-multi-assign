<?php

namespace Modules\MultiAssign\Entities;

use Illuminate\Database\Eloquent\Model;

class ConversationAssignee extends Model
{
    protected $table = 'multi_assign_conversation_user';

    public $timestamps = false;

    protected $fillable = ['conversation_id', 'user_id', 'added_by_user_id', 'created_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }

    public static function getAssignees($conversation_id)
    {
        return self::where('conversation_id', $conversation_id)
            ->join('users', 'users.id', '=', 'multi_assign_conversation_user.user_id')
            ->select('users.id', 'users.first_name', 'users.last_name', 'users.email')
            ->get();
    }

    public static function getAssigneeIds($conversation_id)
    {
        return self::where('conversation_id', $conversation_id)
            ->pluck('user_id')
            ->toArray();
    }

    public static function isAssigned($conversation_id, $user_id)
    {
        return self::where('conversation_id', $conversation_id)
            ->where('user_id', $user_id)
            ->exists();
    }

    public static function toggle($conversation_id, $user_id, $added_by_user_id)
    {
        $existing = self::where('conversation_id', $conversation_id)
            ->where('user_id', $user_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return false;
        }

        self::create([
            'conversation_id' => $conversation_id,
            'user_id'         => $user_id,
            'added_by_user_id' => $added_by_user_id,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return true;
    }
}
