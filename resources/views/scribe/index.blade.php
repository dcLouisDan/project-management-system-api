<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Project Management System API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.3.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.3.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-auth-login">
                                <a href="#endpoints-POSTapi-v1-auth-login">Attempt to authenticate a new session.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-auth-logout">
                                <a href="#endpoints-POSTapi-v1-auth-logout">Destroy an authenticated session.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-auth-forgot-password">
                                <a href="#endpoints-POSTapi-v1-auth-forgot-password">Send a reset link to the given user.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-auth-reset-password">
                                <a href="#endpoints-POSTapi-v1-auth-reset-password">Reset the user's password.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-auth-register">
                                <a href="#endpoints-POSTapi-v1-auth-register">Create a new registered user.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-PUTapi-v1-auth-user-profile-information">
                                <a href="#endpoints-PUTapi-v1-auth-user-profile-information">Update the user's profile information.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-PUTapi-v1-auth-user-password">
                                <a href="#endpoints-PUTapi-v1-auth-user-password">Update the user's password.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-auth-user-confirmed-password-status">
                                <a href="#endpoints-GETapi-v1-auth-user-confirmed-password-status">Get the password confirmation status.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-auth-user-confirm-password">
                                <a href="#endpoints-POSTapi-v1-auth-user-confirm-password">Confirm the user's password.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-auth-two-factor-challenge">
                                <a href="#endpoints-POSTapi-v1-auth-two-factor-challenge">Attempt to authenticate a new session using the two factor authentication code.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-auth-user-two-factor-authentication">
                                <a href="#endpoints-POSTapi-v1-auth-user-two-factor-authentication">Enable two factor authentication for the user.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-auth-user-confirmed-two-factor-authentication">
                                <a href="#endpoints-POSTapi-v1-auth-user-confirmed-two-factor-authentication">Enable two factor authentication for the user.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-v1-auth-user-two-factor-authentication">
                                <a href="#endpoints-DELETEapi-v1-auth-user-two-factor-authentication">Disable two factor authentication for the user.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-auth-user-two-factor-qr-code">
                                <a href="#endpoints-GETapi-v1-auth-user-two-factor-qr-code">Get the SVG element for the user's two factor authentication QR code.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-auth-user-two-factor-secret-key">
                                <a href="#endpoints-GETapi-v1-auth-user-two-factor-secret-key">Get the current user's two factor authentication setup / secret key.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-auth-user-two-factor-recovery-codes">
                                <a href="#endpoints-GETapi-v1-auth-user-two-factor-recovery-codes">Get the two factor authentication recovery codes for authenticated user.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-auth-user-two-factor-recovery-codes">
                                <a href="#endpoints-POSTapi-v1-auth-user-two-factor-recovery-codes">Generate a fresh set of two factor authentication recovery codes.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-user">
                                <a href="#endpoints-GETapi-v1-user">GET api/v1/user</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-project-management" class="tocify-header">
                <li class="tocify-item level-1" data-unique="project-management">
                    <a href="#project-management">Project Management</a>
                </li>
                                    <ul id="tocify-subheader-project-management" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="project-management-GETapi-v1-projects">
                                <a href="#project-management-GETapi-v1-projects">List projects</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-management-GETapi-v1-projects--project_id-">
                                <a href="#project-management-GETapi-v1-projects--project_id-">Get project</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-management-POSTapi-v1-projects">
                                <a href="#project-management-POSTapi-v1-projects">Create project</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-management-PUTapi-v1-projects--project_id-">
                                <a href="#project-management-PUTapi-v1-projects--project_id-">Update project</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-management-DELETEapi-v1-projects--project_id-">
                                <a href="#project-management-DELETEapi-v1-projects--project_id-">Soft delete project</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-management-POSTapi-v1-projects--projectId--restore">
                                <a href="#project-management-POSTapi-v1-projects--projectId--restore">Restore soft-deleted project</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-management-POSTapi-v1-projects--project_id--manager">
                                <a href="#project-management-POSTapi-v1-projects--project_id--manager">Set project manager</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-management-POSTapi-v1-projects--project_id--teams">
                                <a href="#project-management-POSTapi-v1-projects--project_id--teams">Assign teams to project</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-team-management" class="tocify-header">
                <li class="tocify-item level-1" data-unique="team-management">
                    <a href="#team-management">Team Management</a>
                </li>
                                    <ul id="tocify-subheader-team-management" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="team-management-GETapi-v1-teams">
                                <a href="#team-management-GETapi-v1-teams">List teams</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="team-management-POSTapi-v1-teams">
                                <a href="#team-management-POSTapi-v1-teams">Create team</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="team-management-GETapi-v1-teams--team_id-">
                                <a href="#team-management-GETapi-v1-teams--team_id-">Get team</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="team-management-PUTapi-v1-teams--team_id-">
                                <a href="#team-management-PUTapi-v1-teams--team_id-">Update team details</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="team-management-DELETEapi-v1-teams--team_id-">
                                <a href="#team-management-DELETEapi-v1-teams--team_id-">Delete team</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="team-management-POSTapi-v1-teams--team_id--members">
                                <a href="#team-management-POSTapi-v1-teams--team_id--members">Add member to team</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="team-management-POSTapi-v1-teams--team_id--members-bulk">
                                <a href="#team-management-POSTapi-v1-teams--team_id--members-bulk">Add multiple members to team</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="team-management-DELETEapi-v1-teams--team_id--members--user_id-">
                                <a href="#team-management-DELETEapi-v1-teams--team_id--members--user_id-">Remove member from team</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="team-management-DELETEapi-v1-teams--team_id--members">
                                <a href="#team-management-DELETEapi-v1-teams--team_id--members">Remove multiple members from team</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="team-management-POSTapi-v1-teams--team_id--lead">
                                <a href="#team-management-POSTapi-v1-teams--team_id--lead">Set team leader</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="team-management-POSTapi-v1-teams--team_id--projects">
                                <a href="#team-management-POSTapi-v1-teams--team_id--projects">Assign project to team</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="team-management-DELETEapi-v1-teams--team_id--projects--project_id-">
                                <a href="#team-management-DELETEapi-v1-teams--team_id--projects--project_id-">Remove Project from team</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-user-management" class="tocify-header">
                <li class="tocify-item level-1" data-unique="user-management">
                    <a href="#user-management">User Management</a>
                </li>
                                    <ul id="tocify-subheader-user-management" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="user-management-GETapi-v1-users">
                                <a href="#user-management-GETapi-v1-users">List Users</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="user-management-GETapi-v1-users--user_id-">
                                <a href="#user-management-GETapi-v1-users--user_id-">Get user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="user-management-POSTapi-v1-users">
                                <a href="#user-management-POSTapi-v1-users">Create user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="user-management-PUTapi-v1-users--user_id-">
                                <a href="#user-management-PUTapi-v1-users--user_id-">Update user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="user-management-DELETEapi-v1-users--user_id-">
                                <a href="#user-management-DELETEapi-v1-users--user_id-">Soft delete user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="user-management-POSTapi-v1-users--userId--restore">
                                <a href="#user-management-POSTapi-v1-users--userId--restore">Restore soft-deleted user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="user-management-POSTapi-v1-users--user_id--roles">
                                <a href="#user-management-POSTapi-v1-users--user_id--roles">Assign user role</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: October 24, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-POSTapi-v1-auth-login">Attempt to authenticate a new session.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-auth-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/auth/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"gbailey@example.net\",
    \"password\": \"|]|{+-\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "gbailey@example.net",
    "password": "|]|{+-"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-login">
</span>
<span id="execution-results-POSTapi-v1-auth-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-login" data-method="POST"
      data-path="api/v1/auth/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-login"
                    onclick="tryItOut('POSTapi-v1-auth-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-login"
                    onclick="cancelTryOut('POSTapi-v1-auth-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-auth-login"
               value="gbailey@example.net"
               data-component="body">
    <br>
<p>Example: <code>gbailey@example.net</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-auth-login"
               value="|]|{+-"
               data-component="body">
    <br>
