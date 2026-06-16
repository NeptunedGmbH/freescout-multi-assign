<?php

namespace Modules\MultiAssign\Http\Controllers;

use App\Conversation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\MultiAssign\Entities\ConversationAssignee;
use Modules\MultiAssign\Providers\MultiAssignServiceProvider;

class MultiAssignController extends Controller
{
    public function toggle(Request $request)
    {
        $response = ['status' => 'error', 'msg' => ''];

        $conversation = Conversation::find($request->conversation_id);
        $user = auth()->user();

        if (!$conversation) {
            $response['msg'] = __('Conversation not found');
            return \Response::json($response);
        }

        if (!$user->can('update', $conversation)) {
            $response['msg'] = __('Not enough permissions');
            return \Response::json($response);
        }

        if (!MultiAssignServiceProvider::userCanMultiAssign($user)) {
            $response['msg'] = __('Not enough permissions');
            return \Response::json($response);
        }

        $target_user_id = (int) $request->user_id;

        if (!$conversation->mailbox->userHasAccess($target_user_id)) {
            $response['msg'] = __('User does not have access to this mailbox');
            return \Response::json($response);
        }

        if ($conversation->user_id == $target_user_id) {
            $response['msg'] = __('This user is already the primary assignee');
            return \Response::json($response);
        }

        $was_assigned = ConversationAssignee::isAssigned($conversation->id, $target_user_id);

        if (!$was_assigned) {
            $max = MultiAssignServiceProvider::getMaxAssignees();
            if ($max > 0) {
                $current_count = count(ConversationAssignee::getAssigneeIds($conversation->id));
                if ($current_count >= $max) {
                    $response['msg'] = __('Maximum of :max additional assignees reached', ['max' => $max]);
                    return \Response::json($response);
                }
            }
        }

        try {
            ConversationAssignee::toggle($conversation->id, $target_user_id, $user->id);
        } catch (\Illuminate\Database\QueryException $e) {
            $response['msg'] = __('Assignment could not be updated, please try again');
            return \Response::json($response);
        }

        $target_user = User::find($target_user_id);
        if ($target_user) {
            $action = $was_assigned ? 'removed' : 'added';
            MultiAssignServiceProvider::createActivityThread($conversation, $user, $target_user, $action);
        }

        MultiAssignServiceProvider::fireWebhookEvent($conversation);

        $assignees = ConversationAssignee::getAssignees($conversation->id);
        $names = $assignees->map(function ($u) {
            return $u->first_name.' '.$u->last_name;
        })->toArray();

        $response['status'] = 'success';
        $response['assignees'] = $assignees->map(function ($u) {
            return ['id' => $u->id, 'name' => $u->first_name.' '.$u->last_name];
        })->toArray();
        $response['label'] = count($names) ? implode(', ', $names) : __('None');

        \Eventy::action('multiassign.toggled', $conversation, $target_user_id, $user);

        return \Response::json($response);
    }

    public function clearAll(Request $request)
    {
        $response = ['status' => 'error', 'msg' => ''];

        $conversation = Conversation::find($request->conversation_id);
        $user = auth()->user();

        if (!$conversation) {
            $response['msg'] = __('Conversation not found');
            return \Response::json($response);
        }

        if (!$user->can('update', $conversation)) {
            $response['msg'] = __('Not enough permissions');
            return \Response::json($response);
        }

        if (!MultiAssignServiceProvider::userCanMultiAssign($user)) {
            $response['msg'] = __('Not enough permissions');
            return \Response::json($response);
        }

        $assignee_ids = ConversationAssignee::getAssigneeIds($conversation->id);

        if (empty($assignee_ids)) {
            $response['msg'] = __('No additional assignees to remove');
            return \Response::json($response);
        }

        ConversationAssignee::where('conversation_id', $conversation->id)->delete();

        $target_users = User::whereIn('id', $assignee_ids)->get();
        foreach ($target_users as $target_user) {
            MultiAssignServiceProvider::createActivityThread($conversation, $user, $target_user, 'removed');
        }

        MultiAssignServiceProvider::fireWebhookEvent($conversation);

        $response['status'] = 'success';
        $response['assignees'] = [];
        $response['label'] = __('None');

        return \Response::json($response);
    }
}
