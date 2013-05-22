<?php
abstract class MLCGitLogin {
	const Endpoint = '/login/oauth/authorize';
	const client_id = 'client_id';//Required string - The client ID you received from GitHub when you registered.
	const redirect_uri = 'redirect_url';//Optional string - URL in your app where users will be sent after authorization. See details below about redirect urls.
	const scope = 'scope';//ptional string - Comma separated list of scopes.
	const state = 'state';//Optional string - An unguessable random string. It is used to protect against cross-site request forgery attacks.
}