<p>Example: <code>|]|{+-</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-v1-auth-logout">Destroy an authenticated session.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-auth-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/auth/logout" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/logout"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-logout">
</span>
<span id="execution-results-POSTapi-v1-auth-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-logout" data-method="POST"
      data-path="api/v1/auth/logout"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-logout"
                    onclick="tryItOut('POSTapi-v1-auth-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-logout"
                    onclick="cancelTryOut('POSTapi-v1-auth-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-v1-auth-forgot-password">Send a reset link to the given user.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-auth-forgot-password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/auth/forgot-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/forgot-password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-forgot-password">
</span>
<span id="execution-results-POSTapi-v1-auth-forgot-password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-forgot-password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-forgot-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-forgot-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-forgot-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-forgot-password" data-method="POST"
      data-path="api/v1/auth/forgot-password"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-forgot-password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-forgot-password"
                    onclick="tryItOut('POSTapi-v1-auth-forgot-password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-forgot-password"
                    onclick="cancelTryOut('POSTapi-v1-auth-forgot-password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-forgot-password"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/forgot-password</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-forgot-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-forgot-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-v1-auth-reset-password">Reset the user&#039;s password.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-auth-reset-password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/auth/reset-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"token\": \"architecto\",
    \"password\": \"architecto\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/reset-password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "token": "architecto",
    "password": "architecto"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-reset-password">
</span>
<span id="execution-results-POSTapi-v1-auth-reset-password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-reset-password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-reset-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-reset-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-reset-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-reset-password" data-method="POST"
      data-path="api/v1/auth/reset-password"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-reset-password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-reset-password"
                    onclick="tryItOut('POSTapi-v1-auth-reset-password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-reset-password"
                    onclick="cancelTryOut('POSTapi-v1-auth-reset-password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-reset-password"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/reset-password</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-reset-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-reset-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="token"                data-endpoint="POSTapi-v1-auth-reset-password"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-auth-reset-password"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-v1-auth-register">Create a new registered user.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-auth-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/auth/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/register"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-register">
</span>
<span id="execution-results-POSTapi-v1-auth-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-register" data-method="POST"
      data-path="api/v1/auth/register"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-register"
                    onclick="tryItOut('POSTapi-v1-auth-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-register"
                    onclick="cancelTryOut('POSTapi-v1-auth-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-register"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/register</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-PUTapi-v1-auth-user-profile-information">Update the user&#039;s profile information.</h2>

<p>
</p>



<span id="example-requests-PUTapi-v1-auth-user-profile-information">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/v1/auth/user/profile-information" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/user/profile-information"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-auth-user-profile-information">
</span>
<span id="execution-results-PUTapi-v1-auth-user-profile-information" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-auth-user-profile-information"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-auth-user-profile-information"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-auth-user-profile-information" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-auth-user-profile-information">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-auth-user-profile-information" data-method="PUT"
      data-path="api/v1/auth/user/profile-information"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-auth-user-profile-information', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-auth-user-profile-information"
                    onclick="tryItOut('PUTapi-v1-auth-user-profile-information');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-auth-user-profile-information"
                    onclick="cancelTryOut('PUTapi-v1-auth-user-profile-information');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-auth-user-profile-information"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/auth/user/profile-information</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-auth-user-profile-information"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-auth-user-profile-information"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-PUTapi-v1-auth-user-password">Update the user&#039;s password.</h2>

<p>
</p>



<span id="example-requests-PUTapi-v1-auth-user-password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/v1/auth/user/password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/user/password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-auth-user-password">
</span>
<span id="execution-results-PUTapi-v1-auth-user-password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-auth-user-password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-auth-user-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-auth-user-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-auth-user-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-auth-user-password" data-method="PUT"
      data-path="api/v1/auth/user/password"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-auth-user-password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-auth-user-password"
                    onclick="tryItOut('PUTapi-v1-auth-user-password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-auth-user-password"
                    onclick="cancelTryOut('PUTapi-v1-auth-user-password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-auth-user-password"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/auth/user/password</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-auth-user-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-auth-user-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-auth-user-confirmed-password-status">Get the password confirmation status.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-auth-user-confirmed-password-status">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/auth/user/confirmed-password-status" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/user/confirmed-password-status"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-auth-user-confirmed-password-status">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
set-cookie: XSRF-TOKEN=eyJpdiI6IklKbTZNU0lXdHFhcFhhOUl6WDkwQnc9PSIsInZhbHVlIjoiT3Vvb1RPMzNibWE3bkRCR09yZ1BxS2tVQWwrTFhURkF2NWJjcHJ4dUoxZmJIeHAzcVJJQ2hoWjFYWEVPTTVuS0QxNzRBcytZTWUyV1U1MW13dis0TE5DU0pCczcvbVhEcVFWQWEvZmhhRnhFU2Z3anNOLzlVWlZvbVdTT2ZuekIiLCJtYWMiOiI2MmM0NWU0M2M1NmNhMjVlMTQ0N2NhM2FlOWE2NjNkOGIyN2U3YzEwMTQ5OGFkODI5ZTllNzcxNjYyNmQ4YzE4IiwidGFnIjoiIn0%3D; expires=Fri, 24 Oct 2025 09:20:16 GMT; Max-Age=7200; path=/; samesite=lax; project-management-system-session=eyJpdiI6InlSd2V4UDZoQjgzdFZaRjdLZVo0clE9PSIsInZhbHVlIjoiVE5HRWNHT2VpaWRjanFnTEpmOSs4ODZzRy9reHJaci85bTRSZGFha1VrYk15RkNnN2lwS0FERmp4YWozS3d6OHJHRHg2REord096L3psZnFYTkpQb3crbXl2QlJtcTJ6M0l6aCsrQmxkdVRVNHNJY1ZWVnJOVVJVU1Y3T1JXTmciLCJtYWMiOiI3MDZiY2E2N2U4NTJhZjg5YTAzYzcxNjRlZTQ5M2EwM2VhMjliNmRlZDBjZmQ5OTE0NWUyYzNkOGJjMmExOGVmIiwidGFnIjoiIn0%3D; expires=Fri, 24 Oct 2025 09:20:16 GMT; Max-Age=7200; path=/; httponly; samesite=lax
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-auth-user-confirmed-password-status" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-auth-user-confirmed-password-status"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-auth-user-confirmed-password-status"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-auth-user-confirmed-password-status" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-auth-user-confirmed-password-status">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-auth-user-confirmed-password-status" data-method="GET"
      data-path="api/v1/auth/user/confirmed-password-status"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-auth-user-confirmed-password-status', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-auth-user-confirmed-password-status"
                    onclick="tryItOut('GETapi-v1-auth-user-confirmed-password-status');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-auth-user-confirmed-password-status"
                    onclick="cancelTryOut('GETapi-v1-auth-user-confirmed-password-status');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-auth-user-confirmed-password-status"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/auth/user/confirmed-password-status</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-auth-user-confirmed-password-status"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-auth-user-confirmed-password-status"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-v1-auth-user-confirm-password">Confirm the user&#039;s password.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-auth-user-confirm-password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/auth/user/confirm-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/user/confirm-password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-user-confirm-password">
</span>
<span id="execution-results-POSTapi-v1-auth-user-confirm-password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-user-confirm-password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-user-confirm-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-user-confirm-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-user-confirm-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-user-confirm-password" data-method="POST"
      data-path="api/v1/auth/user/confirm-password"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-user-confirm-password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-user-confirm-password"
                    onclick="tryItOut('POSTapi-v1-auth-user-confirm-password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-user-confirm-password"
                    onclick="cancelTryOut('POSTapi-v1-auth-user-confirm-password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-user-confirm-password"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/user/confirm-password</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-user-confirm-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-user-confirm-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-v1-auth-two-factor-challenge">Attempt to authenticate a new session using the two factor authentication code.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-auth-two-factor-challenge">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/auth/two-factor-challenge" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"code\": \"architecto\",
    \"recovery_code\": \"architecto\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/two-factor-challenge"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "code": "architecto",
    "recovery_code": "architecto"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-two-factor-challenge">
</span>
<span id="execution-results-POSTapi-v1-auth-two-factor-challenge" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-two-factor-challenge"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-two-factor-challenge"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-two-factor-challenge" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-two-factor-challenge">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-two-factor-challenge" data-method="POST"
      data-path="api/v1/auth/two-factor-challenge"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-two-factor-challenge', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-two-factor-challenge"
                    onclick="tryItOut('POSTapi-v1-auth-two-factor-challenge');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-two-factor-challenge"
                    onclick="cancelTryOut('POSTapi-v1-auth-two-factor-challenge');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-two-factor-challenge"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/two-factor-challenge</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-two-factor-challenge"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-two-factor-challenge"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="code"                data-endpoint="POSTapi-v1-auth-two-factor-challenge"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>recovery_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="recovery_code"                data-endpoint="POSTapi-v1-auth-two-factor-challenge"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-v1-auth-user-two-factor-authentication">Enable two factor authentication for the user.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-auth-user-two-factor-authentication">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/auth/user/two-factor-authentication" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/user/two-factor-authentication"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-user-two-factor-authentication">
</span>
<span id="execution-results-POSTapi-v1-auth-user-two-factor-authentication" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-user-two-factor-authentication"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-user-two-factor-authentication"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-user-two-factor-authentication" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-user-two-factor-authentication">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-user-two-factor-authentication" data-method="POST"
      data-path="api/v1/auth/user/two-factor-authentication"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-user-two-factor-authentication', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-user-two-factor-authentication"
                    onclick="tryItOut('POSTapi-v1-auth-user-two-factor-authentication');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-user-two-factor-authentication"
                    onclick="cancelTryOut('POSTapi-v1-auth-user-two-factor-authentication');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-user-two-factor-authentication"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/user/two-factor-authentication</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-user-two-factor-authentication"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-user-two-factor-authentication"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-v1-auth-user-confirmed-two-factor-authentication">Enable two factor authentication for the user.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-auth-user-confirmed-two-factor-authentication">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/auth/user/confirmed-two-factor-authentication" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/user/confirmed-two-factor-authentication"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-user-confirmed-two-factor-authentication">
</span>
<span id="execution-results-POSTapi-v1-auth-user-confirmed-two-factor-authentication" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-user-confirmed-two-factor-authentication"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-user-confirmed-two-factor-authentication"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-user-confirmed-two-factor-authentication" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-user-confirmed-two-factor-authentication">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-user-confirmed-two-factor-authentication" data-method="POST"
      data-path="api/v1/auth/user/confirmed-two-factor-authentication"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-user-confirmed-two-factor-authentication', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-user-confirmed-two-factor-authentication"
                    onclick="tryItOut('POSTapi-v1-auth-user-confirmed-two-factor-authentication');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-user-confirmed-two-factor-authentication"
                    onclick="cancelTryOut('POSTapi-v1-auth-user-confirmed-two-factor-authentication');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-user-confirmed-two-factor-authentication"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/user/confirmed-two-factor-authentication</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-user-confirmed-two-factor-authentication"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-user-confirmed-two-factor-authentication"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-DELETEapi-v1-auth-user-two-factor-authentication">Disable two factor authentication for the user.</h2>

<p>
</p>



<span id="example-requests-DELETEapi-v1-auth-user-two-factor-authentication">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/v1/auth/user/two-factor-authentication" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/user/two-factor-authentication"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-auth-user-two-factor-authentication">
</span>
<span id="execution-results-DELETEapi-v1-auth-user-two-factor-authentication" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-auth-user-two-factor-authentication"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-auth-user-two-factor-authentication"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-auth-user-two-factor-authentication" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-auth-user-two-factor-authentication">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-auth-user-two-factor-authentication" data-method="DELETE"
      data-path="api/v1/auth/user/two-factor-authentication"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-auth-user-two-factor-authentication', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-auth-user-two-factor-authentication"
                    onclick="tryItOut('DELETEapi-v1-auth-user-two-factor-authentication');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-auth-user-two-factor-authentication"
                    onclick="cancelTryOut('DELETEapi-v1-auth-user-two-factor-authentication');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-auth-user-two-factor-authentication"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/auth/user/two-factor-authentication</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-auth-user-two-factor-authentication"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-auth-user-two-factor-authentication"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-auth-user-two-factor-qr-code">Get the SVG element for the user&#039;s two factor authentication QR code.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-auth-user-two-factor-qr-code">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/auth/user/two-factor-qr-code" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/user/two-factor-qr-code"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-auth-user-two-factor-qr-code">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
set-cookie: XSRF-TOKEN=eyJpdiI6ImRFOFFrbnRISjJUczVSWG5HOHpCeHc9PSIsInZhbHVlIjoiTG9kUlhrNEJzc2lmcDVZbFVDUmROVmFwZjFMeEZzWHNzUFRtT21IMkZEczZKQ3lWdEV5ZW5pc3RqTURNNE1sNzZqSERZSms5WlJ1NlkxMW1WZ25uZkpYQ00vai9nTWQrZlQyNkJCUG1JVTQxc0xrMGpxK3BucVFoWmpiVDhkRkciLCJtYWMiOiI2ODQ4MDgyNDI2ODUxODAxZDFjM2MyMzY4OThjMTkyNGRiNjc0ZWI3NjdiOGI0MWMxZDkzNTRkNjI2ZmJhZmEyIiwidGFnIjoiIn0%3D; expires=Fri, 24 Oct 2025 09:20:16 GMT; Max-Age=7200; path=/; samesite=lax; project-management-system-session=eyJpdiI6InloMkVZZlpPTzNEdmZMVGwwODYwaXc9PSIsInZhbHVlIjoic2c2QjA4RHQwSm5MeldUalhjVWdXUmRZai9rNnBycm9OVzJtT3BhcmdaK1ZGNkhUdDZ6bjdpZzlYdUc0NHVwVnFhWlB3VmRNTWpWdVQzblZFOTV1UVhCbUE5cURCYllTcmx6R1ZsdVJObEh5R1VjenVlekRiSmR4bVZWZlZFU1MiLCJtYWMiOiIzY2Q0MTdkNDY3MzU1YTRiYzdmMDQ0MzRiZjExNzUzMjU0ODZhNWMwMDNiYzMxYzM5ODUzYzVlYmNhMjM4M2NjIiwidGFnIjoiIn0%3D; expires=Fri, 24 Oct 2025 09:20:16 GMT; Max-Age=7200; path=/; httponly; samesite=lax
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-auth-user-two-factor-qr-code" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-auth-user-two-factor-qr-code"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-auth-user-two-factor-qr-code"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-auth-user-two-factor-qr-code" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-auth-user-two-factor-qr-code">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-auth-user-two-factor-qr-code" data-method="GET"
      data-path="api/v1/auth/user/two-factor-qr-code"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-auth-user-two-factor-qr-code', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-auth-user-two-factor-qr-code"
                    onclick="tryItOut('GETapi-v1-auth-user-two-factor-qr-code');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-auth-user-two-factor-qr-code"
                    onclick="cancelTryOut('GETapi-v1-auth-user-two-factor-qr-code');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-auth-user-two-factor-qr-code"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/auth/user/two-factor-qr-code</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-auth-user-two-factor-qr-code"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-auth-user-two-factor-qr-code"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-auth-user-two-factor-secret-key">Get the current user&#039;s two factor authentication setup / secret key.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-auth-user-two-factor-secret-key">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/auth/user/two-factor-secret-key" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/user/two-factor-secret-key"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-auth-user-two-factor-secret-key">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
set-cookie: XSRF-TOKEN=eyJpdiI6Ijh3RlU3bXpLd05Db1poOEVKRjZtdXc9PSIsInZhbHVlIjoiOFp0QzBRcWFTOXZQMVYyQmU2Nk9KUVM1MG9MZ2daRVFHK2RhSURBK1VWWXJMdVRERk0yUVZyaFF1M0xOZG05U2p6TVRJekdRUVlVZGlTOEJNcXd1V21BdFRkMFBvcVp3QmV3SVRYZXBGTHpRQzRqVlZ4RnpkbE9YOTZCUWJIajAiLCJtYWMiOiJiN2JjMDQ4NTVjZmM1Yzk0YmY0MzU1ZGNjMzhlNTQxYzMwZDBkZDBiMjJkMGQ1NmYwODJkZDg1YzUyNzMwY2M3IiwidGFnIjoiIn0%3D; expires=Fri, 24 Oct 2025 09:20:16 GMT; Max-Age=7200; path=/; samesite=lax; project-management-system-session=eyJpdiI6ImdwVnNZcWpxeWt5aWxFdEh2QjRWQlE9PSIsInZhbHVlIjoiRFVVdTI4RUkza255WkEyeUsvK3U2SGdhNmhUbitFcnhkYkhsOFAvVXgrTW54SGNRQ1Nka2VZaFhoQ0Vub1Qrb210U1VYYXY1UzZ6RC9BWTBvNS9FRG9DY21OUzhyS3c2YUxQNmhod1YrK3ZtZ2lxNVkzRVdmUkp2QXpxOVJYNVciLCJtYWMiOiI2ODc0NzhiMGMwOTMyZTMyMTA5NThlZGYyMGNmZGY1M2QyYjg3NjllOGM5NGIyOTg1ZTk0Zjc5YTU2YzViYzYwIiwidGFnIjoiIn0%3D; expires=Fri, 24 Oct 2025 09:20:16 GMT; Max-Age=7200; path=/; httponly; samesite=lax
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-auth-user-two-factor-secret-key" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-auth-user-two-factor-secret-key"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-auth-user-two-factor-secret-key"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-auth-user-two-factor-secret-key" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-auth-user-two-factor-secret-key">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-auth-user-two-factor-secret-key" data-method="GET"
      data-path="api/v1/auth/user/two-factor-secret-key"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-auth-user-two-factor-secret-key', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-auth-user-two-factor-secret-key"
                    onclick="tryItOut('GETapi-v1-auth-user-two-factor-secret-key');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-auth-user-two-factor-secret-key"
                    onclick="cancelTryOut('GETapi-v1-auth-user-two-factor-secret-key');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-auth-user-two-factor-secret-key"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/auth/user/two-factor-secret-key</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-auth-user-two-factor-secret-key"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-auth-user-two-factor-secret-key"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-auth-user-two-factor-recovery-codes">Get the two factor authentication recovery codes for authenticated user.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-auth-user-two-factor-recovery-codes">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/auth/user/two-factor-recovery-codes" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/user/two-factor-recovery-codes"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-auth-user-two-factor-recovery-codes">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
set-cookie: XSRF-TOKEN=eyJpdiI6Ik0wZXlsWW9TUlpMUU5ObUFjV3FsalE9PSIsInZhbHVlIjoiMFhUL1ZrY1ZiZjFzbmVBWm4zWlQvNFBBU3lvdnZoak02aTlVSE1TMGs0UDlNakhUbmxyUDVpd2l2eVQzTThNSDEwcXpQWmxRaFM1ZStZaU1kWWZ1cDdKSzlldzM2MzA1ZmVoZURreVFMMHdTNGY4MmpJS2lxWmw5REpwMEdVV2UiLCJtYWMiOiI2NzJkN2IxNTZiMmMxOWYxZDEyOGVhYTYxMjllNGJjNWE5YWE4ZmNjYjQ5MDY2MGU5Zjk5MTVlZWZmMTZkNWU4IiwidGFnIjoiIn0%3D; expires=Fri, 24 Oct 2025 09:20:16 GMT; Max-Age=7200; path=/; samesite=lax; project-management-system-session=eyJpdiI6IjRZVFRqNDkzM2hOYjE1b2N3T2psMGc9PSIsInZhbHVlIjoiZVdoYjZySXZyQ29aaGNueE54QkhPcVBxZzcyZkxydjJLeVR5WHdzWVZBbXNxQTdTSU1ITkxGTk0yWGJFbDdLVTVpdjZMZHpmWlFGWStIVzhzMHhHVWt3eDRJU1BnRjNxemhxWTgyejEwWDFJZlRpbWZEUjUzbWhoWUxIRWlwaEsiLCJtYWMiOiJkMWY0NjE0YmM3NDEyZTU1YWIwMDFiZjdmY2I2NGU5NTlhOGUwNTJkYWFhNDhmYzM1ZmZlODQxOTA5MDYyZjhlIiwidGFnIjoiIn0%3D; expires=Fri, 24 Oct 2025 09:20:16 GMT; Max-Age=7200; path=/; httponly; samesite=lax
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-auth-user-two-factor-recovery-codes" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-auth-user-two-factor-recovery-codes"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-auth-user-two-factor-recovery-codes"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-auth-user-two-factor-recovery-codes" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-auth-user-two-factor-recovery-codes">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-auth-user-two-factor-recovery-codes" data-method="GET"
      data-path="api/v1/auth/user/two-factor-recovery-codes"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-auth-user-two-factor-recovery-codes', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-auth-user-two-factor-recovery-codes"
                    onclick="tryItOut('GETapi-v1-auth-user-two-factor-recovery-codes');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-auth-user-two-factor-recovery-codes"
                    onclick="cancelTryOut('GETapi-v1-auth-user-two-factor-recovery-codes');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-auth-user-two-factor-recovery-codes"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/auth/user/two-factor-recovery-codes</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-auth-user-two-factor-recovery-codes"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-auth-user-two-factor-recovery-codes"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-v1-auth-user-two-factor-recovery-codes">Generate a fresh set of two factor authentication recovery codes.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-auth-user-two-factor-recovery-codes">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/auth/user/two-factor-recovery-codes" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/user/two-factor-recovery-codes"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-user-two-factor-recovery-codes">
</span>
<span id="execution-results-POSTapi-v1-auth-user-two-factor-recovery-codes" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-user-two-factor-recovery-codes"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-user-two-factor-recovery-codes"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-user-two-factor-recovery-codes" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-user-two-factor-recovery-codes">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-user-two-factor-recovery-codes" data-method="POST"
      data-path="api/v1/auth/user/two-factor-recovery-codes"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-user-two-factor-recovery-codes', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-user-two-factor-recovery-codes"
                    onclick="tryItOut('POSTapi-v1-auth-user-two-factor-recovery-codes');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-user-two-factor-recovery-codes"
                    onclick="cancelTryOut('POSTapi-v1-auth-user-two-factor-recovery-codes');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-user-two-factor-recovery-codes"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/user/two-factor-recovery-codes</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-user-two-factor-recovery-codes"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-user-two-factor-recovery-codes"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-user">GET api/v1/user</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-user">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/user" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/user"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-user">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">content-type: text/html; charset=UTF-8
cache-control: no-cache, private
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-user" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-user"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-user"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-user">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-user" data-method="GET"
      data-path="api/v1/user"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-user', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-user"
                    onclick="tryItOut('GETapi-v1-user');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-user"
                    onclick="cancelTryOut('GETapi-v1-user');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-user"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/user</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="project-management">Project Management</h1>

    <p>APIs for managing projects</p>

                                <h2 id="project-management-GETapi-v1-projects">List projects</h2>

<p>
</p>

<p>Get a paginated list of all projects with optional filtering.</p>

<span id="example-requests-GETapi-v1-projects">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/projects?status=in_progress&amp;manager_id=3&amp;start_date_from=2024-01-01&amp;start_date_to=2024-12-31&amp;per_page=10" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/projects"
);

