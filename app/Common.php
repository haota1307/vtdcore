<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */
// Preload settings early to reduce per-request DB hits
if (! function_exists('_preload_settings_once')) {
	function _preload_settings_once(): void {
		static $done = false; if ($done) return; $done = true;
		try { service('settings')->preloadAll(); } catch (\Throwable $e) { /* ignore */ }
	}
	_preload_settings_once();
}

if (! function_exists('audit_event')) {
	function audit_event(string $name, array $payload = []): void {
		try { \CodeIgniter\Events\Events::trigger('audit', $name, $payload); } catch (\Throwable $e) { /* ignore */ }
	}
}
