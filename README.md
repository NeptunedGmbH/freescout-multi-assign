# Multi Assign Module for FreeScout

A [FreeScout](https://freescout.net/) module that lets you assign multiple additional users to a conversation alongside the primary assignee.

![FreeScout](https://img.shields.io/badge/FreeScout-%3E%3D1.8.181-blue)
![License](https://img.shields.io/badge/license-AGPL--3.0-green)

## Features

- **Additional assignees** — Add one or more extra users to any conversation without changing the primary assignee.
- **Conversation sidebar dropdown** — A user+ icon in the conversation info bar opens a checkbox dropdown to toggle assignees on/off.
- **New conversation support** — Select additional assignees when creating a new conversation.
- **"Mine" folder integration** — Optionally show additionally-assigned conversations in each user's Mine folder so they see everything they're responsible for.
- **Notifications** — Optionally send email notifications to additional assignees (same notifications the primary assignee receives).
- **Conversation list column** — Optionally show an "Additional Assignees" column in the conversation list view.
- **Max assignees limit** — Set a cap on how many additional assignees a conversation can have (0 = unlimited).
- **Activity log** — Every add/remove is recorded as a conversation activity thread (":person added/removed :user as an additional assignee").
- **Primary assignee sync** — If the primary assignee is changed to someone who is already an additional assignee, that person is automatically removed from the additional list.
- **Conversation merge** — When conversations are merged, additional assignees from the source are carried over to the target (deduplicated).
- **Search filter** — Search conversations by additional assignee using the `additional_assignee` filter.
- **Webhook event** — Fires a `convo.additional_assignee.changed` webhook event (requires the Api Webhooks module).
- **Export support** — Adds an "Additional Assignees" field to the conversation export (requires the Export Conversations module).
- **Permission control** — A dedicated "Users are allowed to manage additional assignees" permission that can be granted to non-admin users.
- **Cleanup** — Assignee records are automatically removed when conversations are permanently deleted.

## Requirements

- FreeScout >= 1.8.181

## Installation

1. Download or clone this repository into your FreeScout `Modules` directory:

   ```bash
   cd /path/to/freescout/Modules
   git clone https://github.com/tombruton87/freescout-multi-assign.git MultiAssign
   ```

2. Run database migrations:

   ```bash
   php artisan module:migrate MultiAssign
   ```

3. Activate the module in **Manage > Modules**.

## Configuration

After activation, go to **Manage > Settings > Multi Assign** to configure:

| Setting | Description | Default |
|---------|-------------|---------|
| **Assigned Folder** | Show additionally-assigned tickets in the user's "Mine" folder | Off |
| **Notifications** | Send notifications to additional assignees | Off |
| **Conversation List** | Show "Additional Assignees" column in the conversation list | Off |
| **Max Assignees** | Maximum additional assignees per conversation (0 = unlimited) | 0 |

## Usage

### Adding/removing assignees on an existing conversation

1. Open a conversation.
2. In the conversation info bar, click the **user+** icon (or the assignee names next to it).
3. A dropdown appears listing all users with mailbox access (excluding the current primary assignee).
4. Click a user to toggle them on/off — a checkmark indicates they are assigned.
5. Click **Clear all** to remove all additional assignees at once.

### Adding assignees when creating a conversation

1. When composing a new conversation, an **Additional Assignees** dropdown appears below the subject line.
2. Select the users you want to assign before sending.

### Permissions

By default, only admins can manage additional assignees. To allow non-admin users:

1. Go to **Manage > Users** and edit the user.
2. Under **Permissions**, enable **"Users are allowed to manage additional assignees"**.

## How It Works

The module stores additional assignments in a `multi_assign_conversation_user` pivot table with a unique constraint on `(conversation_id, user_id)`. It hooks into FreeScout's event system to integrate with folders, notifications, search, webhooks, merge, export, and cleanup — no core files are modified.

## License

[AGPL-3.0](https://www.gnu.org/licenses/agpl-3.0.en.html)