const params = {
    "status": "in_progress",
    "manager_id": "3",
    "start_date_from": "2024-01-01",
    "start_date_to": "2024-12-31",
    "per_page": "10",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-projects">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;New Website&quot;,
            &quot;description&quot;: &quot;Build company website&quot;,
            &quot;status&quot;: &quot;in_progress&quot;,
            &quot;start_date&quot;: &quot;2024-01-01&quot;,
            &quot;due_date&quot;: &quot;2024-06-30&quot;
        }
    ],
    &quot;links&quot;: {},
    &quot;meta&quot;: {}
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to retrieve projects: Database connection error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-projects" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-projects"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-projects"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-projects" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-projects">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-projects" data-method="GET"
      data-path="api/v1/projects"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-projects', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-projects"
                    onclick="tryItOut('GETapi-v1-projects');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-projects"
                    onclick="cancelTryOut('GETapi-v1-projects');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-projects"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/projects</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-projects"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-projects"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-v1-projects"
               value="in_progress"
               data-component="query">
    <br>
<p>Filter by project status. Example: <code>in_progress</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>manager_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="manager_id"                data-endpoint="GETapi-v1-projects"
               value="3"
               data-component="query">
    <br>
<p>Filter by manager ID. Example: <code>3</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>start_date_from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="start_date_from"                data-endpoint="GETapi-v1-projects"
               value="2024-01-01"
               data-component="query">
    <br>
