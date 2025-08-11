<?php
namespace App\Events;

class AuditEvents
{
    public const AUTH_LOGIN_FAIL = 'audit:auth.login.fail';
    public const AUTH_LOGIN_SUCCESS = 'audit:auth.login.success';
    public const MEDIA_UPLOAD = 'audit:media.upload';
    public const MEDIA_DELETE = 'audit:media.delete';
    public const RBAC_ROLE_PERMIT = 'audit:rbac.role.permit';
    public const PASSWORD_RESET_REQUEST = 'audit:password.reset.request';
    public const PASSWORD_RESET_CONSUME = 'audit:password.reset.consume';
}
