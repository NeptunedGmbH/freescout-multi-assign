<div class="block">
    <div class="block-header">
        <h2>{{ __('Multi Assign') }}</h2>
    </div>
    <div class="block-content">
        <form class="form-horizontal margin-bottom" method="POST" action="{{ route('settings', ['section' => 'multiassign']) }}">
            {{ csrf_field() }}

            <div class="form-group">
                <label class="col-sm-2 control-label">{{ __('Assigned Folder') }}</label>
                <div class="col-sm-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="settings[multi_assign.show_in_assigned_folder]" value="1" {{ !empty($settings['multi_assign.show_in_assigned_folder']) ? 'checked' : '' }}>
                            {{ __('Show additionally-assigned tickets in the user\'s "Mine" folder') }}
                        </label>
                    </div>
                    <p class="help-block">{{ __('When enabled, tickets where a user is an additional assignee will also appear in their Mine folder.') }}</p>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">{{ __('Notifications') }}</label>
                <div class="col-sm-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="settings[multi_assign.notify_additional_assignees]" value="1" {{ !empty($settings['multi_assign.notify_additional_assignees']) ? 'checked' : '' }}>
                            {{ __('Send notifications to additional assignees') }}
                        </label>
                    </div>
                    <p class="help-block">{{ __('When enabled, additional assignees receive the same email notifications as the primary assignee.') }}</p>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">{{ __('Conversation List') }}</label>
                <div class="col-sm-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="settings[multi_assign.show_list_column]" value="1" {{ !empty($settings['multi_assign.show_list_column']) ? 'checked' : '' }}>
                            {{ __('Show "Additional Assignees" column in the conversation list') }}
                        </label>
                    </div>
                    <p class="help-block">{{ __('When enabled, an extra column appears in the conversation list showing additional assignees.') }}</p>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">{{ __('Max Assignees') }}</label>
                <div class="col-sm-6">
                    <input type="number" class="form-control" name="settings[multi_assign.max_assignees]" value="{{ $settings['multi_assign.max_assignees'] ?? 0 }}" min="0" max="50" style="width:100px;">
                    <p class="help-block">{{ __('Maximum number of additional assignees per conversation. Set to 0 for unlimited.') }}</p>
                </div>
            </div>

            <div class="form-group margin-top margin-bottom">
                <div class="col-sm-6 col-sm-offset-2">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