<p>date Filter by start date (from). Example: <code>2024-01-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>start_date_to</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="start_date_to"                data-endpoint="GETapi-v1-projects"
               value="2024-12-31"
               data-component="query">
    <br>
<p>date Filter by start date (to). Example: <code>2024-12-31</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-projects"
               value="10"
               data-component="query">
    <br>
<p>Number of items per page. Default is 15. Example: <code>10</code></p>
            </div>
                </form>

                    <h2 id="project-management-GETapi-v1-projects--project_id-">Get project</h2>

<p>
</p>

<p>Get details of a specific project by ID, including its manager and assigned teams.</p>

<span id="example-requests-GETapi-v1-projects--project_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/projects/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/projects/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-projects--project_id-">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;New Website&quot;,
        &quot;description&quot;: &quot;Build company website&quot;,
        &quot;status&quot;: &quot;in_progress&quot;,
        &quot;manager&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Jane Manager&quot;
        },
        &quot;teams&quot;: []
    },
    &quot;message&quot;: &quot;Project details retrieved successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Project not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to retrieve project details: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-projects--project_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-projects--project_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-projects--project_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-projects--project_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-projects--project_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-projects--project_id-" data-method="GET"
      data-path="api/v1/projects/{project_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-projects--project_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-projects--project_id-"
                    onclick="tryItOut('GETapi-v1-projects--project_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-projects--project_id-"
                    onclick="cancelTryOut('GETapi-v1-projects--project_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-projects--project_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/projects/{project_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-projects--project_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-projects--project_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="GETapi-v1-projects--project_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>16</code></p>
            </div>
                    </form>

                    <h2 id="project-management-POSTapi-v1-projects">Create project</h2>

<p>
</p>

<p>Create a new project with the provided details.</p>

<span id="example-requests-POSTapi-v1-projects">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/projects" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"New Website\",
    \"description\": \"Build a responsive company website\",
    \"manager_id\": 3,
    \"status\": \"in_progress\",
    \"start_date\": \"2024-01-01\",
    \"due_date\": \"2024-06-30\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/projects"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "New Website",
    "description": "Build a responsive company website",
    "manager_id": 3,
    "status": "in_progress",
    "start_date": "2024-01-01",
    "due_date": "2024-06-30"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-projects">
            <blockquote>
            <p>Example response (201, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;New Website&quot;,
        &quot;description&quot;: &quot;Build a responsive company website&quot;,
        &quot;manager_id&quot;: 3,
        &quot;status&quot;: &quot;in_progress&quot;,
        &quot;start_date&quot;: &quot;2024-01-01&quot;,
        &quot;due_date&quot;: &quot;2024-06-30&quot;,
        &quot;created_at&quot;: &quot;2024-01-01T12:00:00Z&quot;,
        &quot;updated_at&quot;: &quot;2024-01-01T12:00:00Z&quot;
    },
    &quot;message&quot;: &quot;Project created successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (400, unqualified manager):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;The specified manager is not qualified to manage projects.&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;name&quot;: [
            &quot;The name has already been taken.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to create project: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-projects" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-projects"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-projects"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-projects" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-projects">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-projects" data-method="POST"
      data-path="api/v1/projects"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-projects', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-projects"
                    onclick="tryItOut('POSTapi-v1-projects');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-projects"
                    onclick="cancelTryOut('POSTapi-v1-projects');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-projects"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/projects</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-projects"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-projects"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-projects"
               value="New Website"
               data-component="body">
    <br>
<p>Name of the project. Must be unique. Example: <code>New Website</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-v1-projects"
               value="Build a responsive company website"
               data-component="body">
    <br>
<p>Description of the project. Example: <code>Build a responsive company website</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>manager_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="manager_id"                data-endpoint="POSTapi-v1-projects"
               value="3"
               data-component="body">
    <br>
<p>The ID of the project manager. Must be a user with admin or project manager role. Example: <code>3</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="POSTapi-v1-projects"
               value="in_progress"
               data-component="body">
    <br>
<p>Current status of the project. Allowed values: not started, in progress, completed, on hold. Example: <code>in_progress</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>start_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start_date"                data-endpoint="POSTapi-v1-projects"
               value="2024-01-01"
               data-component="body">
    <br>
<p>Project start date. Example: <code>2024-01-01</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>due_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="due_date"                data-endpoint="POSTapi-v1-projects"
               value="2024-06-30"
               data-component="body">
    <br>
<p>Project due date. Must be on or after start_date. Example: <code>2024-06-30</code></p>
        </div>
        </form>

                    <h2 id="project-management-PUTapi-v1-projects--project_id-">Update project</h2>

<p>
</p>

<p>Update an existing project's details.</p>

<span id="example-requests-PUTapi-v1-projects--project_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/v1/projects/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Updated Website Project\",
    \"description\": \"Updated project description\",
    \"manager_id\": 4,
    \"status\": \"completed\",
    \"start_date\": \"2024-01-15\",
    \"due_date\": \"2024-07-31\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/projects/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Updated Website Project",
    "description": "Updated project description",
    "manager_id": 4,
    "status": "completed",
    "start_date": "2024-01-15",
    "due_date": "2024-07-31"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-projects--project_id-">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Updated Website Project&quot;,
        &quot;description&quot;: &quot;Updated project description&quot;,
        &quot;manager_id&quot;: 4,
        &quot;status&quot;: &quot;completed&quot;,
        &quot;start_date&quot;: &quot;2024-01-15&quot;,
        &quot;due_date&quot;: &quot;2024-07-31&quot;,
        &quot;created_at&quot;: &quot;2024-01-01T12:00:00Z&quot;,
        &quot;updated_at&quot;: &quot;2024-02-01T12:00:00Z&quot;
    },
    &quot;message&quot;: &quot;Project updated successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (400, unqualified manager):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;The specified manager is not qualified to manage projects.&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Project not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;name&quot;: [
            &quot;The name has already been taken.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to update project: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-projects--project_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-projects--project_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-projects--project_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-projects--project_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-projects--project_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-projects--project_id-" data-method="PUT"
      data-path="api/v1/projects/{project_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-projects--project_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-projects--project_id-"
                    onclick="tryItOut('PUTapi-v1-projects--project_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-projects--project_id-"
                    onclick="cancelTryOut('PUTapi-v1-projects--project_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-projects--project_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/projects/{project_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-projects--project_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-projects--project_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="PUTapi-v1-projects--project_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>16</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-projects--project_id-"
               value="Updated Website Project"
               data-component="body">
    <br>
<p>Name of the project. Must be unique. Example: <code>Updated Website Project</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-v1-projects--project_id-"
               value="Updated project description"
               data-component="body">
    <br>
<p>Description of the project. Example: <code>Updated project description</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>manager_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="manager_id"                data-endpoint="PUTapi-v1-projects--project_id-"
               value="4"
               data-component="body">
    <br>
<p>The ID of the project manager. Must be a user with admin or project manager role. Example: <code>4</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="PUTapi-v1-projects--project_id-"
               value="completed"
               data-component="body">
    <br>
<p>Current status of the project. Allowed values: not started, in progress, completed, on hold. Example: <code>completed</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>start_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="start_date"                data-endpoint="PUTapi-v1-projects--project_id-"
               value="2024-01-15"
               data-component="body">
    <br>
<p>Project start date. Example: <code>2024-01-15</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>due_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="due_date"                data-endpoint="PUTapi-v1-projects--project_id-"
               value="2024-07-31"
               data-component="body">
    <br>
<p>Project due date. Must be on or after start_date. Example: <code>2024-07-31</code></p>
        </div>
        </form>

                    <h2 id="project-management-DELETEapi-v1-projects--project_id-">Soft delete project</h2>

<p>
</p>

<p>Soft delete a project from the system.</p>

<span id="example-requests-DELETEapi-v1-projects--project_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/v1/projects/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/projects/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-projects--project_id-">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Project deleted successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Project not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to delete project: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-projects--project_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-projects--project_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-projects--project_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-projects--project_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-projects--project_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-projects--project_id-" data-method="DELETE"
      data-path="api/v1/projects/{project_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-projects--project_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-projects--project_id-"
                    onclick="tryItOut('DELETEapi-v1-projects--project_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-projects--project_id-"
                    onclick="cancelTryOut('DELETEapi-v1-projects--project_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-projects--project_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/projects/{project_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-projects--project_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-projects--project_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="DELETEapi-v1-projects--project_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>16</code></p>
            </div>
                    </form>

                    <h2 id="project-management-POSTapi-v1-projects--projectId--restore">Restore soft-deleted project</h2>

<p>
</p>

<p>Restore a previously deleted project.</p>

<span id="example-requests-POSTapi-v1-projects--projectId--restore">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/projects/16/restore" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/projects/16/restore"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-projects--projectId--restore">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;New Website&quot;,
        &quot;description&quot;: &quot;Build company website&quot;,
        &quot;status&quot;: &quot;in_progress&quot;
    },
    &quot;message&quot;: &quot;Project restored successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Project not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to restore project: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-projects--projectId--restore" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-projects--projectId--restore"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-projects--projectId--restore"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-projects--projectId--restore" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-projects--projectId--restore">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-projects--projectId--restore" data-method="POST"
      data-path="api/v1/projects/{projectId}/restore"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-projects--projectId--restore', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-projects--projectId--restore"
                    onclick="tryItOut('POSTapi-v1-projects--projectId--restore');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-projects--projectId--restore"
                    onclick="cancelTryOut('POSTapi-v1-projects--projectId--restore');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-projects--projectId--restore"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/projects/{projectId}/restore</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-projects--projectId--restore"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-projects--projectId--restore"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>projectId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="projectId"                data-endpoint="POSTapi-v1-projects--projectId--restore"
               value="16"
               data-component="url">
    <br>
<p>Example: <code>16</code></p>
            </div>
                    </form>

                    <h2 id="project-management-POSTapi-v1-projects--project_id--manager">Set project manager</h2>

<p>
</p>

<p>Assign or change the project manager for a specific project.</p>

<span id="example-requests-POSTapi-v1-projects--project_id--manager">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/projects/16/manager" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"manager_id\": 5
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/projects/16/manager"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "manager_id": 5
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-projects--project_id--manager">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;New Website&quot;,
        &quot;description&quot;: &quot;Build company website&quot;,
        &quot;manager_id&quot;: 5,
        &quot;status&quot;: &quot;in_progress&quot;
    },
    &quot;message&quot;: &quot;Project manager assigned successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (400, unqualified manager):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;The specified manager is not qualified to manage projects.&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Project not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;manager_id&quot;: [
            &quot;The selected manager id is invalid.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to assign project manager: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-projects--project_id--manager" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-projects--project_id--manager"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-projects--project_id--manager"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-projects--project_id--manager" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-projects--project_id--manager">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-projects--project_id--manager" data-method="POST"
      data-path="api/v1/projects/{project_id}/manager"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-projects--project_id--manager', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-projects--project_id--manager"
                    onclick="tryItOut('POSTapi-v1-projects--project_id--manager');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-projects--project_id--manager"
                    onclick="cancelTryOut('POSTapi-v1-projects--project_id--manager');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-projects--project_id--manager"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/projects/{project_id}/manager</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-projects--project_id--manager"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-projects--project_id--manager"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="POSTapi-v1-projects--project_id--manager"
               value="16"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>16</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>manager_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="manager_id"                data-endpoint="POSTapi-v1-projects--project_id--manager"
               value="5"
               data-component="body">
    <br>
<p>The ID of the user to assign as project manager. Must be a user with admin or project manager role. Example: <code>5</code></p>
        </div>
        </form>

                    <h2 id="project-management-POSTapi-v1-projects--project_id--teams">Assign teams to project</h2>

<p>
</p>

<p>Assign multiple teams to a project in a single request.</p>

<span id="example-requests-POSTapi-v1-projects--project_id--teams">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/projects/16/teams" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"team_ids\": [
        1,
        3,
        5
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/projects/16/teams"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "team_ids": [
        1,
        3,
        5
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-projects--project_id--teams">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;project&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;New Website&quot;,
            &quot;teams&quot;: [
                {
                    &quot;id&quot;: 1,
                    &quot;name&quot;: &quot;Development Team&quot;
                },
                {
                    &quot;id&quot;: 3,
                    &quot;name&quot;: &quot;QA Team&quot;
                }
            ]
        },
        &quot;invalid_team_ids&quot;: []
    },
    &quot;message&quot;: &quot;Teams assigned to project successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (200, success with invalid teams):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;project&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;New Website&quot;,
            &quot;teams&quot;: [
                {
                    &quot;id&quot;: 1,
                    &quot;name&quot;: &quot;Development Team&quot;
                }
            ]
        },
        &quot;invalid_team_ids&quot;: [
            99
        ]
    },
    &quot;message&quot;: &quot;Teams assigned to project successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Project not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;team_ids.0&quot;: [
            &quot;The selected team_ids.0 is invalid.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to assign teams to project: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-projects--project_id--teams" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-projects--project_id--teams"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-projects--project_id--teams"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-projects--project_id--teams" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-projects--project_id--teams">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-projects--project_id--teams" data-method="POST"
      data-path="api/v1/projects/{project_id}/teams"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-projects--project_id--teams', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-projects--project_id--teams"
                    onclick="tryItOut('POSTapi-v1-projects--project_id--teams');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-projects--project_id--teams"
                    onclick="cancelTryOut('POSTapi-v1-projects--project_id--teams');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-projects--project_id--teams"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/projects/{project_id}/teams</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-projects--project_id--teams"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-projects--project_id--teams"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="POSTapi-v1-projects--project_id--teams"
               value="16"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>16</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>team_ids</code></b>&nbsp;&nbsp;
<small>integer[]</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="team_ids[0]"                data-endpoint="POSTapi-v1-projects--project_id--teams"
               data-component="body">
        <input type="number" style="display: none"
               name="team_ids[1]"                data-endpoint="POSTapi-v1-projects--project_id--teams"
               data-component="body">
    <br>
<p>Array of team IDs to assign to the project.</p>
        </div>
        </form>

                <h1 id="team-management">Team Management</h1>

    <p>APIs for managing teams</p>

                                <h2 id="team-management-GETapi-v1-teams">List teams</h2>

<p>
</p>

<p>Get a paginated list of teams with optional filters.</p>

<span id="example-requests-GETapi-v1-teams">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/teams?name=Development&amp;has_leader=1&amp;per_page=10" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/teams"
);

const params = {
    "name": "Development",
    "has_leader": "1",
    "per_page": "10",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-teams">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Development Team&quot;,
            &quot;description&quot;: &quot;Handles all development tasks&quot;
        }
    ],
    &quot;links&quot;: {},
    &quot;meta&quot;: {}
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to retrieve teams: Database connection error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-teams" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-teams"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-teams"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-teams" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-teams">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-teams" data-method="GET"
      data-path="api/v1/teams"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-teams', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-teams"
                    onclick="tryItOut('GETapi-v1-teams');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-teams"
                    onclick="cancelTryOut('GETapi-v1-teams');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-teams"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/teams</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-teams"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-teams"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="GETapi-v1-teams"
               value="Development"
               data-component="query">
    <br>
<p>Filter teams by name (partial match). Example: <code>Development</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>has_leader</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="GETapi-v1-teams" style="display: none">
            <input type="radio" name="has_leader"
                   value="1"
                   data-endpoint="GETapi-v1-teams"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-v1-teams" style="display: none">
            <input type="radio" name="has_leader"
                   value="0"
                   data-endpoint="GETapi-v1-teams"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Filter teams by whether they have a leader assigned. Example: <code>true</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-teams"
               value="10"
               data-component="query">
    <br>
<p>Number of results per page. Default is 15. Example: <code>10</code></p>
            </div>
                </form>

                    <h2 id="team-management-POSTapi-v1-teams">Create team</h2>

<p>
</p>

<p>Create a new team with the provided details.</p>

<span id="example-requests-POSTapi-v1-teams">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/teams" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Development Team\",
    \"description\": \"Team responsible for product development\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/teams"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Development Team",
    "description": "Team responsible for product development"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-teams">
            <blockquote>
            <p>Example response (201, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Development Team&quot;,
        &quot;description&quot;: &quot;Handles all development tasks&quot;,
        &quot;created_at&quot;: &quot;2024-01-01T12:00:00Z&quot;,
        &quot;updated_at&quot;: &quot;2024-01-01T12:00:00Z&quot;
    },
    &quot;message&quot;: &quot;Team created successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;name&quot;: [
            &quot;The name has already been taken.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to create team: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-teams" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-teams"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-teams"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-teams" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-teams">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-teams" data-method="POST"
      data-path="api/v1/teams"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-teams', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-teams"
                    onclick="tryItOut('POSTapi-v1-teams');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-teams"
                    onclick="cancelTryOut('POSTapi-v1-teams');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-teams"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/teams</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-teams"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-teams"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-teams"
               value="Development Team"
               data-component="body">
    <br>
<p>Name of the Team. Must be unique. Example: <code>Development Team</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-v1-teams"
               value="Team responsible for product development"
               data-component="body">
    <br>
<p>Description of the Team. Example: <code>Team responsible for product development</code></p>
        </div>
        </form>

                    <h2 id="team-management-GETapi-v1-teams--team_id-">Get team</h2>

<p>
</p>

<p>Get details of a specific team by ID, including its members.</p>

<span id="example-requests-GETapi-v1-teams--team_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/teams/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/teams/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-teams--team_id-">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Development Team&quot;,
        &quot;description&quot;: &quot;Handles all development tasks&quot;,
        &quot;users&quot;: []
    },
    &quot;message&quot;: &quot;Team retrieved successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Team not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to retrieve team&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-teams--team_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-teams--team_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-teams--team_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-teams--team_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-teams--team_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-teams--team_id-" data-method="GET"
      data-path="api/v1/teams/{team_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-teams--team_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-teams--team_id-"
                    onclick="tryItOut('GETapi-v1-teams--team_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-teams--team_id-"
                    onclick="cancelTryOut('GETapi-v1-teams--team_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-teams--team_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/teams/{team_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-teams--team_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-teams--team_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>team_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="team_id"                data-endpoint="GETapi-v1-teams--team_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the team. Example: <code>16</code></p>
            </div>
                    </form>

                    <h2 id="team-management-PUTapi-v1-teams--team_id-">Update team details</h2>

<p>
</p>

<p>Update the details of an existing team.</p>

<span id="example-requests-PUTapi-v1-teams--team_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/v1/teams/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Updated Development Team\",
    \"description\": \"Updated team description\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/teams/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Updated Development Team",
    "description": "Updated team description"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-teams--team_id-">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Updated Development Team&quot;,
        &quot;description&quot;: &quot;Updated description&quot;,
        &quot;created_at&quot;: &quot;2024-01-01T12:00:00Z&quot;,
        &quot;updated_at&quot;: &quot;2024-01-02T12:00:00Z&quot;
    },
    &quot;message&quot;: &quot;Team updated successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Team not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;name&quot;: [
            &quot;The name has already been taken.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to update team: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-teams--team_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-teams--team_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-teams--team_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-teams--team_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-teams--team_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-teams--team_id-" data-method="PUT"
      data-path="api/v1/teams/{team_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-teams--team_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-teams--team_id-"
                    onclick="tryItOut('PUTapi-v1-teams--team_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-teams--team_id-"
                    onclick="cancelTryOut('PUTapi-v1-teams--team_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-teams--team_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/teams/{team_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-teams--team_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-teams--team_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>team_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="team_id"                data-endpoint="PUTapi-v1-teams--team_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the team. Example: <code>16</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-teams--team_id-"
               value="Updated Development Team"
               data-component="body">
    <br>
<p>Name of the Team. Must be unique. Example: <code>Updated Development Team</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-v1-teams--team_id-"
               value="Updated team description"
               data-component="body">
    <br>
<p>Description of the Team. Example: <code>Updated team description</code></p>
        </div>
        </form>

                    <h2 id="team-management-DELETEapi-v1-teams--team_id-">Delete team</h2>

<p>
</p>

<p>Permanently delete a team from the system.</p>

<span id="example-requests-DELETEapi-v1-teams--team_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/v1/teams/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/teams/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-teams--team_id-">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Team deleted successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Team not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to delete team: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-teams--team_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-teams--team_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-teams--team_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-teams--team_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-teams--team_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-teams--team_id-" data-method="DELETE"
      data-path="api/v1/teams/{team_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-teams--team_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-teams--team_id-"
                    onclick="tryItOut('DELETEapi-v1-teams--team_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-teams--team_id-"
                    onclick="cancelTryOut('DELETEapi-v1-teams--team_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-teams--team_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/teams/{team_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-teams--team_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-teams--team_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>team_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="team_id"                data-endpoint="DELETEapi-v1-teams--team_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the team. Example: <code>16</code></p>
            </div>
                    </form>

                    <h2 id="team-management-POSTapi-v1-teams--team_id--members">Add member to team</h2>

<p>
</p>

<p>Add a single user to a team with a specified role.</p>

<span id="example-requests-POSTapi-v1-teams--team_id--members">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/teams/16/members" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"user_id\": 5,
    \"role\": \"team member\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/teams/16/members"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "user_id": 5,
    "role": "team member"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-teams--team_id--members">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;User added to team successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (400, invalid argument):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to add user to team: Invalid user or role&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Team not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;user_id&quot;: [
            &quot;The selected user id is invalid.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to add user to team: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-teams--team_id--members" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-teams--team_id--members"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-teams--team_id--members"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-teams--team_id--members" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-teams--team_id--members">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-teams--team_id--members" data-method="POST"
      data-path="api/v1/teams/{team_id}/members"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-teams--team_id--members', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-teams--team_id--members"
                    onclick="tryItOut('POSTapi-v1-teams--team_id--members');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-teams--team_id--members"
                    onclick="cancelTryOut('POSTapi-v1-teams--team_id--members');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-teams--team_id--members"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/teams/{team_id}/members</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-teams--team_id--members"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-teams--team_id--members"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>team_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="team_id"                data-endpoint="POSTapi-v1-teams--team_id--members"
               value="16"
               data-component="url">
    <br>
<p>The ID of the team. Example: <code>16</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="POSTapi-v1-teams--team_id--members"
               value="5"
               data-component="body">
    <br>
<p>The ID of the user to add. Example: <code>5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="role"                data-endpoint="POSTapi-v1-teams--team_id--members"
               value="team member"
               data-component="body">
    <br>
<p>The role to assign. Allowed values: team lead, team member. Example: <code>team member</code></p>
        </div>
        </form>

                    <h2 id="team-management-POSTapi-v1-teams--team_id--members-bulk">Add multiple members to team</h2>

<p>
</p>

<p>Add multiple users to a team with their specified roles in a single request.</p>

<span id="example-requests-POSTapi-v1-teams--team_id--members-bulk">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/teams/16/members/bulk" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"members\": [
        {
            \"user_id\": 5,
            \"role\": \"team member\"
        }
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/teams/16/members/bulk"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "members": [
        {
            "user_id": 5,
            "role": "team member"
        }
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-teams--team_id--members-bulk">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;invalid_users&quot;: []
    },
    &quot;message&quot;: &quot;Users added to team successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (200, success with invalid users):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;invalid_users&quot;: [
            3,
            7
        ]
    },
    &quot;message&quot;: &quot;Users added to team successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Team not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;members.0.user_id&quot;: [
            &quot;The selected members.0.user_id is invalid.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to add users to team: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-teams--team_id--members-bulk" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-teams--team_id--members-bulk"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-teams--team_id--members-bulk"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-teams--team_id--members-bulk" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-teams--team_id--members-bulk">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-teams--team_id--members-bulk" data-method="POST"
      data-path="api/v1/teams/{team_id}/members/bulk"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-teams--team_id--members-bulk', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-teams--team_id--members-bulk"
                    onclick="tryItOut('POSTapi-v1-teams--team_id--members-bulk');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-teams--team_id--members-bulk"
                    onclick="cancelTryOut('POSTapi-v1-teams--team_id--members-bulk');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-teams--team_id--members-bulk"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/teams/{team_id}/members/bulk</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-teams--team_id--members-bulk"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-teams--team_id--members-bulk"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>team_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="team_id"                data-endpoint="POSTapi-v1-teams--team_id--members-bulk"
               value="16"
               data-component="url">
    <br>
<p>The ID of the team. Example: <code>16</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>members</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
 &nbsp;
<br>
<p>Array of members to add.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="members.0.user_id"                data-endpoint="POSTapi-v1-teams--team_id--members-bulk"
               value="5"
               data-component="body">
    <br>
<p>The ID of the user to add. Example: <code>5</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="members.0.role"                data-endpoint="POSTapi-v1-teams--team_id--members-bulk"
               value="team member"
               data-component="body">
    <br>
<p>The role to assign. Allowed values: team lead, team member. Example: <code>team member</code></p>
                    </div>
                                    </details>
        </div>
        </form>

                    <h2 id="team-management-DELETEapi-v1-teams--team_id--members--user_id-">Remove member from team</h2>

<p>
</p>

<p>Remove a single user from a team.</p>

<span id="example-requests-DELETEapi-v1-teams--team_id--members--user_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/v1/teams/16/members/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"user_id\": 5
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/teams/16/members/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "user_id": 5
};

fetch(url, {
    method: "DELETE",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-teams--team_id--members--user_id-">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;User removed from team successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (400, invalid argument):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to remove user from team: User not in team&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Team not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;user_id&quot;: [
            &quot;The selected user id is invalid.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to remove user from team: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-teams--team_id--members--user_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-teams--team_id--members--user_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-teams--team_id--members--user_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-teams--team_id--members--user_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-teams--team_id--members--user_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-teams--team_id--members--user_id-" data-method="DELETE"
      data-path="api/v1/teams/{team_id}/members/{user_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-teams--team_id--members--user_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-teams--team_id--members--user_id-"
                    onclick="tryItOut('DELETEapi-v1-teams--team_id--members--user_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-teams--team_id--members--user_id-"
                    onclick="cancelTryOut('DELETEapi-v1-teams--team_id--members--user_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-teams--team_id--members--user_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/teams/{team_id}/members/{user_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-teams--team_id--members--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-teams--team_id--members--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>team_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="team_id"                data-endpoint="DELETEapi-v1-teams--team_id--members--user_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the team. Example: <code>16</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="DELETEapi-v1-teams--team_id--members--user_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>16</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="DELETEapi-v1-teams--team_id--members--user_id-"
               value="5"
               data-component="body">
    <br>
<p>The ID of the user to remove. Example: <code>5</code></p>
        </div>
        </form>

                    <h2 id="team-management-DELETEapi-v1-teams--team_id--members">Remove multiple members from team</h2>

<p>
</p>

<p>Remove multiple users from a team in a single request.</p>

<span id="example-requests-DELETEapi-v1-teams--team_id--members">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/v1/teams/16/members" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"user_ids\": [
        5,
        8,
        12
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/teams/16/members"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "user_ids": [
        5,
        8,
        12
    ]
};

fetch(url, {
    method: "DELETE",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-teams--team_id--members">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Users removed from team successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (400, invalid argument):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to remove users from team: Invalid user IDs&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Team not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;user_ids.0&quot;: [
            &quot;The selected user_ids.0 is invalid.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to remove users from team: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-teams--team_id--members" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-teams--team_id--members"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-teams--team_id--members"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-teams--team_id--members" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-teams--team_id--members">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-teams--team_id--members" data-method="DELETE"
      data-path="api/v1/teams/{team_id}/members"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-teams--team_id--members', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-teams--team_id--members"
                    onclick="tryItOut('DELETEapi-v1-teams--team_id--members');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-teams--team_id--members"
                    onclick="cancelTryOut('DELETEapi-v1-teams--team_id--members');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-teams--team_id--members"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/teams/{team_id}/members</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-teams--team_id--members"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-teams--team_id--members"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>team_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="team_id"                data-endpoint="DELETEapi-v1-teams--team_id--members"
               value="16"
               data-component="url">
    <br>
<p>The ID of the team. Example: <code>16</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user_ids</code></b>&nbsp;&nbsp;
<small>integer[]</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_ids[0]"                data-endpoint="DELETEapi-v1-teams--team_id--members"
               data-component="body">
        <input type="number" style="display: none"
               name="user_ids[1]"                data-endpoint="DELETEapi-v1-teams--team_id--members"
               data-component="body">
    <br>
<p>Array of user IDs to remove from the team.</p>
        </div>
        </form>

                    <h2 id="team-management-POSTapi-v1-teams--team_id--lead">Set team leader</h2>

<p>
</p>

<p>Set a user as the team leader. If there's an existing leader, they will be demoted to team member.</p>

<span id="example-requests-POSTapi-v1-teams--team_id--lead">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/teams/16/lead" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"user_id\": 5
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/teams/16/lead"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "user_id": 5
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-teams--team_id--lead">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;demoted_lead&quot;: {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;Previous Leader&quot;
        }
    },
    &quot;message&quot;: &quot;Team leader set successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (200, success - no previous leader):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;demoted_lead&quot;: null
    },
    &quot;message&quot;: &quot;Team leader set successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (200, user already leader):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;User is already the team lead&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (400, invalid argument):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to set team leader: User does not have the team lead role&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Team not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;user_id&quot;: [
            &quot;The selected user id is invalid.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to set team leader: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-teams--team_id--lead" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-teams--team_id--lead"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-teams--team_id--lead"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-teams--team_id--lead" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-teams--team_id--lead">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-teams--team_id--lead" data-method="POST"
      data-path="api/v1/teams/{team_id}/lead"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-teams--team_id--lead', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-teams--team_id--lead"
                    onclick="tryItOut('POSTapi-v1-teams--team_id--lead');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-teams--team_id--lead"
                    onclick="cancelTryOut('POSTapi-v1-teams--team_id--lead');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-teams--team_id--lead"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/teams/{team_id}/lead</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-teams--team_id--lead"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-teams--team_id--lead"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>team_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="team_id"                data-endpoint="POSTapi-v1-teams--team_id--lead"
               value="16"
               data-component="url">
    <br>
<p>The ID of the team. Example: <code>16</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="POSTapi-v1-teams--team_id--lead"
               value="5"
               data-component="body">
    <br>
<p>The ID of the user to set as leader. Example: <code>5</code></p>
        </div>
        </form>

                    <h2 id="team-management-POSTapi-v1-teams--team_id--projects">Assign project to team</h2>

<p>
</p>

<p>Assign a project to the team to work on.</p>

<span id="example-requests-POSTapi-v1-teams--team_id--projects">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/teams/16/projects" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"project_id\": 3,
    \"notes\": \"n\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/teams/16/projects"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "project_id": 3,
    "notes": "n"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-teams--team_id--projects">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Project assigned to team successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (400, invalid argument):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to assign project to team: Project already assigned to team&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Team not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;project_id&quot;: [
            &quot;The selected project id is invalid.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to assign project to team: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-teams--team_id--projects" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-teams--team_id--projects"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-teams--team_id--projects"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-teams--team_id--projects" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-teams--team_id--projects">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-teams--team_id--projects" data-method="POST"
      data-path="api/v1/teams/{team_id}/projects"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-teams--team_id--projects', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-teams--team_id--projects"
                    onclick="tryItOut('POSTapi-v1-teams--team_id--projects');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-teams--team_id--projects"
                    onclick="cancelTryOut('POSTapi-v1-teams--team_id--projects');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-teams--team_id--projects"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/teams/{team_id}/projects</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-teams--team_id--projects"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-teams--team_id--projects"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>team_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="team_id"                data-endpoint="POSTapi-v1-teams--team_id--projects"
               value="16"
               data-component="url">
    <br>
<p>The ID of the team. Example: <code>16</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="POSTapi-v1-teams--team_id--projects"
               value="3"
               data-component="body">
    <br>
<p>The ID of the project to assign. Example: <code>3</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="notes"                data-endpoint="POSTapi-v1-teams--team_id--projects"
               value="n"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>n</code></p>
        </div>
        </form>

                    <h2 id="team-management-DELETEapi-v1-teams--team_id--projects--project_id-">Remove Project from team</h2>

<p>
</p>

<p>Remove a project assignment from the team.</p>

<span id="example-requests-DELETEapi-v1-teams--team_id--projects--project_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/v1/teams/16/projects/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"project_id\": 3
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/teams/16/projects/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "project_id": 3
};

fetch(url, {
    method: "DELETE",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-teams--team_id--projects--project_id-">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Project removed from team successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (400, invalid argument):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to remove project from team: Project not found&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Team not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to remove project from team: Internal server error&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-teams--team_id--projects--project_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-teams--team_id--projects--project_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-teams--team_id--projects--project_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-teams--team_id--projects--project_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-teams--team_id--projects--project_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-teams--team_id--projects--project_id-" data-method="DELETE"
      data-path="api/v1/teams/{team_id}/projects/{project_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-teams--team_id--projects--project_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-teams--team_id--projects--project_id-"
                    onclick="tryItOut('DELETEapi-v1-teams--team_id--projects--project_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-teams--team_id--projects--project_id-"
                    onclick="cancelTryOut('DELETEapi-v1-teams--team_id--projects--project_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-teams--team_id--projects--project_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/teams/{team_id}/projects/{project_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-teams--team_id--projects--project_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-teams--team_id--projects--project_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>team_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="team_id"                data-endpoint="DELETEapi-v1-teams--team_id--projects--project_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the team. Example: <code>16</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="DELETEapi-v1-teams--team_id--projects--project_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>16</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="DELETEapi-v1-teams--team_id--projects--project_id-"
               value="3"
               data-component="body">
    <br>
<p>The ID of the project to remove. Example: <code>3</code></p>
        </div>
        </form>

                <h1 id="user-management">User Management</h1>

    <p>APIs for managing users</p>

                                <h2 id="user-management-GETapi-v1-users">List Users</h2>

<p>
</p>

<p>Get a paginated list of all users with optional filtering.</p>

<span id="example-requests-GETapi-v1-users">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/users?per_page=15&amp;name=John&amp;email=john%40example.com&amp;role=admin" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/users"
);

const params = {
    "per_page": "15",
    "name": "John",
    "email": "john@example.com",
    "role": "admin",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-users">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 37,
            &quot;name&quot;: &quot;Aubrey Lehner&quot;,
            &quot;email&quot;: &quot;osinski.madyson@example.org&quot;,
            &quot;roles&quot;: []
        },
        {
            &quot;id&quot;: 38,
            &quot;name&quot;: &quot;Lourdes Purdy&quot;,
            &quot;email&quot;: &quot;brook22@example.org&quot;,
            &quot;roles&quot;: []
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;/?page=1&quot;,
        &quot;last&quot;: &quot;/?page=1&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;from&quot;: 1,
        &quot;last_page&quot;: 1,
        &quot;links&quot;: [
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;&amp;laquo; Previous&quot;,
                &quot;page&quot;: null,
                &quot;active&quot;: false
            },
            {
                &quot;url&quot;: &quot;/?page=1&quot;,
                &quot;label&quot;: &quot;1&quot;,
                &quot;page&quot;: 1,
                &quot;active&quot;: true
            },
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;Next &amp;raquo;&quot;,
                &quot;page&quot;: null,
                &quot;active&quot;: false
            }
        ],
        &quot;path&quot;: &quot;/&quot;,
        &quot;per_page&quot;: 10,
        &quot;to&quot;: 2,
        &quot;total&quot;: 2
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;John Doe&quot;,
            &quot;email&quot;: &quot;john@example.com&quot;
        }
    ],
    &quot;links&quot;: {},
    &quot;meta&quot;: {}
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, forbidden):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;per_page&quot;: [
            &quot;The per page must be an integer.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to retrieve user list&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-users" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-users"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-users"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-users" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-users">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-users" data-method="GET"
      data-path="api/v1/users"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-users', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-users"
                    onclick="tryItOut('GETapi-v1-users');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-users"
                    onclick="cancelTryOut('GETapi-v1-users');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-users"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/users</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-users"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-users"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-users"
               value="15"
               data-component="query">
    <br>
<p>Number of users per page. Defaults to 10. Example: <code>15</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="GETapi-v1-users"
               value="John"
               data-component="query">
    <br>
<p>Filter users by name (partial match). Example: <code>John</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="GETapi-v1-users"
               value="john@example.com"
               data-component="query">
    <br>
<p>Filter users by email (partial match). Example: <code>john@example.com</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="role"                data-endpoint="GETapi-v1-users"
               value="admin"
               data-component="query">
    <br>
<p>Filter users by role. Example: <code>admin</code></p>
            </div>
                </form>

                    <h2 id="user-management-GETapi-v1-users--user_id-">Get user</h2>

<p>
</p>

<p>Get details of a specific user by ID.</p>

<span id="example-requests-GETapi-v1-users--user_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/users/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/users/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-users--user_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 39,
        &quot;name&quot;: &quot;Ms. Elisabeth Okuneva&quot;,
        &quot;email&quot;: &quot;gulgowski.asia@example.com&quot;,
        &quot;roles&quot;: []
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;John Doe&quot;,
        &quot;email&quot;: &quot;john@example.com&quot;,
        &quot;roles&quot;: [
            &quot;admin&quot;
        ]
    },
    &quot;message&quot;: &quot;User retrieved successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;User not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to retrieve user&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-users--user_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-users--user_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-users--user_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-users--user_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-users--user_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-users--user_id-" data-method="GET"
      data-path="api/v1/users/{user_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-users--user_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-users--user_id-"
                    onclick="tryItOut('GETapi-v1-users--user_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-users--user_id-"
                    onclick="cancelTryOut('GETapi-v1-users--user_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-users--user_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/users/{user_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-users--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-users--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="GETapi-v1-users--user_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>16</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-users--user_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="user-management-POSTapi-v1-users">Create user</h2>

<p>
</p>

<p>Create a new user with assigned roles.</p>

<span id="example-requests-POSTapi-v1-users">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/users" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"John Doe\",
    \"email\": \"john.doe@example.com\",
    \"password\": \"SecurePass123!\",
    \"password_confirmation\": \"SecurePass123!\",
    \"roles\": [
        \"admin\",
        \"project manager\"
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/users"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "John Doe",
    "email": "john.doe@example.com",
    "password": "SecurePass123!",
    "password_confirmation": "SecurePass123!",
    "roles": [
        "admin",
        "project manager"
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-users">
            <blockquote>
            <p>Example response (201, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;John Doe&quot;,
        &quot;email&quot;: &quot;john.doe@example.com&quot;,
        &quot;roles&quot;: [
            &quot;admin&quot;
        ]
    },
    &quot;message&quot;: &quot;User created successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;email&quot;: [
            &quot;The email has already been taken.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to create user&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-users" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-users"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-users"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-users" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-users">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-users" data-method="POST"
      data-path="api/v1/users"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-users', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-users"
                    onclick="tryItOut('POSTapi-v1-users');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-users"
                    onclick="cancelTryOut('POSTapi-v1-users');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-users"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/users</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-users"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-users"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-users"
               value="John Doe"
               data-component="body">
    <br>
<p>The name of the user. Example: <code>John Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-users"
               value="john.doe@example.com"
               data-component="body">
    <br>
<p>The email address of the user. Must be unique. Example: <code>john.doe@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-users"
               value="SecurePass123!"
               data-component="body">
    <br>
<p>The password for the user account. Must be at least 8 characters. Example: <code>SecurePass123!</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password_confirmation"                data-endpoint="POSTapi-v1-users"
               value="SecurePass123!"
               data-component="body">
    <br>
<p>Password confirmation. Must match password field. Example: <code>SecurePass123!</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>roles</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="roles[0]"                data-endpoint="POSTapi-v1-users"
               data-component="body">
        <input type="text" style="display: none"
               name="roles[1]"                data-endpoint="POSTapi-v1-users"
               data-component="body">
    <br>
<p>Array of roles to assign to the user. Allowed values: admin, project manager, team lead, team member.</p>
        </div>
        </form>

                    <h2 id="user-management-PUTapi-v1-users--user_id-">Update user</h2>

<p>
</p>

<p>Update an existing user's information.</p>

<span id="example-requests-PUTapi-v1-users--user_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/v1/users/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"John Doe Updated\",
    \"email\": \"john.updated@example.com\",
    \"password\": \"NewSecurePass123!\",
    \"password_confirmation\": \"NewSecurePass123!\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/users/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "John Doe Updated",
    "email": "john.updated@example.com",
    "password": "NewSecurePass123!",
    "password_confirmation": "NewSecurePass123!"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-users--user_id-">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;John Doe Updated&quot;,
        &quot;email&quot;: &quot;john.updated@example.com&quot;,
        &quot;roles&quot;: [
            &quot;admin&quot;
        ]
    },
    &quot;message&quot;: &quot;User updated successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;User not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;email&quot;: [
            &quot;The email has already been taken.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to update user&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-users--user_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-users--user_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-users--user_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-users--user_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-users--user_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-users--user_id-" data-method="PUT"
      data-path="api/v1/users/{user_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-users--user_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-users--user_id-"
                    onclick="tryItOut('PUTapi-v1-users--user_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-users--user_id-"
                    onclick="cancelTryOut('PUTapi-v1-users--user_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-users--user_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/users/{user_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-users--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-users--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="PUTapi-v1-users--user_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>16</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-users--user_id-"
               value="John Doe Updated"
               data-component="body">
    <br>
<p>The name of the user. Example: <code>John Doe Updated</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="PUTapi-v1-users--user_id-"
               value="john.updated@example.com"
               data-component="body">
    <br>
<p>The email address of the user. Example: <code>john.updated@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="PUTapi-v1-users--user_id-"
               value="NewSecurePass123!"
               data-component="body">
    <br>
<p>The new password (optional). Must be at least 8 characters if provided. Example: <code>NewSecurePass123!</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="password_confirmation"                data-endpoint="PUTapi-v1-users--user_id-"
               value="NewSecurePass123!"
               data-component="body">
    <br>
<p>Password confirmation (required if password is provided). Example: <code>NewSecurePass123!</code></p>
        </div>
        </form>

                    <h2 id="user-management-DELETEapi-v1-users--user_id-">Soft delete user</h2>

<p>
</p>

<p>Soft delete a user from the system.</p>

<span id="example-requests-DELETEapi-v1-users--user_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/v1/users/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/users/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-users--user_id-">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;User deleted successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;User not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to delete user&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-users--user_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-users--user_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-users--user_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-users--user_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-users--user_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-users--user_id-" data-method="DELETE"
      data-path="api/v1/users/{user_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-users--user_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-users--user_id-"
                    onclick="tryItOut('DELETEapi-v1-users--user_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-users--user_id-"
                    onclick="cancelTryOut('DELETEapi-v1-users--user_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-users--user_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/users/{user_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-users--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-users--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="DELETEapi-v1-users--user_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>16</code></p>
            </div>
                    </form>

                    <h2 id="user-management-POSTapi-v1-users--userId--restore">Restore soft-deleted user</h2>

<p>
</p>

<p>Restore a previously deleted user.</p>

<span id="example-requests-POSTapi-v1-users--userId--restore">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/users/16/restore" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/users/16/restore"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-users--userId--restore">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;John Doe&quot;,
        &quot;email&quot;: &quot;john@example.com&quot;,
        &quot;roles&quot;: [
            &quot;admin&quot;
        ]
    },
    &quot;message&quot;: &quot;User restored successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;User not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to restore user&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-users--userId--restore" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-users--userId--restore"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-users--userId--restore"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-users--userId--restore" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-users--userId--restore">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-users--userId--restore" data-method="POST"
      data-path="api/v1/users/{userId}/restore"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-users--userId--restore', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-users--userId--restore"
                    onclick="tryItOut('POSTapi-v1-users--userId--restore');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-users--userId--restore"
                    onclick="cancelTryOut('POSTapi-v1-users--userId--restore');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-users--userId--restore"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/users/{userId}/restore</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-users--userId--restore"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-users--userId--restore"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>userId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="userId"                data-endpoint="POSTapi-v1-users--userId--restore"
               value="16"
               data-component="url">
    <br>
<p>Example: <code>16</code></p>
            </div>
                    </form>

                    <h2 id="user-management-POSTapi-v1-users--user_id--roles">Assign user role</h2>

<p>
</p>

<p>Update the roles assigned to a specific user. Note: Cannot remove 'team lead' role if user is actively leading teams.</p>

<span id="example-requests-POSTapi-v1-users--user_id--roles">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/users/16/roles" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"roles\": [
        \"team lead\",
        \"project manager\"
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/users/16/roles"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "roles": [
        "team lead",
        "project manager"
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-users--user_id--roles">
            <blockquote>
            <p>Example response (200, success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;John Doe&quot;,
        &quot;email&quot;: &quot;john@example.com&quot;,
        &quot;roles&quot;: [
            &quot;team lead&quot;,
            &quot;project manager&quot;
        ]
    },
    &quot;message&quot;: &quot;Roles assigned successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;User not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error - active team lead):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Cannot remove team lead role: user is actively leading teams&quot;,
    &quot;errors&quot;: {
        &quot;roles&quot;: [
            &quot;User must be removed as team lead from all teams before removing this role.&quot;
        ],
        &quot;active_teams&quot;: [
            &quot;Backend Team&quot;,
            &quot;DevOps Team&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, validation error - invalid roles):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;roles.0&quot;: [
            &quot;The selected roles.0 is invalid.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;Failed to assign roles&quot;,
    &quot;errors&quot;: [],
    &quot;meta&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-users--user_id--roles" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-users--user_id--roles"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-users--user_id--roles"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-users--user_id--roles" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-users--user_id--roles">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-users--user_id--roles" data-method="POST"
      data-path="api/v1/users/{user_id}/roles"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-users--user_id--roles', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-users--user_id--roles"
                    onclick="tryItOut('POSTapi-v1-users--user_id--roles');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-users--user_id--roles"
                    onclick="cancelTryOut('POSTapi-v1-users--user_id--roles');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-users--user_id--roles"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/users/{user_id}/roles</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-users--user_id--roles"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-users--user_id--roles"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="POSTapi-v1-users--user_id--roles"
               value="16"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>16</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>roles</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="roles[0]"                data-endpoint="POSTapi-v1-users--user_id--roles"
               data-component="body">
        <input type="text" style="display: none"
               name="roles[1]"                data-endpoint="POSTapi-v1-users--user_id--roles"
               data-component="body">
    <br>
<p>Array of roles to assign to the user. Allowed values: admin, project manager, team lead, team member.</p>
        </div>
        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
